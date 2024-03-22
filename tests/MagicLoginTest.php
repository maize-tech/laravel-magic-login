<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Maize\MagicLogin\Facades\MagicLink;
use Maize\MagicLogin\Models\MagicLogin;
use Maize\MagicLogin\Notifications\MagicLinkNotification;
use Maize\MagicLogin\Support\AuthData;
use Maize\MagicLogin\Tests\Support\Exceptions\InvalidSignatureTestException;
use Maize\MagicLogin\Tests\Support\Models\Admin;
use Maize\MagicLogin\Tests\Support\Models\User;

use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\followingRedirects;
use function Spatie\PestPluginTestTime\testTime;

it('can generate valid uri', function (Authenticatable&Model $user, $generator, $redirectUrl, $guard, $expiration) {
    testTime()->freeze();

    Notification::fake();

    $uri = $generator($user);

    $query = parse_url($uri, PHP_URL_QUERY);
    parse_str($query, $query);

    $data = AuthData::fromString($query['data'])->toArray();

    expect((int) $query['expires'])->toBe(
        now()->addMinutes($expiration)->timestamp
    );

    $model = MagicLogin::first();

    expect($model)
        ->uuid->toBe($data['uuid'])
        ->authenticatable_id->toBe($user->getKey())
        ->authenticatable_type->toBe($user->getMorphClass())
        ->guard->toBe($guard)
        ->redirect_url->toBe($redirectUrl);

    expect($model->expires_at->timestamp)
        ->toBe(now()->addMinutes($expiration)->timestamp);

    Notification::assertNothingSent();
})->with([
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/home'
        ),
        'redirectUrl' => '/home',
        'guard' => 'web',
        'expiration' => 120,
    ],
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/dashboard',
            expiration: now()->addMinutes(2000)
        ),
        'redirectUrl' => '/dashboard',
        'guard' => 'web',
        'expiration' => 2000,
    ],
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/home',
            guard: 'admin'
        ),
        'redirectUrl' => '/home',
        'guard' => 'admin',
        'expiration' => 120,
    ],
    [
        'user' => fn () => Admin::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/home'
        ),
        'redirectUrl' => '/home',
        'guard' => 'web',
        'expiration' => 120,
    ],
]);

it('can send valid uri', function (Authenticatable&Model $user, $generator, $redirectUrl, $guard, $expiration) {
    testTime()->freeze();

    Notification::fake();

    $uri = $generator($user);

    $query = parse_url($uri, PHP_URL_QUERY);
    parse_str($query, $query);

    $data = AuthData::fromString($query['data'])->toArray();

    expect((int) $query['expires'])->toBe(
        now()->addMinutes($expiration)->timestamp
    );

    $model = MagicLogin::first();

    expect($model)
        ->uuid->toBe($data['uuid'])
        ->authenticatable_id->toBe($user->getKey())
        ->authenticatable_type->toBe($user->getMorphClass())
        ->guard->toBe($guard)
        ->redirect_url->toBe($redirectUrl);

    expect($model->expires_at->timestamp)
        ->toBe(now()->addMinutes($expiration)->timestamp);

    Notification::assertSentTo(
        [$user], MagicLinkNotification::class
    );
})->with([
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::send(
            authenticatable: $user,
            redirectUrl: '/home'
        ),
        'redirectUrl' => '/home',
        'guard' => 'web',
        'expiration' => 120,
    ],
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::send(
            authenticatable: $user,
            redirectUrl: '/dashboard',
            expiration: now()->addMinutes(2000)
        ),
        'redirectUrl' => '/dashboard',
        'guard' => 'web',
        'expiration' => 2000,
    ],
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::send(
            authenticatable: $user,
            redirectUrl: '/home',
            guard: 'admin'
        ),
        'redirectUrl' => '/home',
        'guard' => 'admin',
        'expiration' => 120,
    ],
    [
        'user' => fn () => Admin::factory()->create(),
        'generator' => fn ($user) => MagicLink::send(
            authenticatable: $user,
            redirectUrl: '/home'
        ),
        'redirectUrl' => '/home',
        'guard' => 'web',
        'expiration' => 120,
    ],
]);

it('can authenticate user', function (Authenticatable&Model $user, $generator, $text, $status) {
    $uri = $generator($user);

    followingRedirects()
        ->assertGuest()
        ->getJson($uri)
        ->assertStatus($status)
        ->assertSeeText($text);

    assertAuthenticatedAs($user);

    auth()->logout();

    assertGuest();
})->with([
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/home',
        ),
        'text' => 'home',
        'status' => 200,
    ],
    [
        'user' => fn () => Admin::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/dashboard',
        ),
        'text' => 'dashboard',
        'status' => 200,
    ],
]);

it('can invalidate the old uri and regenerate a new one', function (Authenticatable&Model $user, $generator, $text, $status) {
    $uri1 = $generator($user);

    followingRedirects()
        ->assertGuest()
        ->getJson($uri1)
        ->assertStatus($status)
        ->assertSeeText($text);

    auth()->logout();

    $uri2 = $generator($user);

    followingRedirects()
        ->assertGuest()
        ->getJson($uri2)
        ->assertStatus($status)
        ->assertSeeText($text);

    auth()->logout();

    followingRedirects()
        ->assertGuest()
        ->getJson($uri1)
        ->assertStatus(403);

    auth()->logout();

    followingRedirects()
        ->assertGuest()
        ->getJson($uri2)
        ->assertStatus($status)
        ->assertSeeText($text);
})->with([
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/home',
        ),
        'text' => 'home',
        'status' => 200,
    ],
]);

it('can force single uri for single user', function (Authenticatable&Model $user, $generator, $text, $status) {
    config()->set('magic-login.force_single', false);

    $uri1 = $generator($user);

    followingRedirects()
        ->assertGuest()
        ->getJson($uri1)
        ->assertStatus($status)
        ->assertSeeText($text);

    auth()->logout();

    $uri2 = $generator($user);

    followingRedirects()
        ->assertGuest()
        ->getJson($uri2)
        ->assertStatus($status)
        ->assertSeeText($text);

    auth()->logout();

    followingRedirects()
        ->assertGuest()
        ->getJson($uri1)
        ->assertStatus($status);

    auth()->logout();

    followingRedirects()
        ->assertGuest()
        ->getJson($uri2)
        ->assertStatus($status)
        ->assertSeeText($text);
})->with([
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/home',
        ),
        'text' => 'home',
        'status' => 200,
    ],
]);

it('can fail with invalid signature', function (Authenticatable&Model $user, $generator, $text, $status) {
    testTime()->freeze();

    $uri = $generator($user);

    followingRedirects()
        ->assertGuest()
        ->getJson($uri)
        ->assertStatus($status)
        ->assertSeeText($text);

    assertGuest();
})->with([
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/home',
            expiration: now()->subMinutes(2000)
        ),
        'text' => 'Invalid signature.',
        'status' => 403,
    ],
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => MagicLink::make(
            authenticatable: $user,
            redirectUrl: '/home',
        ).'tampered',
        'text' => 'Invalid signature.',
        'status' => 403,
    ],
    [
        'user' => fn () => User::factory()->create(),
        'generator' => fn ($user) => '/magic-login',
        'text' => 'Invalid signature.',
        'status' => 403,
    ],
]);

it('can not authenticate a non-existent user', function () {
    $user = User::factory()->create();

    $uri = MagicLink::make(
        authenticatable: $user,
        redirectUrl: '/home'
    );

    followingRedirects()
        ->assertGuest()
        ->getJson($uri)
        ->assertStatus(200)
        ->assertSeeText('home');

    auth()->logout();

    $user->delete();

    followingRedirects()
        ->assertGuest()
        ->getJson($uri)
        ->assertStatus(403)
        ->assertSeeText('Invalid signature.');
});

it('can change invalid signature http status code', function () {
    config()->set('magic-login.exception', InvalidSignatureTestException::class);

    $user = User::factory()->create();

    $uri = MagicLink::make(
        authenticatable: $user,
        redirectUrl: '/home'
    );

    $user->delete();

    followingRedirects()
        ->assertGuest()
        ->getJson($uri)
        ->assertStatus(418)
        ->assertSeeText('Invalid signature (418).');
});

it('can work with logins limit', function ($limit) {
    config()->set('magic-login.logins_limit', $limit);

    $user = User::factory()->create();

    $uri = MagicLink::make(
        authenticatable: $user,
        redirectUrl: '/home',
    );

    for ($i = 0; $i < $limit; $i++) {
        followingRedirects()
            ->assertGuest()
            ->getJson($uri)
            ->assertStatus(200)
            ->assertSeeText('home');

        auth()->logout();
    }

    followingRedirects()
        ->assertGuest()
        ->getJson($uri)
        ->assertStatus(403)
        ->assertSeeText('Invalid signature.');
})->with([1, 2, 3, 4, 5, 10, 20]);

it('can revoke all uri for single user', function ($force, $count) {
    config()->set('magic-login.force_single', $force);

    $user = User::factory()->create();

    MagicLink::make(
        authenticatable: $user,
        redirectUrl: '/home',
    );

    MagicLink::make(
        authenticatable: $user,
        redirectUrl: '/home',
    );

    expect(MagicLogin::count())->toBe($count);

    MagicLink::revokeAll($user);

    expect(MagicLogin::count())->toBe(0);
})->with([
    ['force' => true, 'count' => 1],
    ['force' => false, 'count' => 2],
]);

it('can store metadata', function ($metadata, $count) {
    $user = User::factory()->create();

    MagicLink::make(
        authenticatable: $user,
        redirectUrl: '/home',
        metadata: $metadata,
    );

    expect(
        MagicLogin::query()->where('metadata->test', true)->count()
    )->toBe($count);
})->with([
    ['metadata' => ['test' => true], 'count' => 1],
    ['metadata' => ['test' => false], 'count' => 0],
]);

it('can fail with guest user', function ($route) {
    assertGuest()
        ->getJson($route)
        ->assertStatus(401);
})->with([
    'home',
    'dashboard',
]);
