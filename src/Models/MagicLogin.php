<?php

namespace Maize\MagicLogin\Models;

use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $uuid
 * @property int $authenticatable_id
 * @property string $authenticatable_type
 * @property int $logins
 * @property int|null $logins_limit
 * @property string $guard
 * @property string $redirect_url
 * @property DateTime $expires_at
 * @property array $metadata
 */
class MagicLogin extends Model
{
    use HasFactory;
    use HasUuids;
    use Prunable;

    /** @var string */
    protected $primaryKey = 'uuid';

    /** @var array<int, string> */
    protected $fillable = [
        'authenticatable_id',
        'authenticatable_type',
        'logins',
        'logins_limit',
        'guard',
        'redirect_url',
        'expires_at',
        'metadata',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function authenticatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function prunable(): Builder
    {
        return static::where('expires_at', '<=', now());
    }

    public function login(): self
    {
        if (! $this->isAuthenticable()) {
            throw new Exception;
        }

        $this->loadMissing('authenticatable');

        auth()->guard($this->guard)->login(
            $this->authenticatable
        );

        $this->increment('logins');

        return $this;
    }

    protected function isAuthenticable(): bool
    {
        $this->loadMissing('authenticatable');

        if (is_null($this->authenticatable)) {
            return false;
        }

        if ($this->logins_limit === -1) {
            return true;
        }

        if ($this->logins_limit > $this->logins) {
            return true;
        }

        return false;
    }
}
