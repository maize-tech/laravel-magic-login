<?php

namespace Maize\MagicLogin\Actions;

use Illuminate\Support\Facades\Notification;
use Maize\MagicLogin\Models\MagicLogin;
use Maize\MagicLogin\Notifications\MagicLinkNotification;

class SendMagicLinkAction
{
    public function __invoke(string $uri, MagicLogin $model): void
    {
        Notification::send(
            [$model->authenticatable],
            new MagicLinkNotification($uri, $model)
        );
    }
}
