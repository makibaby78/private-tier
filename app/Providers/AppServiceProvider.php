<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot()
    {
        // $this->forceHttpsForNgrok(app(Request::class));
    }

    protected function forceHttpsForNgrok(Request $request): void
    {
        // if ($this->app->environment('local')) {
        //     URL::forceScheme('https');
        //     $request->server->set('HTTPS', 'on');
        // }
    }
}
