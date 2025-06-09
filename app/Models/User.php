<?php
declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use App\Traits\HasUuid;
use App\Traits\Blameable;
use App\Enums\User\StatusUserEnum;
use Illuminate\Support\Collection;
use Database\Factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HideSuperAdminUnlessCurrent;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @use HasFactory<UserFactory>
 * @property string $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string|null $status
 * @property bool $is_active
 * @property null|\Carbon\Carbon $email_verified_at
 * @property null|string $remember_token
 * @property null|\Carbon\CarbonInterface $created_at
 * @property null|\Carbon\CarbonInterface $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 * @property null|string $deleted_by
 * @property null|string $password_confirmation
 * @property null|string $avatar_url
 * @property null|string $description
 * @property \App\Models\Contact|null $pic
 * @property \App\Models\Employee|null $emp
 * @property \App\Models\Company|null $company
 * @property string|null $companies
 * @property string|null $created_ip
 * @property string|null $updated_ip
 * @property string|null $deleted_ip
 * @property string|null $created_user_agent
 * @property string|null $updated_user_agent
 * @property string|null $deleted_user_agent
 * 
 */
class User extends Authenticatable implements FilamentUser, HasAvatar, HasTenants
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasUuid;
    use SoftDeletes;
    use HideSuperAdminUnlessCurrent;
    use Blameable;

    protected $table = 'users';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'password',
        'description',
        'avatar_url',
        'password_confirmation',
        'status',
        'is_active',

        'created_by',
        'updated_by',
        'deleted_by',
        'created_ip',
        'updated_ip',
        'deleted_ip',
        'created_user_agent',
        'updated_user_agent',
        'deleted_user_agent',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $attributes = [
        'status' => 'pending',
        'is_active' => false,
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => StatusUserEnum::class,
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeSelfEditable(Builder $query): Builder
    {
        return $query->where('id', auth()->id());
    }


    public function scopeAvailableForPICWithRole($query, string|array $roles, ?int $excludeUserId = null)
    {
        $usedUserIds = \App\Models\Contact::query()
            ->when($excludeUserId, fn($q) => $q->where('user_id', '!=', $excludeUserId))
            ->whereNull('deleted_at')
            ->pluck('user_id')
            ->filter()
            ->values();

        return $query
            ->whereHas('roles', fn($q) => $q->whereIn('name', (array) $roles))
            ->whereNotIn('id', $usedUserIds);
    }



    public function canAccessPanel(Panel $panel): bool
    {
        // return $this->hasRole(['super_admin', 'administrator', 'pic']);
        // return true;

        // Super admin bisa akses semua panel
        if ($this->hasAnyRole(['super_admin', 'administrator', 'noc'])) {
            return true;
        }

        return match ($panel->getId()) {
            'client' => $this->hasAnyRole(['noc', 'pic', 'teknisi']),
            default => false,
        };

    }

    public function isActive(): bool
    {
        return $this->is_active; // pastikan kolom is_active ada dan berupa boolean
    }
    public function getFilamentAvatarUrl(): ?string
    {
        $avatar = url('/assets/placeholder.png/');
        return $this->avatar_url ? Storage::url(str("$this->avatar_url")) : $avatar;
    }

    public function pic(): HasOne
    {
        return $this->hasOne(Contact::class, 'user_id', 'id');
    }

    public function emp(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id', 'id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id')
            ->withPivot('created_by', 'updated_by')
            ->withTimestamps();
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->companies()->get();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->companies()->whereKey($tenant)->exists();
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin'); // Spatie Permission
    }
}
