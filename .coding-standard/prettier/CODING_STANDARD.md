
# Tiêu chuẩn Code cho Frontend

Tài liệu này định nghĩa các quy tắc định dạng mã nguồn được áp dụng cho các file frontend của dự án, bao gồm HTML, Blade, CSS, JavaScript và JSON.

## 1. Công cụ định dạng

Toàn bộ việc định dạng code frontend được tự động hóa bằng công cụ **Prettier**. Các quy tắc đã được định nghĩa trong file `.prettierrc.json` ở thư mục gốc của dự án và sẽ được áp dụng tự động mỗi khi lưu file trong VS Code.

## 2. Các quy tắc định dạng chính

Dưới đây là các quy tắc quan trọng nhất mà Prettier sẽ thực thi.

### 2.1. Quy tắc chung

-   **Thụt đầu dòng (Indentation):**
    -   Sử dụng **4 dấu cách** cho mỗi cấp thụt lề.
    -   Không sử dụng Tab.

-   **Độ dài dòng (Line Width):**
    -   Code sẽ được tự động ngắt dòng nếu vượt quá **120 ký tự**.

-   **Dấu chấm phẩy (Semicolons):**
    -   Luôn luôn thêm dấu chấm phẩy (`;`) ở cuối các câu lệnh trong JavaScript.

-   **Dấu ngoặc (Quotes):**
    -   Sử dụng **ngoặc đơn (`'`)** cho tất cả các chuỗi trong JavaScript và các thuộc tính của Blade/HTML.

-   **Dấu phẩy cuối (Trailing Comma):**
    -   Tự động thêm dấu phẩy ở phần tử cuối cùng cho các đối tượng và mảng nhiều dòng (theo chuẩn `es5`). Việc này giúp cho việc xem lại lịch sử thay đổi trên Git (diff) trở nên sạch sẽ và rõ ràng hơn.

### 2.2. Quy tắc cho HTML & Blade

-   **Thuộc tính trên mỗi dòng (Attribute Per Line):**
    -   Nếu một thẻ HTML có nhiều thuộc tính, mỗi thuộc tính sẽ được Prettier tự động đặt trên một dòng riêng. Quy tắc này giúp code cực kỳ dễ đọc và dễ quản lý.

    **Ví dụ:**

    *Code bạn viết:*
    ```html
    <button class="btn btn-primary" id="submit-button" type="submit" data-user-id="123">Submit</button>
    ```

    *Code sau khi lưu (tự động định dạng):*
    ```html
    <button
        class="btn btn-primary"
        id="submit-button"
        type="submit"
        data-user-id="123"
    >
        Submit
    </button>
    ```

-   **Cú pháp Blade:**
    -   Các chỉ thị của Blade (`@if`, `@foreach`, `@auth`...) sẽ được thụt lề và định dạng khoảng cách một cách nhất quán, đảm bảo cấu trúc template luôn rõ ràng.

    **Ví dụ:**

    ```blade
    @if (isset($user))
        <div class="user-greeting">
            <p>
                Hello, {{ $user->name }}
            </p>
        </div>
    @else
        <div class="guest-greeting">
            <p>
                Hello, Guest
            </p>
        </div>
    @endif
    ```