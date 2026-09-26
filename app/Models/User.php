<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ---------- Relations ----------

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // ---------- Helpers ----------

    /**
     * Check if user has a permission.
     * Example: $user->hasPermission('products.view')
     */
    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role) {
            return false;
        }

        // Admin role has everything
        if ($this->role->isAdmin()) {
            return true;
        }

        return $this->role->hasPermission($permissionName);
    }

    /**
     * Check by role name — kept for backwards-compat with old code.
     * Example: $user->hasRole('admin')
     */
    public function hasRole(string ...$roles): bool
    {
        if (!$this->role) {
            return false;
        }

        return in_array($this->role->name, $roles);
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isCashier(): bool
    {
        return $this->role && $this->role->name === 'cashier';
    }

    public function isManager(): bool
    {
        return $this->role && $this->role->name === 'manager';
    }
}