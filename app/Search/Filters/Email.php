<?php

namespace App\Search\Filters;

use Illuminate\Database\Eloquent\Builder;

class Email implements Filter
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('email', $value);
    }
}