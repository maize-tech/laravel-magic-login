<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Magic Login model
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the magic login
    | model.
    | By default, the value is Maize\MagicLogin\Models\MagicLogin::class
    |
    */

    'model' => null,

    /*
    |--------------------------------------------------------------------------
    | Expiration time
    |--------------------------------------------------------------------------
    |
    | Here you may specify the amount of minutes before the magic login link
    | expires.
    | By default, the value is 120 minutes (2 hours).
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify the guard you want to use to authenticate the user.
    | The guard name must be defined in your application's auth.php config file.
    | By default, the value is 'web'.
    |
    */

    'guard' => null,

    /*
    |--------------------------------------------------------------------------
    | Default redirect url
    |--------------------------------------------------------------------------
    |
    | Here you may specify the redirect url used by default if none is specified
    | when creating the magic link.
    |
    */

    'redirect_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Exception class
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default exception class used for all package
    | related exceptions.
    | Useful if you need to customize the http status code in case an exception
    | is thrown during the magic login process.
    | By default, the value is Illuminate\Routing\Exceptions\InvalidSignatureException::class
    |
    */

    'exception' => null,

    /*
    |--------------------------------------------------------------------------
    | Force single link
    |--------------------------------------------------------------------------
    |
    | Here you can specify whether a user can only have one valid magic link
    | at a time or not.
    | If true, when you generate a new magic link for a specific user all
    | previously generated links will be revoked.
    | By default, the value is true.
    |
    */

    'force_single' => null,

    /*
    |--------------------------------------------------------------------------
    | Logins limit
    |--------------------------------------------------------------------------
    |
    | Here you can specify the amount of logins a user can perform with the
    | same magic link.
    | Can be either -1, which lets the user login indefinitely, or any number
    | greater than or equal to 1.
    | By default, the value is -1.
    |
    */

    'logins_limit' => null,

    /*
    |--------------------------------------------------------------------------
    | Send Notification Action
    |--------------------------------------------------------------------------
    |
    | Here you can specify the fully qualified class name of a custom action
    | used to send the magic login email notification.
    | By default, the value is Maize\MagicLogin\Actions\SendMagicLinkAction::class
    */

    'send_notification_action' => null,

    'route' => [

        /*
        |--------------------------------------------------------------------------
        | Route method
        |--------------------------------------------------------------------------
        |
        | Here you may specify the route's allowed methods.
        | By default, the value is 'GET'.
        |
        */

        'methods' => null,

        /*
        |--------------------------------------------------------------------------
        | Route URI
        |--------------------------------------------------------------------------
        |
        | Here you may specify the route's uri.
        | By default, the value is 'magic-login'.
        |
        */

        'uri' => null,

        /*
        |--------------------------------------------------------------------------
        | Route name
        |--------------------------------------------------------------------------
        |
        | Here you may specify the route's name.
        | By default, the value is 'magic-login'.
        |
        */

        'name' => null,

        /*
        |--------------------------------------------------------------------------
        | Route controller
        |--------------------------------------------------------------------------
        |
        | Here you may specify the fully qualified class name of a custom controller
        | class used to handle the magic login request.
        | By default, the value is Maize\MagicLogin\Http\Controllers\MagicLoginController::class.
        |
        */

        'controller' => null,

        /*
        |--------------------------------------------------------------------------
        | Route middlewares
        |--------------------------------------------------------------------------
        |
        | Here you may specify the list of middlewares used by the magic login route.
        | By default, the value is Maize\MagicLogin\Http\Middleware\ValidateSignature::class.
        |
        */

        'middleware' => null,

    ],

];
