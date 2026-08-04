<?php

namespace App\Providers;

use App\Models\Enquiry;
use App\Models\Property;
use App\Policies\EnquiryPolicy;
use App\Policies\PropertyPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Explicit policy registration (in addition to Laravel's naming-convention
        // auto-discovery) so authorization intent is obvious to future maintainers.
        Gate::policy(Property::class, PropertyPolicy::class);
        Gate::policy(Enquiry::class, EnquiryPolicy::class);

        // Force HTTPS URL generation in production (behind AWS ELB/CloudFront, Phase 20).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->brandAuthNotifications();
    }

    /**
     * Laravel's built-in "forgot password" and "verify email" notifications
     * (fired by Password::sendResetLink() and $user->sendEmailVerificationNotification(),
     * both already wired in Phase 12/13) ship with a generic gray Markdown
     * template. Rather than duplicating that plumbing with two more custom
     * Mailables, we simply re-skin the *same* notifications with
     * RealEstatePro's brand color and copy — one Mailable class avoided per
     * flow.
     */
    private function brandAuthNotifications(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Reset Your RealEstatePro Password')
                ->greeting('Hi ' . ($notifiable->name ?? 'there') . ',')
                ->line('You recently requested to reset your password for your RealEstatePro account.')
                ->action('Reset Password', $url)
                ->line('This password reset link will expire in 60 minutes.')
                ->line("If you didn't request a password reset, no further action is required.")
                ->salutation('— The RealEstatePro Team');
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Your RealEstatePro Email Address')
                ->greeting('Hi ' . ($notifiable->name ?? 'there') . ',')
                ->line('Please click the button below to verify your email address and activate your account.')
                ->action('Verify Email Address', $url)
                ->line("If you didn't create an account with RealEstatePro, no further action is required.")
                ->salutation('— The RealEstatePro Team');
        });
    }
}
