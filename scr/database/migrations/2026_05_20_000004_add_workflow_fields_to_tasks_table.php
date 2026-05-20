<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('priority');
            $table->text('result_note')->nullable()->after('completed_at');
            $table->string('result_link')->nullable()->after('result_note');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'result_note', 'result_link']);
        });
    }
};
