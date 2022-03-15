<?php

namespace App\Search\Filters;

use Illuminate\Database\Eloquent\Builder;

class Status implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        return $builder->whereHas('status', function ($q) use ($value) {
            $q->where('status','=', $value);
         });

    }

}