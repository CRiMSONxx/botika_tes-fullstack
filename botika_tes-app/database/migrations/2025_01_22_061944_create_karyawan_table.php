<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * https://laravel.com/docs/11.x/eloquent#soft-deleting
     * @return void
     */
    public function up()
    {
        // Table for Divisi Karyawan
        Schema::create('divisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_divisi');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table for Pekerjaan Karyawan
        Schema::create('pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pekerjaan');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table for Data Diri Karyawan
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('no_telepon')->unique();
            $table->date('tanggal_lahir');
            $table->text('file')->nullable();
            $table->foreignId('divisi_id')->nullable()->constrained('divisis')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('pekerjaan_id')->nullable()->constrained('pekerjaans')->onUpdate('cascade')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawans');
        Schema::dropIfExists('pekerjaans');
        Schema::dropIfExists('divisis');
    }
};
