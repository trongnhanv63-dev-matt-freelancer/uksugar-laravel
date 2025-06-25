# Gói Phân quyền (RBAC) cho Laravel

Một package linh hoạt, "headless" (không bao gồm view), và có khả năng tùy biến cao để xây dựng hệ thống Phân quyền Dựa trên Vai trò (Role-Based Access Control) cho các ứng dụng Laravel hiện đại.

## Tính năng chính

-   Quản lý Users, Roles, và Permissions.
-   Hệ thống phân quyền mạnh mẽ dựa trên Laravel Gate.
-   Quyền lực tuyệt đối cho Super Admin, độc lập với cơ sở dữ liệu.
-   Hỗ trợ cơ chế Active/Inactive cho Roles và Permissions.
-   Kiến trúc V-C-S-R-M (Service, Repository) cho logic backend.
-   Hoàn toàn "headless": không cung cấp View, cho phép dự án tự do xây dựng giao diện.
-   Có khả năng tùy biến (override) cao thông qua file config.

## Yêu cầu

-   PHP >= 8.2
-   Laravel Framework >= 11.x

## Hướng dẫn Cài đặt và Cấu hình

Đây là các bước để tích hợp package này vào một dự án Laravel mới.

### Bước 1: Cài đặt qua Composer

Tại thư mục gốc của dự án Laravel, chạy lệnh sau:

```bash
# Thay thế "nhandev/rbac" bằng tên package của bạn trên Packagist
# Nếu đang phát triển local, bạn cần thêm vào composer.json của dự án chính trước
composer require nhandev/rbac
```

### Bước 2: Chạy lệnh cài đặt của Package

Package cung cấp một lệnh Artisan tiện lợi để tự động hóa việc thiết lập.

```bash
php artisan rbac:make-seeders
```

Lệnh này sẽ thực hiện các hành động sau:
-   **Publish file cấu hình:** Sao chép file `rbac.php` vào thư mục `config` của dự án, cho phép bạn tùy chỉnh.
-   **Tạo các file Seeder:** Tự động tạo các file `PermissionSeeder.php`, `RoleSeeder.php`, và `SuperAdminSeeder.php` trong thư mục `database/seeders` của dự án với namespace chính xác.

### Bước 3: Cập nhật Migration của `users`

Package này cần 1 cột `status` trên bảng `users` của bạn. Hãy đảm bảo file migration `..._create_users_table.php` của dự án có chứa cột này.

*Đường dẫn: `database/migrations/YYYY_MM_DD_HHMMSS_create_users_table.php`*
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('username')->unique();
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    
    // --- COLUMN REQUIRED BY THE RBAC PACKAGE ---
    $table->string('status', 30)->default('active');
    
    $table->rememberToken();
    $table->timestamps();
});
```

### Bước 4: Cập nhật `User` Model

Để `User` model của bạn có các phương thức phân quyền, hãy `use` trait `HasRolesAndPermissions` được cung cấp bởi package.

*Đường dẫn: `app/Models/User.php`*
```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use NhanDev\Rbac\Models\Traits\HasRolesAndPermissions; // 1. Import the trait

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRolesAndPermissions; // 2. Use the trait

    // ... (các thuộc tính $fillable, $hidden, $casts của bạn)
    // Hãy đảm bảo 'status' có trong mảng $fillable của bạn.
}
```

### Bước 5: Chạy Migration và Seeder

1.  **Chạy Migration:** Lệnh này sẽ tạo các bảng `users` (từ dự án của bạn) và các bảng `roles`, `permissions`, `user_roles`, `role_permissions` (từ package).
    ```bash
    php artisan migrate
    ```
2.  **Cập nhật `DatabaseSeeder.php`:** Mở file `database/seeders/DatabaseSeeder.php` và gọi các seeder mà package đã tạo ra.
    ```php
    <?php
    namespace Database\Seeders;
    
    use Illuminate\Database\Seeder;
    
    class DatabaseSeeder extends Seeder
    {
        public function run(): void
        {
            $this->call([
                PermissionSeeder::class,
                RoleSeeder::class,
                SuperAdminSeeder::class,
            ]);
        }
    }
    ```
3.  **Chạy Seeder:** Lệnh này sẽ tạo các permissions, role `super-admin`, và tài khoản Super Admin mặc định.
    ```bash
    php artisan db:seed
    ```

**Cài đặt hoàn tất!** Bạn đã có một tài khoản `superadmin@example.com` (mật khẩu: `password`) để bắt đầu xây dựng trang quản trị.

## Hướng dẫn Sử dụng

Sau khi cài đặt, bạn có thể sử dụng các phương thức và tính năng sau trong ứng dụng của mình.

### Kiểm tra Vai trò (Checking Roles)

```php
$user = Auth::user();

// Kiểm tra có phải là Super Admin không (rất nhanh)
if ($user->is_super_admin) {
    //
}

// Kiểm tra một vai trò cụ thể
if ($user->hasRole('editor')) {
    //
}
```

### Kiểm tra Quyền hạn (Checking Permissions)

Đây là cách chính để phân quyền trong ứng dụng.

**Trong Controller / Service (PHP):**
```php
use Illuminate\Support\Facades\Gate;

// Dùng Gate facade
if (Gate::allows('posts.edit')) {
    //
}

// Hoặc dùng trực tiếp trên User model
if ($user->can('posts.edit')) {
    //
}

// Ngăn chặn truy cập nếu không có quyền
$this->authorize('posts.edit');
```

**Trong file Route (`web.php` hoặc `api.php`):**
```php
Route::put('/posts/{post}', /* ... */)->middleware('can:posts.edit');
```

**Trong file Blade (`.blade.php`):**
```blade
@can('posts.edit')
    <a href="...">Edit Post</a>
@endcan

@cannot('posts.delete')
    <p>You cannot delete this post.</p>
@endcannot
```

### Gán và Đồng bộ Vai trò

Bạn có thể dễ dàng quản lý vai trò của một người dùng.

```php
use App\Models\User;
use NhanDev\Rbac\Models\Role;

$user = User::find(1);
$editorRole = Role::where('name', 'editor')->first();

// Gán một vai trò mới (không xóa các vai trò cũ)
$user->roles()->attach($editorRole);

// Xóa một vai trò
$user->roles()->detach($editorRole);

// Đồng bộ hóa: chỉ giữ lại các vai trò trong danh sách
$user->roles()->sync([$editorRole->id]); 
```

## Tùy chỉnh (Customization)

Để tùy chỉnh package, hãy chạy lệnh `php artisan vendor:publish --tag="rbac-config"` và sửa file `config/rbac.php`. Bạn có thể:
-   Thay đổi User model mặc định.
-   Ghi đè (override) các lớp Model, Repository bằng implementation của riêng bạn.
-   Tùy chỉnh các giá trị `status`.

## Dự tính phát triển thêm
Bước 4: Thêm các Idea nâng cao (Yêu cầu 5)
Để package của bạn thực sự chuyên nghiệp, hãy xem xét thêm:

Events: Bắn ra các sự kiện như RoleAssignedToUser, PermissionRevokedFromRole... để dự án có thể lắng nghe và thực hiện các hành động tùy chỉnh.
Commands: Tạo các lệnh Artisan như php artisan rbac:create-role editor để quản lý quyền hạn qua dòng lệnh.
Blade Directives: Tạo các chỉ thị Blade riêng như @role('editor') và @permission('posts.create') để kiểm tra quyền trực tiếp trong view một cách gọn gàng.
Testing: Viết các bộ test (unit và feature tests) cho package của bạn để đảm bảo nó hoạt động ổn định.


## License

The MIT License (MIT).