<?php

namespace App\Http\Controllers\Tenant\Payroll;

use App\Filters\Tenant\PayslipFilter;
use App\Helpers\Traits\ConflictedPayslipQueryHelpers;
use App\Helpers\Traits\DateRangeHelper;
use App\Helpers\Traits\SettingKeyHelper;
use App\Helpers\Traits\TenantAble;
use App\Http\Controllers\Controller;
use App\Jobs\Tenant\SendPayslipJob;
use App\Models\Core\Auth\User;
use App\Models\Tenant\Holiday\Holiday;
use App\Models\Tenant\Payroll\Payslip;
use App\Repositories\Core\Setting\SettingRepository;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Payroll\PayrunService;
use App\Services\Tenant\Payroll\PayslipService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PayslipController extends Controller
{
    use DateRangeHelper, SettingKeyHelper, TenantAble, ConflictedPayslipQueryHelpers;

    public PayrunService $payrunService;

    public function __construct(PayslipService $service, PayslipFilter $filter, PayrunService $payrunService)
    {
        $this->service = $service;
        $this->filter = $filter;
        $this->payrunService = $payrunService;
    }

    public function index()
    {
        $within = request()->get('within');
        $month = $within ?: request('month_number') + 1;
        $ranges = $this->convertRangesToStringFormat($this->getStartAndEndOf($month, request()->get('year')));

        if ($within == 'total' && Payslip::query()->exists()) {
            $min_date = Payslip::query()->oldest('start_date')->first()->start_date;
            $max_date = Payslip::query()->latest('end_date')->first()->end_date;
            $ranges = [$min_date, $max_date];
        }

        $payslips = $this->service
            ->filters($this->filter)
            ->with($this->service->getRelations())
            ->whereBetween(DB::raw('DATE(start_date)'), count($ranges) == 1 ? [$ranges[0], $ranges[0]] : $ranges)
            ->whereBetween(DB::raw('DATE(end_date)'), count($ranges) == 1 ? [$ranges[0], $ranges[0]] : $ranges)
            ->when(request()->has('conflicted') && request()->get('conflicted') == 'true', function (Builder $builder){
                $builder->whereIn('id', $this->getConflictedPayslip());
            })->latest()
            ->paginate(request()->get('per_page', 10));

        $payslips->map(function ($payslip){
            $conflictedData = $this->conflictedUserPayslip($payslip, $payslip->start_date, $payslip->end_date);
            $payslip->conflicted = $conflictedData->count();
        });

        return $payslips;
    }

    public function sendPayslip(Payslip $payslip)
    {
        SendPayslipJob::dispatch($payslip);

        $statusPending = resolve(StatusRepository::class)->payslipPending();
        $payslip->update(['status_id' => $statusPending]);

        return response()->json(['status' => true, 'message' => trans('default.payslip_has_started_sending')]);

    }

    public function sendMonthlyPayslip()
    {
        $within = request()->get('within');
        $month = $within ?: request('month_number') + 1;
        $ranges = $this->convertRangesToStringFormat($this->getStartAndEndOf($month, request()->get('year')));

        if ($within == 'total') {
            $min_date = Payslip::query()->oldest('start_date')->first()->start_date;
            $ranges = [$min_date->toDateString(), todayFromApp()->toDateString()];
        }

        $payslipsQuery = $this->service
            ->filters($this->filter)
            ->with($this->service->getRelations())
            ->whereBetween(DB::raw('DATE(start_date)'), count($ranges) == 1 ? [$ranges[0], $ranges[0]] : $ranges)
            ->whereBetween(DB::raw('DATE(end_date)'), count($ranges) == 1 ? [$ranges[0], $ranges[0]] : $ranges);

        $payslipsQuery->get()->each(fn(Payslip $payslip) => SendPayslipJob::dispatch($payslip));

        $statusPending = resolve(StatusRepository::class)->payslipPending();
        $payslipsQuery->update(['status_id' => $statusPending]);

        return response()->json(['status' => true, 'message' => trans('default.payslip_has_started_sending')]);

    }

    public function showPdf(Payslip $payslip)
    {
        $payslip->load($this->service->getRelations());
        $beneficiaries = count($payslip->beneficiaries) ? $payslip->beneficiaries : ($payslip->without_beneficiary ? [] : $payslip->payrun->beneficiaries);
        $salaryAmount = $payslip->basic_salary;
        $totalAllowance = $this->service->getTotalBeneficiary($beneficiaries, $salaryAmount, 'allowance');
        $totalDeduction = $this->service->getTotalBeneficiary($beneficiaries, $salaryAmount, 'deduction');
        $payslipFor = $this->getDateDifferenceString($payslip->start_date, $payslip->end_date);
        [$setting_able_id, $setting_able_type] = $this->tenantAble();
        $settings = (object)resolve(SettingRepository::class)
            ->getFormattedSettings('tenant', $setting_able_type, $setting_able_id);
        $payslip_settings = json_decode($payslip->payrun->data);
//        $payslip_settings = (object)resolve(SettingRepository::class)
//            ->getFormattedSettings('payslip', $setting_able_type, $setting_able_id);
        $pdf = PDF::loadView('tenant.payroll.pdf.payslip',
            compact(
                'payslip',
                'beneficiaries',
                'totalAllowance',
                'totalDeduction',
                'settings',
                'salaryAmount',
                'payslipFor',
                'payslip_settings'
            )
        );
        $fileName = 'Payslip for ' . $payslip->user->full_name . ' (' . ($payslip->user->profile ? $payslip->user->profile->employee_id : 'uid') . ').pdf';
        if (request()->get('download') == true) {
            return $pdf->download($fileName);
        }
        return $pdf->stream($fileName);
    }

    public function update(Payslip $payslip)
    {
        DB::transaction(function () use ($payslip){
            $this->service
                ->setModel($payslip)
                ->setAttributes(\request()->only([
                    'allowances',
                    'allowanceValues',
                    'allowancePercentages',
                    'deductions',
                    'deductionValues',
                    'deductionPercentages',
                ]))
                ->beneficiariesValidation()
                ->updateBeneficiaries();

            if (count(\request()->get('allowances',[])) == 0 && count(\request()->get('deductions',[])) == 0){
                $payslip->without_beneficiary = true;
            }

            $settings = [
                'consider_type' => $payslip->consider_type,
                'period' => $payslip->period,
                'consider_overtime' => $payslip->consider_overtime
            ];
            $ranges = [$payslip->start_date, $payslip->end_date];
            $net_salary = $this->payrunService->countNetSalary($payslip->user, $settings, $payslip->beneficiaries, $ranges);

            $payslip->net_salary = $net_salary;

            $payslip->update();
        });

        return updated_responses('payslip');
    }

    public function destroy(Payslip $payslip)
    {
        $payslip->beneficiaries()->delete();
        $payslip->delete();

        return deleted_responses('payslip');
    }
    public function provisionalSalary()
    {
        $id = request('id');

        $salary = Payslip::filters($this->filter)
            ->select(['basic_salary'])
            ->where('user_id',$id)
            ->get();

        $ranges = $this->getStartAndEndOf('thisMonth', nowFromApp()->year);

        $totalScheduled = $this->attendanceSummaryService
            ->setModel(auth()->user())
            ->setRanges($ranges)
            ->setHolidays(
                $this->attendanceSummaryService
                    ->generateEmployeeHolidaysFromDepartments(auth()->user()->departments)
                    ->merge(Holiday::generalHolidays($ranges))
            )->getTotalScheduled();

        $work = round($totalScheduled/60/60/9.5);

        $attendanceApprove = resolve(StatusRepository::class)->attendanceApprove();

        $users = User::filters($this->userFilter)
            ->select(['id', 'first_name', 'last_name'])
            ->where('id',$id)
            ->with([
                'attendances' => function (HasMany $builder) use ($attendanceApprove, $ranges) {
                    $builder->select(['id', 'in_date', 'user_id', 'behavior']);
                    if (count($ranges) == 1) {
                        return $builder->whereDate('in_date', $ranges[0])
                            ->where('status_id', $attendanceApprove);
                    }
                    return $builder->whereDate('in_date', '>=', $ranges[0])
                        ->where('status_id', $attendanceApprove)
                        ->whereHas(
                            'details',
                            fn(Builder $bl) => $bl->whereDate('out_time', '<=', $ranges[1])
                                ->where('status_id', $attendanceApprove)
                        );
                },
                'attendances.details' => function (HasMany $details) use ($attendanceApprove) {
                    $details->where('status_id', $attendanceApprove)
                        ->orderBy('in_time', 'DESC')
                        ->select([
                            'id',
                            'in_time',
                            'out_time',
                            'attendance_id',
                            'status_id',
                            'review_by',
                            'added_by',
                            'attendance_details_id',
                            'in_ip_data',
                            'out_ip_data',
                            'in_time_late',
                            'out_time_early',
                            'works',
                        ]);
                },'department','roles'
            ])->get();
        $data = $users->reduce(function ($user, $currentUser) {

            $attendance = $currentUser->attendances;
            $a = $attendance->reduce(function ($attendance,$currentAttendance){
                $details = $currentAttendance->details;
                $t = $details->reduce(function ($details,$currentDetails){
                    $works = $currentDetails->works;

                    return [
                        'work' => $details['work'] + $works,

                    ];
                },['work' => 0]);

                return [
                    'work' => $attendance['work'] + $t['work'],

                ];
            }, ['work' => 0]);

            return $a['work'];
        }, []);
        $a = $salary[0]['basic_salary'];
        $b = round($a/$work , 3);
        $c = $b * $data;

        return ['data'=>[
            'totalWorkMax'=>$work,
            'salaryBasic' => $salary[0]['basic_salary'],
            'workCurrent'=>$data,
            'provisionalSalary'=>$c,
        ]];
    }
}
