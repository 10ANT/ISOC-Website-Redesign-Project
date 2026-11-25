<?php

namespace App\Providers;

use App\Models\Programme;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->environment('production'));
        Paginator::useBootstrapFive();

        Gate::define('access-admin', fn (User $user) => $user->isStaff() && $user->isApproved());
        Gate::define('manage-users', fn (User $user) => $user->isAdmin() && $user->isApproved());

        // Markdown is rendered with raw HTML stripped and unsafe links blocked.
        Blade::directive('markdown', fn ($expression) => "<?php echo \\App\\Providers\\AppServiceProvider::markdown({$expression}); ?>");

        // Site settings and the programme menu are used across public views; load once per request.
        View::composer(['layouts.app', 'partials.*', 'home', 'pages.*'], function ($view) {
            $view->with(once(fn () => [
                'settings' => Setting::allCached(),
                'navProgrammes' => Programme::ordered()->get(['id', 'title', 'slug']),
            ]));
        });
    }

    public static function markdown(?string $text): string
    {
        return Str::markdown((string) $text, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }
}
