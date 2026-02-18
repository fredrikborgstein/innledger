<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->change();
            $table->integer('number_of_adults')->default(1)->after('check_out_date');
            $table->integer('number_of_children')->default(0)->after('number_of_adults');
        });

        DB::table('bookings')->update([
            'number_of_adults' => DB::raw('number_of_guests'),
        ]);

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('number_of_guests');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('number_of_guests')->default(1)->after('check_out_date');
        });

        DB::table('bookings')->update([
            'number_of_guests' => DB::raw('number_of_adults'),
        ]);

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['number_of_adults', 'number_of_children']);
            $table->foreignId('room_id')->nullable(false)->change();
        });
    }
};
