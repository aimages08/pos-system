<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
    ];

    // ---------- Relations ----------

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    // ---------- Helpers ----------

    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions->contains('name', $permissionName);
    }

    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }

    public function usersCount(): int
    {
        return $this->users()->count();
    }
}