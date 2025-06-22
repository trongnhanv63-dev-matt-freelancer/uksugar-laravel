# Hướng dẫn Cài đặt & Cấu hình PHP CS Fixer với Docker & VS Code

Tài liệu này hướng dẫn chi tiết cách thiết lập một môi trường phát triển PHP chuyên nghiệp, tự động định dạng mã nguồn theo tiêu chuẩn PSR-12 bằng cách sử dụng `PHP CS Fixer` trong một môi trường Docker, với VS Code là trình soạn thảo chính.

Giải pháp cuối cùng và đã được xác nhận hoạt động là sử dụng extension **Dev Containers** của Microsoft để có một trải nghiệm mượt mà và ổn định nhất.

## Yêu cầu

-   Docker & Docker Compose đã được cài đặt và đang hoạt động.
-   Dự án Laravel đang chạy trong một container Docker.
-   Visual Studio Code (VS Code) đã được cài đặt trên máy vật lý (host).

## Cấu trúc thư mục

Để giữ cho thư mục gốc của dự án gọn gàng, các file cấu hình sẽ được đặt trong thư mục `.php-cs-fixer`.

```
your-project/
├── .php-cs-fixer/
│   └── .php-cs-fixer.dist.php  # File chứa các quy tắc định dạng
├── .vscode/
│   └── settings.json           # Cài đặt cho VS Code của dự án
└── ... (các thư mục khác của Laravel như app, config, routes)
```

## Các bước thực hiện

### Bước 1: Cài đặt `php-cs-fixer` vào Dự án

Lệnh này cần được thực thi **bên trong** container PHP của bạn.

1.  Mở terminal trên máy host.
2.  Xác định tên container đang chạy PHP bằng lệnh `docker ps`. Ví dụ, tên container là `my-project-app-1`.
3.  Chạy lệnh `docker exec` để thực thi Composer bên trong container:

    ```bash
    # Thay thế 'my-project-app-1' bằng tên container thực tế của bạn
    docker exec my-project-app-1 composer require friendsofphp/php-cs-fixer --dev
    ```

### Bước 2: Tạo File Cấu hình Quy tắc

Tạo file tại đường dẫn `.php-cs-fixer/.php-cs-fixer.dist.php` với nội dung sau:

```php
<?php
// Configuration file for PHP CS Fixer

$finder = PhpCsFixer\Finder::create()
    ->in([
        // Use `__DIR__ . '/..'` to point back to the project root
        __DIR__ . '/../app',
        __DIR__ . '/../config',
        __DIR__ . '/../database/factories',
        __DIR__ . '/../database/seeders',
        __DIR__ . '/../routes',
        __DIR__ . '/../tests',
    ]);

$config = new PhpCsFixer\Config();
return $config->setRules([
        // Use the PSR-12 standard as a base ruleset
        '@PSR12' => true,
        // Enforce short array syntax: [] instead of array()
        'array_syntax' => ['syntax' => 'short'],
        // Imports should be ordered alphabetically
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        // Automatically remove unused `use` statements
        'no_unused_imports' => true,
        // Enforce a single blank line at the end of files
        'single_blank_line_at_eof' => true,
        // Add a trailing comma in multiline arrays for cleaner git diffs
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        // Standardize spacing around binary operators
        'binary_operator_spaces' => ['default' => 'single_space'],
        // Enforce the use of single quotes for simple strings.
        // The rule name for recent versions is 'single_quote'.
        'single_quote' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    // Point cache file to the correct directory
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
```

### Bước 3: Thiết lập môi trường Dev Containers

Đây là bước quan trọng nhất để đảm bảo VS Code và các công cụ hoạt động trơn tru.

1.  **Cài đặt Extension:** Trong VS Code, tìm và cài đặt extension **`Dev Containers`** của **Microsoft**.
2.  **Kết nối vào Container:**
    -   Mở **Command Palette** (`Ctrl+Shift+P` hoặc `Cmd+Shift+P`).
    -   Chọn lệnh `Dev Containers: Attach to Running Container...`.
    -   Chọn container Laravel của bạn từ danh sách.
    -   Một cửa sổ VS Code mới sẽ mở ra, kết nối trực tiếp vào container.
3.  **Mở Thư mục Dự án:** Trong cửa sổ mới, chọn `File > Open Folder...` và trỏ đến thư mục gốc của dự án bên trong container (thường là `/var/www/html`).
4.  **Cài Extension "In Container":**
    -   Vào mục Extensions.
    -   Tìm `php-cs-fixer` của `junstyle` và nhấn nút **"Install in Dev Container..."**.
    -   Nên làm tương tự cho `PHP Intelephense` để có trải nghiệm code tốt nhất.

### Bước 4: Cấu hình VS Code (`settings.json`)

Đây là cấu hình cuối cùng đã được xác nhận là hoạt động. Tạo hoặc cập nhật file `.vscode/settings.json` với nội dung sau:

```json
{
    // These settings apply specifically to PHP files
    "[php]": {
        // Set 'junstyle.php-cs-fixer' as the default tool to format PHP code
        "editor.defaultFormatter": "junstyle.php-cs-fixer",
        // Automatically run the formatter every time a PHP file is saved
        "editor.formatOnSave": true
    },
    
    // --- PHP CS Fixer Extension Specific Settings ---

    // Tell the extension exactly where to find our rules configuration file.
    // This path is relative to the workspace root inside the container.
    "php-cs-fixer.config": ".php-cs-fixer/.php-cs-fixer.dist.php",

    // Explicitly tell the extension where the executable is located inside the container.
    // This was found to be necessary in this specific setup.
    "php-cs-fixer.executablePath": "vendor/bin/php-cs-fixer"
}
```
**Lưu ý quan trọng:** Theo như bạn đã debug thành công, đường dẫn đến `config` là các đường dẫn **tương đối** so với thư mục gốc của dự án, không cần biến `${workspaceFolder}`.

### Bước 5: Hoàn tất và Kiểm tra

1.  Sau khi đã có đầy đủ các file trên, hãy tải lại cửa sổ VS Code bằng cách mở Command Palette và chọn **`Developer: Reload Window`**.
2.  Mở một file PHP, thay đổi định dạng và nhấn `Ctrl+S` để lưu.
3.  Code sẽ được tự động định dạng theo đúng quy tắc.

---

Tuyệt vời! File hướng dẫn đã hoàn tất.