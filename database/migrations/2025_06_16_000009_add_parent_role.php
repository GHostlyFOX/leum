<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('ref_user_roles')->insert([
            'id'   => 9,
            'name' => 'Родитель',
        ]);
    }

    public function down(): void
    {
        DB::table('ref_user_roles')->where('id', 9)->delete();
    }
};
