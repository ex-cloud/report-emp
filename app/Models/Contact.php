<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Blameable;
use App\Traits\HasOwnedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use SoftDeletes;
    use HasUuid;
    use Blameable;
    use HasOwnedScope;

    protected $table = 'contacts';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        
        'company_id',
        'user_id',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')
            ->withDefault([
                'name' => 'N/A',
            ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
