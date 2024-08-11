<?php

namespace App\Models;

use App\Enums\Role;
use App\Enums\ShelterRole;
use App\Models\Casts\Hashed;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\RefreshToken;

class User extends Authenticatable implements LaratrustUser, HasLocalePreference
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRolesAndPermissions,
        TwoFactorAuthenticatable,
        SoftDeletes;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'locale',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'last_active_at',
    ];

    protected $casts = [
        'password' => Hashed::class,
        'two_factor_confirmed_at' => 'datetime',
        'shelter_id' => 'integer',
        'last_active_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function shelter() : BelongsTo
    {
        return $this->belongsTo(Shelter::class);
    }

    public function getRole() : Role
    {
        return Role::from($this->getRoles()[0]);
    }

    public function getShelterRole() : ?ShelterRole
    {
        if ($this->shelter_id === null) {
            return null;
        }

        $role = $this->getRoles($this->shelter_id)[0] ?? null;

        return $role ? ShelterRole::from($role) : null;
    }

    public function revokeTokens() : void
    {
        RefreshToken::query()
            ->whereIn('access_token_id', $this->tokens()->select('id')->where('revoked', false))
            ->update(['revoked' => true]);

        $this->tokens()
            ->where('revoked', false)
            ->update(['revoked' => true]);
    }

    public function preferredLocale() : string
    {
        return in_array($this->locale, config('app.supported_locales')) ? $this->locale : config('app.fallback_locale');
    }
}
