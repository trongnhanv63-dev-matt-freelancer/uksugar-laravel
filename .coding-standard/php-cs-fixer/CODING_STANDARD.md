# Hướng dẫn về Tiêu chuẩn Code (Coding Standard)

Tài liệu này định nghĩa các quy tắc và tiêu chuẩn về định dạng mã nguồn (code style) được áp dụng cho toàn bộ dự án. Việc tuân thủ các tiêu chuẩn này là **bắt buộc** đối với tất cả các lập trình viên khi tham gia vào dự án.

Mục tiêu của chúng ta là duy trì một mã nguồn sạch, dễ đọc, nhất quán và dễ bảo trì.

## 1. Tiêu chuẩn nền tảng: PSR-12

Dự án sử dụng **PSR-12 (Extended Coding Style)** làm bộ quy tắc nền tảng. Toàn bộ code phải tuân thủ nghiêm ngặt theo tiêu chuẩn này.

Ngoài ra, dự án được tích hợp công cụ **PHP CS Fixer** để tự động định dạng code theo các quy tắc đã định nghĩa mỗi khi lưu file. Điều này giúp giảm thiểu sai sót của con người và đảm bảo 100% code được commit lên sẽ tuân thủ tiêu chuẩn.

## 2. Các quy tắc định dạng chính

Dưới đây là tóm tắt các quy tắc quan trọng nhất được rút ra từ PSR-12 và các quy tắc bổ sung của dự án.

### 2.1. Độ dài dòng

-   **Giới hạn "mềm" (Soft Limit):** Cố gắng giữ cho độ dài mỗi dòng code dưới **80 ký tự**.
-   **Giới hạn "cứng" (Hard Limit):** Một dòng code **KHÔNG ĐƯỢC** vượt quá **120 ký tự**.
-   **Lưu ý:** Lập trình viên có trách nhiệm tự quyết định vị trí ngắt dòng sao cho hợp lý và dễ đọc nhất. `PHP CS Fixer` sẽ tự động căn chỉnh lại thụt đầu dòng sau khi bạn đã ngắt dòng.

### 2.2. Thụt đầu dòng (Indentation)

-   Sử dụng **4 dấu cách** cho mỗi cấp thụt đầu dòng.
-   **KHÔNG** được sử dụng ký tự Tab.

```php
// ✅ ĐÚNG
foreach ($items as $item) {
    if ($item->isActive()) {
        $item->process();
    }
}

// ❌ SAI
foreach ($items as $item) {
	if ($item->isActive()) { // Dùng Tab
		$item->process();
	}
}
```

### 2.3. Từ khóa và Cấu trúc điều khiển

-   Tất cả các từ khóa của PHP (`if`, `else`, `elseif`, `for`, `foreach`, `while`, `switch`, `true`, `false`, `null`...) phải được viết bằng chữ thường.
-   Phải có **1 dấu cách** sau các từ khóa điều khiển.
-   Dấu ngoặc nhọn mở (`{`) phải nằm trên cùng một dòng. Dấu ngoặc nhọn đóng (`}`) phải nằm trên một dòng riêng.

```php
// ✅ ĐÚNG
if ($condition === true) {
    // some logic
} elseif ($anotherCondition) {
    // other logic
} else {
    // final logic
}
```

### 2.4. Toán tử (Operators)

-   Phải có **1 dấu cách** trước và sau các toán tử nhị phân (binary operators) như `+`, `-`, `*`, `/`, `=`, `.`, `==`, `===`, `&&`, `||`, v.v.

```php
// ✅ ĐÚNG
$sum = $firstValue + $secondValue;
$fullName = $firstName . ' ' . $lastName;

// ❌ SAI
$sum=$firstValue+$secondValue;
$fullName = $firstName.$lastName;
```

### 2.5. Khai báo thuộc tính và phương thức

-   Phải khai báo rõ ràng tầm vực (visibility) cho tất cả các thuộc tính và phương thức (`public`, `protected`, `protected`).
-   Từ khóa `static` phải được khai báo sau tầm vực.

```php
// ✅ ĐÚNG
class MyClass
{
    public static $myStaticVar = 'foo';
    protected $myProperty;

    public function __construct($propertyValue)
    {
        $this->myProperty = $propertyValue;
    }
}
```

## 3. Các quy tắc bổ sung của dự án (Tự động bởi PHP CS Fixer)

Ngoài PSR-12, dự án áp dụng thêm các quy tắc sau để tăng chất lượng code:

-   **Cú pháp mảng ngắn:** Luôn sử dụng `[]` thay vì `array()`.
    ```php
    // ✅ ĐÚNG
    $myArray = [1, 2, 3];
    // ❌ SAI
    $myArray = array(1, 2, 3);
    ```

-   **Sử dụng ngoặc đơn cho chuỗi:** Ưu tiên sử dụng ngoặc đơn (`'`) cho các chuỗi không chứa biến. Chỉ sử dụng ngoặc kép (`"`) khi cần nội suy biến. Công cụ sẽ tự động chuyển đổi.
    ```php
    // ✅ ĐÚNG
    $greeting = 'Hello world';
    $name = 'John';
    $message = "Hello, $name!";

    // ❌ SAI (sẽ bị tự động sửa)
    $greeting = "Hello world";
    ```

-   **Sắp xếp `use` statements:** Các câu lệnh `use` ở đầu file sẽ được tự động sắp xếp theo thứ tự alphabet.

-   **Xóa `use` statements không dùng:** Các câu lệnh `use` không được gọi đến trong file sẽ bị tự động xóa đi.

-   **Dấu phẩy ở cuối (Trailing Comma):** Các mảng được viết trên nhiều dòng sẽ được tự động thêm một dấu phẩy ở phần tử cuối cùng. Điều này giúp cho việc xem lịch sử thay đổi trên Git (diff) trở nên sạch sẽ hơn.
    ```php
    // ✅ ĐÚNG
    $myArray = [
        'apple',
        'banana',
        'orange', // Dấu phẩy ở cuối được tự động thêm
    ];
    ```

## 4. Tổng kết

Việc tuân thủ các quy tắc trên không chỉ là một yêu cầu mà còn là sự thể hiện tính chuyên nghiệp và tôn trọng đối với các thành viên khác trong nhóm. Một mã nguồn nhất quán sẽ giúp giảm thời gian đọc hiểu, dễ dàng tìm lỗi và tăng tốc độ phát triển chung của toàn dự án.