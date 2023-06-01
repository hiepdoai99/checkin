<?php


namespace App\Filters\Tenant;


use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use Illuminate\Database\Eloquent\Builder;

class HanetFilter extends FilterBuilder
{
    use DateRangeFilter, SearchFilterTrait;

    public function type($type = null)
    {
        $this->builder->when($type, function (Builder $builder) use ($type) {
            $builder->whereIn('type', explode(',', $type));
        });
    }
}
