<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'dob',
        'nid',
        'passport_id',
        'birth_id',
        'active',
        'role',
        'password',
        'meta',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'role' => UserRole::class,
        ];
    }

    /**
     * Helper method to check multiple roles using Enums.
     */
    public function hasRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function sentTransactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'sender');
    }

    public function receivedTransactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'receiver');
    }

    public function addBalance(float $amount): self
    {
        $this->balance += $amount;
        $this->save();

        return $this;
    }

    public function subtractBalance(float $amount): self
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $this->balance -= $amount;
        $this->save();

        return $this;
    }
}
