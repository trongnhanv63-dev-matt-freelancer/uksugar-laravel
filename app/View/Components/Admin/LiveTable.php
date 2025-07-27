<?php

namespace App\View\Components\Admin;

use App\Enums\Status as BaseStatus;
use App\Enums\UserStatus;
use App\Services\UserService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class LiveTable extends Component
{
    public array $columns = [];
    public array $filters = [];

    private const CONFIGURATIONS = [
        'users' => 'configureForUsers',
        'roles' => 'configureForRoles',
        'permissions' => 'configureForPermissions',
    ];

    /**
     * Create a new component instance.
     */
    public function __construct(
        protected UserService $userService,
        public string $type,
        public ?string $title = null,
        public ?string $description = null,
        public array $initialData = [],
        public ?string $apiUrl = null,
        public ?string $createUrl = null,
        public ?string $createPermission = null,
        public ?string $editUrlTemplate = '',
        public ?string $stateKey = 'liveTableState',
    ) {
        // This dynamically calls the correct configuration method based on the type.
        $configMethod = self::CONFIGURATIONS[$this->type] ?? null;

        if ($configMethod && method_exists($this, $configMethod)) {
            $this->$configMethod();
        }
    }

    /**
     * Configure the columns and filters for the Users table.
     */
    private function configureForUsers(): void
    {
        $this->columns = [
            ['label' => 'User', 'key' => 'name', 'sortable' => true],
            ['label' => 'Roles', 'key' => 'roles', 'sortable' => false],
            ['label' => 'Status', 'key' => 'status', 'sortable' => false],
            ['label' => 'Last Login', 'key' => 'last_login_at', 'sortable' => true],
        ];

        $roles = $this->userService->getRolesForForm();
        $this->filters = [
            ['type' => 'search', 'key' => 'search', 'placeholder' => 'Search by name or email...'],
            ['type' => 'select', 'key' => 'role', 'id' => 'role-filter-select', 'placeholder' => 'All Roles', 'options' => $roles->map(fn ($r) => ['value' => $r->name, 'text' => $r->name])->all()],
            [
                'type' => 'select', 'key' => 'status', 'id' => 'status-filter-select', 'placeholder' => 'All Statuses',
                'options' => collect(UserStatus::cases())->map(fn ($s) => ['value' => $s->value, 'text' => Str::headline($s->name)])->all(),
            ],
        ];
    }

    /**
     * Configure the columns and filters for the Roles table.
     */
    private function configureForRoles(): void
    {
        $this->columns = [
            ['label' => 'Role Name', 'key' => 'name', 'sortable' => true],
            ['label' => 'Permissions', 'key' => 'permissions_count', 'sortable' => false],
            ['label' => 'Status', 'key' => 'status', 'sortable' => false],
            ['label' => 'Created', 'key' => 'created_at', 'sortable' => true],
        ];

        $this->filters = [
            ['type' => 'search', 'key' => 'search', 'placeholder' => 'Search by role name...'],
            [
                'type' => 'select', 'key' => 'status', 'id' => 'status-filter-select', 'placeholder' => 'All Statuses',
                'options' => collect(BaseStatus::cases())->map(fn ($s) => ['value' => $s->value, 'text' => Str::headline($s->name)])->all(),
            ],
        ];
    }

    /**
     * Configure the columns and filters for the Permissions table.
     */
    private function configureForPermissions(): void
    {
        $this->columns = [
            ['label' => 'Permission Name', 'key' => 'name', 'sortable' => true],
            ['label' => 'Description', 'key' => 'description', 'sortable' => false],
            ['label' => 'Status', 'key' => 'status', 'sortable' => false],
            ['label' => 'Created', 'key' => 'created_at', 'sortable' => true],
        ];

        $this->filters = [
            ['type' => 'search', 'key' => 'search', 'placeholder' => 'Search by name or description...'],
            [
                'type' => 'select', 'key' => 'status', 'id' => 'status-filter-select', 'placeholder' => 'All Statuses',
                'options' => collect(BaseStatus::cases())->map(fn ($s) => ['value' => $s->value, 'text' => Str::headline($s->name)])->all(),
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.live-table');
    }
}
