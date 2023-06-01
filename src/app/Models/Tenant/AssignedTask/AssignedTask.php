<?php

namespace App\Models\Tenant\AssignedTask;

use App\Helpers\Traits\DateRangeHelper;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\Tenant\AssignedTask\Relationship\AssignedTaskRelationship;
use App\Models\Tenant\AssignedTask\role\AssignedTaskRules;
use App\Models\Tenant\TenantModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AssignedTask extends TenantModel
{
    use AssignedTaskRelationship, AssignedTaskRules, StatusRelationship;

    protected $fillable = [
        'name', 'description', 'status_id', 'start_date', 'end_date', 'user_id','job_assignor_id'
    ];

    public static array $statuses = [
        'slacking', 'done', 'late','unfinished'
    ];


}
