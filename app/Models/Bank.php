<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'owner_id',
        'manager_id',
        'bank_name',
        'bank_branch',
        'bank_type',
        'account_number',
        'balance',
        'is_active',
        'visible_to',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'manager_id' => 'array',
        'meta' => 'array',
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Bank type constants
     */
    const TYPE_MFS = 'MFS';
    const TYPE_BANK = 'BANK';
    const TYPE_AGENT_BANK = 'AGENT_BANK';

    /**
     * Visibility constants
     */
    const VISIBLE_ADMIN = 'ADMIN';
    const VISIBLE_MANAGER = 'MANAGER';
    const VISIBLE_VOLUNTEER = 'VOLUNTEER';
    const VISIBLE_MEMBER = 'MEMBER';
    const VISIBLE_NONE = 'NONE';

    /**
     * Get all available bank types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_MFS,
            self::TYPE_BANK,
            self::TYPE_AGENT_BANK,
        ];
    }

    /**
     * Get all visibility options
     */
    public static function getVisibilityOptions(): array
    {
        return [
            self::VISIBLE_ADMIN,
            self::VISIBLE_MANAGER,
            self::VISIBLE_VOLUNTEER,
            self::VISIBLE_MEMBER,
            self::VISIBLE_NONE,
        ];
    }

    /**
     * Get visibility label
     */
    public function getVisibilityLabel(): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $this->visible_to)));
    }

    /**
     * Get bank type label
     */
    public function getTypeLabel(): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $this->bank_type)));
    }

    /**
     * Get full bank name with branch
     */
    public function getFullNameAttribute(): string
    {
        return $this->bank_branch 
            ? "{$this->bank_name} - {$this->bank_branch}"
            : $this->bank_name;
    }

    /**
     * Get formatted balance
     */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 2);
    }

    /**
     * Check if bank is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if user can view this bank
     */
    public function isVisibleTo(string $role): bool
    {
        if ($this->visible_to === self::VISIBLE_NONE) {
            return false;
        }

        if ($this->visible_to === self::VISIBLE_ADMIN && $role === self::VISIBLE_ADMIN) {
            return true;
        }

        if ($this->visible_to === self::VISIBLE_MANAGER && $role === self::VISIBLE_MANAGER) {
            return true;
        }

        if ($this->visible_to === self::VISIBLE_VOLUNTEER && $role === self::VISIBLE_VOLUNTEER) {
            return true;
        }

        if ($this->visible_to === self::VISIBLE_MEMBER && $role === self::VISIBLE_MEMBER) {
            return true;
        }

        return false;
    }

    /**
     * Check if user is manager of this bank
     */
    public function isManager(int $userId): bool
    {
        if (empty($this->manager_id)) {
            return false;
        }

        return in_array($userId, $this->manager_id);
    }

    /**
     * Check if user is owner of this bank
     */
    public function isOwner(int $userId): bool
    {
        return $this->owner_id === $userId;
    }

    /**
     * Check if user can manage this bank
     */
    public function canManage(int $userId): bool
    {
        return $this->isOwner($userId) || $this->isManager($userId);
    }

    /**
     * Add manager to bank
     */
    public function addManager(int $userId): bool
    {
        $managers = $this->manager_id ?? [];
        
        if (in_array($userId, $managers)) {
            return false;
        }

        $managers[] = $userId;
        $this->manager_id = $managers;
        
        return $this->save();
    }

    /**
     * Remove manager from bank
     */
    public function removeManager(int $userId): bool
    {
        $managers = $this->manager_id ?? [];
        
        if (!in_array($userId, $managers)) {
            return false;
        }

        $managers = array_filter($managers, function ($id) use ($userId) {
            return $id !== $userId;
        });

        $this->manager_id = array_values($managers);
        
        return $this->save();
    }

    /**
     * Update bank balance
     */
    public function updateBalance(float $amount, string $operation = 'add'): bool
    {
        if ($operation === 'add') {
            $this->balance += $amount;
        } elseif ($operation === 'subtract') {
            if ($this->balance < $amount) {
                return false;
            }
            $this->balance -= $amount;
        } else {
            $this->balance = $amount;
        }

        return $this->save();
    }

    /**
     * Scope a query to only include active banks
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by bank type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('bank_type', $type);
    }

    /**
     * Scope a query to filter by visibility
     */
    public function scopeVisibleTo($query, string $role)
    {
        if ($role === self::VISIBLE_ADMIN) {
            return $query->whereIn('visible_to', [
                self::VISIBLE_ADMIN,
                self::VISIBLE_MANAGER,
                self::VISIBLE_VOLUNTEER,
                self::VISIBLE_MEMBER,
            ]);
        }

        if ($role === self::VISIBLE_MANAGER) {
            return $query->whereIn('visible_to', [
                self::VISIBLE_MANAGER,
                self::VISIBLE_VOLUNTEER,
                self::VISIBLE_MEMBER,
            ]);
        }

        if ($role === self::VISIBLE_VOLUNTEER) {
            return $query->whereIn('visible_to', [
                self::VISIBLE_VOLUNTEER,
                self::VISIBLE_MEMBER,
            ]);
        }

        if ($role === self::VISIBLE_MEMBER) {
            return $query->where('visible_to', self::VISIBLE_MEMBER);
        }

        return $query->where('visible_to', self::VISIBLE_NONE);
    }

    /**
     * Scope a query to filter by owner
     */
    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('owner_id', $userId);
    }

    /**
     * Scope a query to filter by manager
     */
    public function scopeManagedBy($query, int $userId)
    {
        return $query->whereJsonContains('manager_id', $userId);
    }

    /**
     * Scope a query to filter by user (owner or manager)
     */
    public function scopeAccessibleBy($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('owner_id', $userId)
              ->orWhereJsonContains('manager_id', $userId);
        });
    }

    /**
     * Relationships
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'bank_manager', 'bank_id', 'user_id');
    }

    // If you need a pivot table for managers instead of JSON field
    // Uncomment the above and create a bank_manager pivot table
    
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Get manager details
     */
    public function getManagerDetails()
    {
        if (empty($this->manager_id)) {
            return collect();
        }

        return User::whereIn('id', $this->manager_id)->get();
    }

    /**
     * Get manager names as string
     */
    public function getManagerNamesAttribute(): string
    {
        if (empty($this->manager_id)) {
            return 'No managers';
        }

        $managers = User::whereIn('id', $this->manager_id)
            ->pluck('name')
            ->toArray();

        return implode(', ', $managers);
    }

    /**
     * Get owner name
     */
    public function getOwnerNameAttribute(): string
    {
        return $this->owner->name ?? 'Unknown';
    }

    /**
     * Check if balance is sufficient
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}