<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function givePermissionTo(Can|string $key): void
    {
        $pkey = $key instanceof Can ? $key->value : $key;
        $this->permissions()->firstOrCreate(['key' => $pkey]);
        Cache::forget($this->permissionCacheKey());
        Cache::rememberForever($this->permissionCacheKey(), fn () => $this->permissions);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    private function permissionCacheKey(): string
    {
        return "user::{{$this->id}}::permissions";
    }

    public function hasPermissionTo(Can|string $key): bool
    {
        $pkey = $key instanceof Can ? $key->value : $key;

        /** @var Collection $permissions */
        $permissions = Cache::get($this->permissionCacheKey(), $this->permissions);

        return $permissions->where('key', '=', $pkey)->isNotEmpty();
    }
}
