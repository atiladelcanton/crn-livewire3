<?php

namespace App\Livewire\Admin\Users;

use App\Enum\Can;
use App\Models\{Permission, User};
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read LengthAwarePaginator|User[] $users
 * @property-read array $headers;
 */
class Index extends Component
{
    public ?string $search = null;

    public array $search_permissions = [];

    public Collection $permissionsToSearch;

    public string $sortDirection = 'asc';

    public string $sortColumnBy = 'id';

    public bool $search_trash = false;
    public function mount()
    {
        $this->authorize(Can::BE_AN_ADMIN->value);
        $this->filterPermissions();
    }

    public function render()
    {
        return view('livewire.admin.users.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $this->validate(['search_permissions' => 'exists:permissions,id']);

        return User::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(DB::raw('lower(name)'), 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->when(
                $this->search_permissions,
                fn (Builder $q) => $q->whereHas('permissions', function (Builder $q) {
                    $q->whereIn('id', $this->search_permissions);
                })
            )
            ->when($this->search_trash, fn (Builder $q) => $q->onlyTrashed())
            ->orderBy($this->sortColumnBy, $this->sortDirection)
            ->paginate();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'permissions', 'label' => 'Permissions'],
        ];
    }

    public function filterPermissions(?string $search = null): void
    {
        $this->permissionsToSearch = Permission::query()
            ->when($search, function (Builder $q) use ($search) {
                $q->where('key', 'like', "%{$search}%");
            })
            ->orderBy('key')
            ->get();
    }
}
