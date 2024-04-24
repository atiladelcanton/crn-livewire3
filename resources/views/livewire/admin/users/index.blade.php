<div>
    <x-header title="Users" separator/>

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
