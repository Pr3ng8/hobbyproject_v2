<?php

namespace App\Search;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\{Auth};

class UserSearch {

    /**
     * Apply a given search value to the builder instance.
     * 
     * @param Request $filters
     * @return Builder $builder
     */
    public static function apply(Request $filters)
    {
        $query = 
            static::applyDecoratorsFromRequest(
                $filters, (new User)->newQuery()
            );

        return $query;
    }

    /**
     * 
     * @param Builder $builder
     * @param Request $request
     * @return Builder $builder
     */
    private static function applyDecoratorsFromRequest(Request $request, Builder $query)
    {
        $filters = static::getValidatedData($request);

        foreach ($filters as $filterName => $value) {

            $decorator = static::createFilterDecorator($filterName);

            if (static::isValidDecorator($decorator)) {
                $query = $decorator::apply($query, $value);
            }

        }
        
        $query = static::notCurrentUser($query);

        return $query;
    }

    /**
     * 
     * @param string $name
     * @return string 
     */
    private static function createFilterDecorator($name)
    {
        return __NAMESPACE__ . '\\Filters\\' . 
            str_replace(' ', '', 
                ucwords(str_replace('_', ' ', $name)));
    }
    

    /**
     * Check if the class exists
     * @param string $decorator
     * @return boolean 
     */
    private static function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }

    /**
     * 
     * @param Builder $builder
     * @return Builder $builder
     */
    private static function notCurrentUser(Builder $query)
    {
        return $query->where('id', '<>' ,Auth::id());
    }

    /**
     * 
     * @param Request $request
     * @return array 
     */
    private static function getValidatedData(Request $request)
    {
        return array_filter($request->validated(), function($v, $k) {
            return $v !== NULL;
        }, ARRAY_FILTER_USE_BOTH);
    }

}