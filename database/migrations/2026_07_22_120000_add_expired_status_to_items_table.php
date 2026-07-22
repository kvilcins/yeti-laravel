<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * `auctions:complete` marks a lot that closed without a single bid as "expired",
 * but that value was missing from the status enum, so MySQL rejected the write
 * in strict mode. This adds it.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement(
            "ALTER TABLE items MODIFY COLUMN status
             ENUM('active', 'inactive', 'completed', 'cancelled', 'expired')
             NOT NULL DEFAULT 'active'"
        );
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::table('items')->where('status', 'expired')->update(['status' => 'inactive']);

        DB::statement(
            "ALTER TABLE items MODIFY COLUMN status
             ENUM('active', 'inactive', 'completed', 'cancelled')
             NOT NULL DEFAULT 'active'"
        );
    }
};
