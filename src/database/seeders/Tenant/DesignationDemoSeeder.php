<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Employee\Designation;
use Illuminate\Database\Seeder;

class DesignationDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            'Tổng giám đốc',
            'Giám đốc kỹ thuật (CTO)',
            'Quản lý nhân sự',
            'Quản lý dự án',
            'Trưởng phòng kỹ thuật',
            'Kỹ sư phần mềm',
        ];

        Designation::query()->insert(array_map(function ($designation){
            return [
                'name' => $designation,
                'tenant_id' => 1,
            ];
        },$designations));
    }
}
