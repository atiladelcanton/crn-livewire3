<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
    public function givePermissionTo(string $key): void
    {
        $this->permissions()->firstOrCreate(compact('key'));
        Cache::forget($this->permissionCacheKey());
        Cache::rememberForever($this->permissionCacheKey(), fn () => $this->permissions);
    }

    public function hasPermissionTo(string $key): bool
    {
        /** @var Collection $permissions */
        $permissions = Cache::get($this->permissionCacheKey(), $this->permissions);

        return $permissions
            ->where('key', $key)
            ->isNotEmpty();
    }

    private function permissionCacheKey(): string
    {
        return "user::{{$this->id}}::permissions";
    }
}
