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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();//Mã
            $table->string('title');//Tiêu đề
            $table->text('packing')->nullable();//Quy cách
            $table->text('origin')->nullable();//Xuất xứ
            $table->text('delivery_condition');//Điều kiện giao hàng
            $table->text('payment_condition');//Điều kiện thanh toán
            $table->text('freight_charge')->nullable();//Cước vận tải
            $table->text('certificate')->nullable();//Chứng từ cung cấp
            $table->text('other_term')->nullable();//Điều khoản khác
            $table->dateTime('start_time');//Thời gian bắt đầu
            $table->dateTime('end_time');//Thời gian kết thúc
            $table->foreignId('creator_id')->constrained('admins')->onDelete('cascade');//Người tạo thầu
            $table->foreignId('reviewer_id')->nullable()->constrained('admins')->onDelete('cascade');//Người kiểm tra việc tạo thầu
            $table->foreignId('auditor_id')->nullable()->constrained('admins')->onDelete('cascade');//Người kiểm tra kết quả thầu
            $table->foreignId('approver_id')->nullable()->constrained('admins')->onDelete('cascade');//Người duyệt kết quả thầu
            $table->enum('status', ['Mở', 'Đóng', 'Đang diễn ra', 'Đang kiểm tra', 'Hủy']);
            $table->dateTime('closed_time')->nullable();
            $table->boolean('is_competitive')->default(false);
            $table->enum('approve_result', ['Đồng ý', 'Từ chối'])->nullable();
            $table->enum('audit_result', ['Đồng ý', 'Từ chối'])->nullable();
            $table->text('close_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
