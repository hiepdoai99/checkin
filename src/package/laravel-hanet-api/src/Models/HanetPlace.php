<?php

namespace Htqxd\LaravelHanetApi\Models;

use Illuminate\Database\Eloquent\Model;

class HanetPlace extends Model
{
    protected $table = 'hanet_places';

    protected $guarded = [];

    protected $fillable = [
        'placeID', 'address', 'name', 'userID', 'dept_id'
    ];

    public $timestamps = false;

    public function getRules() : array
    {
        return [
            'placeID' => ['required', 'unique:'.$this->table.',placeID,'.request()->id],
            'address' => ['required'],
            'name' => ['required'],
            'userID' => ['nullable'],
            'dept_id' => ['nullable'],
        ];
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePlaceID($query, $placeID)
    {
        return $query->where('placeID', $placeID);
    }
}
