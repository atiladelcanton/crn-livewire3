<div>
    <x-header title="Users" separator/>
    <div class="flex space-x-4 mb-5">
        <div class="w-1/3">
            <x-input icon="o-magnifying-glass-circle"
                     class="input-sm"
                     placeholder="Search by email and name"
                     wire:model.live="search"
            />
        </div>
        <x-choices
                class="select-sm"
                placeholder="Select an permission"

        />
    </div>
    <x-card>
        <x-table :headers="$this->headers" :rows="$this->users" with-pagination>
            @scope('cell_permissions',$user)
                @foreach($user->permissions as $permission)
                    <x-badge :value="$permission->key" class="badge-primary" />
                @endforeach
            @endscope
            @scope('actions', $user)
                <x-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm" />
            @endscope
        </x-table>
    </x-card>
</div>
