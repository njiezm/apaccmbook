<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordFrench extends ResetPassword
{
    protected function buildMailMessage($url): MailMessage
    {
        $count = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe — APACC-M e-Livre')
            ->greeting('Bonjour,')
            ->line('Vous recevez cet email car une demande de réinitialisation de mot de passe a été effectuée pour votre compte.')
            ->action('Réinitialiser mon mot de passe', $url)
            ->line("Ce lien de réinitialisation expirera dans {$count} minutes.")
            ->line("Si vous n'avez pas demandé de réinitialisation, aucune action n'est requise.")
            ->salutation('Cordialement, — L\'équipe APACC-M e-Livre');
    }
}
