<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\WorkingShift\WorkingShift;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WorkingShiftDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workingShift = WorkingShift::query()->create([
            'name' => 'Ca Sáng',
            'tenant_id' => 1,
            'start_date' => Carbon::now()->subMonths(rand(2,4))->toDateString(),
            'end_date' => Carbon::now()->addMonths(rand(2,4))->toDateString(),
            'is_default' => 0,
            'type' => 'regular'
        ]);

        $workingShift->details()->insert(
            array_map(function ($day) use($workingShift){
                return [
                    'weekday' => $day,
                    'is_weekend' => !!in_array($day, ['sun', 'sat']) ? 1 : 0,
                    'start_at' => !!in_array($day, ['sun', 'sat']) ? null : '1:00:00',
                    'end_at' => !!in_array($day, ['sun', 'sat']) ? null : '5:00:00',
                    'working_shift_id' => $workingShift->id
                ];
            }, config('settings.weekdays'))
        );

        $types = ['regular', 'scheduled'];

        foreach ($types as $type){
            $workingShift = WorkingShift::query()->create([
                'name' => ('scheduled' === $type) ? trans('default.scheduled') : 'Ca Chiều', 
                'tenant_id' => 1,
                'start_date' => Carbon::now()->subMonths(rand(2,4))->toDateString(),
                'end_date' => Carbon::now()->addMonths(rand(2,4))->toDateString(),
                'is_default' => 0,
                'type' => $type
            ]);

            $workingShift->details()->insert(
                array_map(function ($day) use($workingShift){
                    $change = $workingShift->type == 'scheduled' ? rand(true, false): false;
                    return [
                        'weekday' => $day,
                        'is_weekend' => !!in_array($day, ['sun', 'sat']) ? 1 : 0,
                        'start_at' => !!in_array($day, ['sun', 'sat']) ? null : ($change ? '7:00:00' : '6:30:00'),
                        'end_at' => !!in_array($day, ['sun', 'sat']) ? null : ($change ? '9:30:00' : '10:30:00'),
                        'working_shift_id' => $workingShift->id
                    ];
                }, config('settings.weekdays'))
            );
        }

    }
}
