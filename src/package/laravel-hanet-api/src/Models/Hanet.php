<?php

namespace Htqxd\LaravelHanetApi\Models;

use Illuminate\Database\Eloquent\Model;

class Hanet extends Model
{
    protected $table = 'hanets';

    protected $fillable = [
        'client_id', 'client_secret', 'access_token', 'refresh_token', 'email', 'userID', 'expire', 'token_type'
    ];

    public $timestamps = false;

    public function getRules() : array
    {
        return [
            'client_id' => ['required', 'unique:hanets,client_id,'.request()->id],
            'client_secret' => ['required'],
            'access_token' => ['required'],
            'refresh_token' => ['nullable'],
            'email' => ['required', 'email'],
            'userID' => ['nullable'],
            'expire' => ['nullable', 'number'],
            'token_type' => ['nullable'],
        ];
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfEmail($query, $email)
    {
        return $query->where('email', $email);
    }

}
