<?php

namespace App\Providers;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        if(Schema::hasTable('permissions')){
          $slugs = DB::table('permissions')->pluck('slug');
          foreach ($slugs as $slug) {
            # code...
            Gate::define($slug, function ($user) use ($slug) { 
              return $user->can($slug);
            });
          }
        }
        
        //start
       /* Gate::define('area-read', function ($user) { return $user->can('area-read'); });
        Gate::define('area-create', function ($user) { return $user->can('area-create'); });
        Gate::define('area-edit', function ($user) { return $user->can('area-edit'); });
        Gate::define('area-delete', function ($user) { return $user->can('area-delete'); });
        Gate::define('designation-read', function ($user) { return $user->can('designation-read'); });
        Gate::define('designation-create', function ($user) { return $user->can('designation-create'); });
        Gate::define('designation-edit', function ($user) { return $user->can('designation-edit'); });
        Gate::define('designation-delete', function ($user) { return $user->can('designation-delete'); });
        Gate::define('evaluation-read', function ($user) { return $user->can('evaluation-read'); });
        Gate::define('evaluation-create', function ($user) { return $user->can('evaluation-create'); });
        Gate::define('evaluation-edit', function ($user) { return $user->can('evaluation-edit'); });
        Gate::define('evaluation-delete', function ($user) { return $user->can('evaluation-delete'); });
        Gate::define('feature-read', function ($user) { return $user->can('feature-read'); });
        Gate::define('feature-create', function ($user) { return $user->can('feature-create'); });
        Gate::define('feature-edit', function ($user) { return $user->can('feature-edit'); });
        Gate::define('feature-delete', function ($user) { return $user->can('feature-delete'); });
        Gate::define('permission-read', function ($user) { return $user->can('permission-read'); });
        Gate::define('permission-create', function ($user) { return $user->can('permission-create'); });
        Gate::define('permission-edit', function ($user) { return $user->can('permission-edit'); });
        Gate::define('permission-delete', function ($user) { return $user->can('permission-delete'); });
        Gate::define('person-read', function ($user) { return $user->can('person-read'); });
        Gate::define('person-create', function ($user) { return $user->can('person-create'); });
        Gate::define('person-edit', function ($user) { return $user->can('person-edit'); });
        Gate::define('person-delete', function ($user) { return $user->can('person-delete'); });
        Gate::define('position-read', function ($user) { return $user->can('position-read'); });
        Gate::define('position-create', function ($user) { return $user->can('position-create'); });
        Gate::define('position-edit', function ($user) { return $user->can('position-edit'); });
        Gate::define('position-delete', function ($user) { return $user->can('position-delete'); });
        Gate::define('role-read', function ($user) { return $user->can('role-read'); });
        Gate::define('role-create', function ($user) { return $user->can('role-create'); });
        Gate::define('role-edit', function ($user) { return $user->can('role-edit'); });
        Gate::define('role-delete', function ($user) { return $user->can('role-delete'); });
        Gate::define('training-read', function ($user) { return $user->can('training-read'); });
        Gate::define('training-create', function ($user) { return $user->can('training-create'); });
        Gate::define('training-edit', function ($user) { return $user->can('training-edit'); });
        Gate::define('training-delete', function ($user) { return $user->can('training-delete'); });
        Gate::define('user-read', function ($user) { return $user->can('user-read'); });
        Gate::define('user-create', function ($user) { return $user->can('user-create'); });
        Gate::define('user-edit', function ($user) { return $user->can('user-edit'); });
        Gate::define('user-delete', function ($user) { return $user->can('user-delete'); });*/
        //end





















        Passport::routes();
    }
}
