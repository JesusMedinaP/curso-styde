<?php

namespace App\Providers;

use App\Profession;
use App\Skill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::withoutDoubleEncoding();
        Blade::component('shared._card', 'card');

        Blade::directive('render', function ($expression){
            $parts = explode(',', $expression,2 );
            $component = $parts[0];
            $args = trim($parts[1] ?? '[]');

            return "<?php echo app('App\Http\ViewComponents\\\\'.$component, $args)->toHtml()?>";
        });

        Builder::macro('whereQuery', function ($subquery, $value){
            $this->addBinding($subquery->getBindings());
            $this->where(DB::raw("({$subquery->toSql()})"), $value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
