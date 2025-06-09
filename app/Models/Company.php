<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Blameable;
use App\Enums\Company\TypeCompany;
use App\Enums\Company\StatusCompany;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @use HasFactory<CompanyFactory>
 * @property string $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $region
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $website
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $youtube
 * @property string|null $custom_link
 * @property string|null $logo
 * @property string|null $notes
 * @property \App\Enums\Company\StatusCompany $status
 * @property \App\Enums\Company\TypeCompany $type
 * @property null|\Carbon\CarbonInterface $created_at
 * @property null|\Carbon\CarbonInterface $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 * @property null|string $deleted_by
 */
class Company extends Model implements HasCurrentTenantLabel
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;
    use Blameable;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'region',
        'country',
        'postal_code',
        'website',
        'instagram',
        'facebook',
        'youtube',
        'custom_link',
        'logo',
        'notes',
        'status',
        'start_date',
        'end_date',
        'type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status' => StatusCompany::class,
        'type' => TypeCompany::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function getRouteKeyName(): string
    {
        return 'id'; // atau 'slug' jika kamu pakai slug
    }

    public function getCurrentTenantLabel(): string
    {
        return 'Active company';
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /** @return HasMany<\App\Models\Contact, self> */
    public function pics(): HasMany
    {
        return $this->hasMany(Contact::class, 'company_id', 'id')
            ->with('user')
            ->withTimestamps();
    }

    public function emps(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'company_employee', 'company_id', 'employee_id')
            ->using(CompanyEmployee::class)
            ->withPivot('created_by', 'updated_by')
            ->withTimestamps();
    }
    
}
