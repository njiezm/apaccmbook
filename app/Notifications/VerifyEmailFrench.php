<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailFrench extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Vérifiez votre adresse email — APACC-M e-Livre')
            ->greeting('Bonjour,')
            ->line('Merci de vous être inscrit(e) sur APACC-M e-Livre. Veuillez confirmer votre adresse email en cliquant sur le bouton ci-dessous.')
            ->action('Vérifier mon adresse email', $url)
            ->line("Si vous n'êtes pas à l'origine de cette inscription, vous pouvez ignorer cet email.")
            ->salutation('À bientôt, — L\'équipe APACC-M e-Livre');
    }
}
