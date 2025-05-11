<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('escorts', function (Blueprint $table) {
            // ID có thể dùng bigint tự tăng (hoặc uuid tuỳ chọn)
            $table->id();

            // Tên: chuỗi kí tự
            $table->string('name');

            // Mô tả: kiểu text, có thể null
            $table->text('description')->nullable();

            // Đường dẫn ảnh: chuỗi kí tự, có thể null
            $table->string('image')->nullable();

            // Trạng thái: enum gồm 'public', 'private', 'hidden', mặc định là 'private'
            $table->enum('status', ['public', 'private', 'hidden'])->default('public');

            // Theo dõi người tạo và cập nhật (liên kết tới bảng users)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Timestamps (created_at, updated_at)
            $table->timestamps();

            // Khóa ngoại tham chiếu tới users.id
            // Khóa ngoại foreign(...): đảm bảo liên kết với bảng users. Ta dùng onDelete('set null') để nếu người dùng bị xóa, giá trị này sẽ thành null, giữ nguyên dữ liệu escort.
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escorts');
    }
};
