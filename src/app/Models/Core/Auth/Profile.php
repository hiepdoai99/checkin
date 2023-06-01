<?php

namespace App\Models\Core\Auth;

use App\Traits\HtqGetNextId;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HtqGetNextId;
    
    protected $fillable = ['user_id', 'gender', 'date_of_birth', 'address',	'contact', 'about_me'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }    

    public function makeEmployeeId()
    {
        return 'EMP-'.$this->getNextId();
    }
}
