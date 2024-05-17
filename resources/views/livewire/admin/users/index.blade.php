<div>
    <x-header title="Users" separator/>
    <div class="mb-4 flex space-x-4">
        <div class="w-1/3">
            <x-input icon="o-magnifying-glass-circle"
                     placeholder="Search by email and name"
                     wire:model.live="search"
                     label="Search by email or name"
            />
        </div>
        <x-choices
                wire:model.live="search_permissions"
                :options="$permissionsToSearch"
                search-function="filterPermissions"
                option-label="key"
                searchable

                no-result-text="Nothing here"
                label="Search by permissions"
        />
        <x-checkbox label="Show Deleted Users" wire:model.live="search_trash" class="checkbox-primary" right tight />

    </div>
    <x-card>
        <x-table :headers="$this->headers" :rows="$this->users" with-pagination>

            @scope('header_name' ,$header)
                {{$header['label']}} ↑↓
            @endscope
            @scope('cell_permissions',$user)
                @foreach($user->permissions as $permission)
                    <x-badge :value="$permission->key" class="badge-primary" />
                @endforeach
            @endscope

            @scope('actions', $user)
                @unless($user->trashed())
                    <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm btn-error
                    btn-outline" />
                @else
                <x-button icon="o-arrow-path-rounded-square" wire:click="restore({{ $user->id }})" spinner
                          class="btn-sm btn-success
                btn-outline" />
                @endunless
            @endscope
        </x-table>
    </x-card>
</div>
