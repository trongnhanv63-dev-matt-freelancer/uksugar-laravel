# Hướng dẫn Cài đặt & Cấu hình Prettier

Tài liệu này hướng dẫn các bước để cài đặt và cấu hình **Prettier** cho dự án, nhằm mục đích tự động định dạng mã nguồn cho các file frontend (HTML, CSS, JS, JSON, Blade...).

## Yêu cầu

-   Node.js và `npm` (hoặc `yarn`) đã được cài đặt trong môi trường phát triển (khuyến khích dùng môi trường Dev Container).
-   Visual Studio Code (VS Code) đã được cài đặt.

## Các bước thực hiện

### Bước 1: Cài đặt Prettier và Plugin cho Blade

Mở terminal tại thư mục gốc của dự án và chạy lệnh sau để cài đặt Prettier và plugin hỗ trợ định dạng file Blade.

```bash
npm install --save-dev --save-exact prettier prettier-plugin-blade
```

-   `--save-dev`: Cài đặt các gói này như một dependency chỉ dành cho môi trường phát triển.
-   `--save-exact`: Đảm bảo tất cả thành viên trong nhóm sử dụng chính xác cùng một phiên bản Prettier, tránh xung đột về định dạng.

### Bước 2: Cài đặt Extension "Prettier" cho VS Code

1.  Mở VS Code.
2.  Vào mục **Extensions** (Phím tắt: `Ctrl+Shift+X`).
3.  Tìm kiếm và cài đặt extension:
    -   **Tên:** `Prettier - Code formatter`
    -   **Tác giả:** `Prettier`
4.  Nếu bạn đang sử dụng môi trường Dev Container, hãy chắc chắn rằng bạn đã nhấn **"Install in Dev Container..."**.

### Bước 3: Tạo File Cấu hình `.prettierrc.json`

File này định nghĩa các quy tắc định dạng mà Prettier sẽ áp dụng.

1.  Tại thư mục gốc của dự án ( Hoặc tại 1 folder bất kì ), tạo một file mới tên là `.prettierrc.json`.
2.  Dán nội dung cấu hình sau vào file:

    ```json
    {
        "printWidth": 120,
        "tabWidth": 4,
        "useTabs": false,
        "semi": true,
        "singleQuote": true,
        "quoteProps": "as-needed",
        "jsxSingleQuote": false,
        "trailingComma": "es5",
        "bracketSpacing": true,
        "bracketSameLine": false,
        "arrowParens": "always",
        "singleAttributePerLine": true
    }
    ```

### Bước 4: Cấu hình VS Code trong `settings.json`

File này sẽ bảo VS Code sử dụng Prettier cho các loại file phù hợp và kích hoạt tính năng "Format on Save".

1.  Mở hoặc tạo file `.vscode/settings.json` trong thư mục gốc dự án.
2.  Thêm hoặc cập nhật nội dung file như sau:

    ```json
    {
        // Set Prettier as the default formatter for these specific file types
        "[html]": {
            "editor.defaultFormatter": "esbenp.prettier-vscode"
        },
        "[css]": {
            "editor.defaultFormatter": "esbenp.prettier-vscode"
        },
        "[scss]": {
            "editor.defaultFormatter": "esbenp.prettier-vscode"
        },
        "[javascript]": {
            "editor.defaultFormatter": "esbenp.prettier-vscode"
        },
        "[json]": {
            "editor.defaultFormatter": "esbenp.prettier-vscode"
        },
        "[jsonc]": {
            "editor.defaultFormatter": "esbenp.prettier-vscode"
        },
    
        // Enable format on save for all files
        "editor.formatOnSave": true,
    
        // Helps Prettier recognize and process .blade.php files
        "prettier.documentSelectors": [
            "**/*.blade.php"
        ],
        
        // Add this line to specify the new path to your Prettier config file.
        // Please replace 'config/.prettierrc.json' with your actual path.
        "prettier.configPath": "config/.prettierrc.json"
    }
    ```
    *Lưu ý: Cấu hình này không bao gồm các cài đặt cho PHP, đảm bảo nó chỉ tập trung vào Prettier.*

### Bước 5: Hoàn tất

Khởi động lại VS Code hoặc tải lại cửa sổ (`Developer: Reload Window`) để áp dụng tất cả các thay đổi. Từ giờ, các file frontend của bạn sẽ được tự động định dạng mỗi khi lưu.