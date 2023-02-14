<?php

namespace Maize\MagicLogin\Actions;

use Illuminate\Support\Facades\Notification;
use Maize\MagicLogin\Models\MagicLogin;
use Maize\MagicLogin\Support\Config;

class SendMagicLinkAction
{
    public function __invoke(string $uri, MagicLogin $model): void
    {
        $notification = Config::getNotification();

        Notification::send(
            [$model->authenticatable],
            new $notification($uri, $model)
        );
    }
}
