<?php


namespace App\Models\Tenant\AssignedTask\Relationship;


use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Core\Traits\StatusRelationship;
use App\Models\Tenant\AssignedTask\AssignedTask;
use App\Models\Tenant\Traits\CommentableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait AssignedTaskRelationship
{
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function assignBy(): belongsTo
    {
        return $this->belongsTo(User::class, 'job_assignor_id', 'id');
    }
}