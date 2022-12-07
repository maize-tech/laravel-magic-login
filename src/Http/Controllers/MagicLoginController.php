<?php

namespace Maize\MagicLogin\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Maize\MagicLogin\Http\Requests\MagicLoginRequest;
use Maize\MagicLogin\Models\MagicLogin;
use Maize\MagicLogin\Support\Config;

class MagicLoginController
{
    public function __invoke(MagicLoginRequest $request): Redirector|RedirectResponse
    {
        $model = null;

        try {
            /** @var MagicLogin */
            $model = Config::getModel()
                ->query()
                ->with('authenticatable')
                ->findOrFail($request->uuid());

            $model->login();

            return redirect($model->redirect_url);
        } catch (Exception) {
            auth()->guard($model?->guard)->logout();
            throw Config::getException();
        }
    }
}
