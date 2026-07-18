<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\AI\Services\AiProviderInterface;
use App\Modules\AI\Services\OpenRouterService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AiProviderInterface::class, function (): OpenRouterService {
            return new OpenRouterService(
                apiKey: config('services.openrouter.api_key') ?? '',
                siteUrl: config('services.openrouter.site_url') ?? '',
                siteName: config('services.openrouter.site_name') ?? '',
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
