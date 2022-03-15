<?php

namespace App\Search\Filters;

use Illuminate\Database\Eloquent\Builder;

class Userstatus implements Filter
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
        switch ( $value ) {

            //If we want all the users
            case "all" :

                try {
                    //Get all users even if they are deleted
                    return $builder->withTrashed();

                } catch ( \Exception $e ) {

                    return $e->getMessage();

                }
                
                break;
            
            //If we want only the active users
            case "active" :
                //We just return the $builder
                return $builder;
                break;
            
            //If we want only the soft deleted users
            case "trashed" :

                try {
                    //Get only the deleted users
                    return $builder->onlyTrashed();

                } catch ( \Exception $e ) {

                    return $e->getMessage();

                }
            
                break;
            
            //For defailt we ant active and soft deleted users
            default :

                try {
                    //Get all users even if they are deleted
                    return $builder->withTrashed();

                } catch ( \Exception $e ) {

                    return $e->getMessage();

                }
            
                break;
        }

    }

}