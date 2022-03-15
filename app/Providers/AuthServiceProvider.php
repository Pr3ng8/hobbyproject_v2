<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\User' => [
            'App\Policies\AdminUserPolicy',
            'App\Policies\UserPolicy',
        ],
        'App\Boat' => 'App\Policies\AdminBoatPolicy',
        'App\Post' => [
            'App\Policies\AuthorPostPolicy',
            'App\Policies\AdminPostPolicy'
        ],
        'App\Comment' => 'App\Policies\CommentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('post', 'App\Policies\AuthorPostPolicy');
        Gate::define('post.restore','App\Policies\AuthorPostPolicy@restore');
        Gate::define('post.edit','App\Policies\AuthorPostPolicy@update');

        Gate::resource('admin-post', 'App\Policies\AdminPostPolicy');
        Gate::define('admin-post.restore','App\Policies\AdminPostPolicy@restore');
        Gate::define('admin-post.edit','App\Policies\AdminPostPolicy@update');

        Gate::resource('admin-user', 'App\Policies\AdminUserPolicy');

        Gate::resource('boat', 'App\Policies\AdminBoatPolicy');
        Gate::resource('comment', 'App\Policies\CommentPolicy');

        Gate::define('user.view', 'App\Policies\UserPolicy@view');
        Gate::define('user.edit', 'App\Policies\UserPolicy@update');
        Gate::define('user.update', 'App\Policies\UserPolicy@update');


        Gate::define('admin-menu', function ($user) {
            return $user->hasAccess(['administrator']);
        });

        Gate::define('author-menu', function ($user) {
            return $user->hasAccess( ["author","administrator"] );
        });
    }
}
