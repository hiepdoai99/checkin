<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Core\Traits\HasWhen;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\Tenant\Employee\EmployeeInviteService;
use Illuminate\Support\Facades\DB;


class RegisteredUserController extends Controller
{
    use HasWhen;

    public function __construct(EmployeeInviteService $service)
    {
        $this->service = $service;
    }
    
    public function register(RegisterRequest $request)
    {
        DB::transaction(function () use ($request) {
        $user = $this->service
            ->setAttributes($request->except('allowed_resource', 'tenant_id', 'tenant_short_name'))
            ->register();
        });
        return response()->json([
            'status' => true,
            'message' => trans('default.invite_employee_response')
        ]);
    }
}

