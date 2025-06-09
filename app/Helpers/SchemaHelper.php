<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class SchemaHelper
{
    protected static array $cache = [];

    public static function hasColumn(string $table, string $column): bool
    {
        $key = "{$table}.{$column}";

        if (!array_key_exists($key, self::$cache)) {
            try {
                // Check if table exists first (optional safety)
                if (!Schema::hasTable($table)) {
                    Log::warning("SchemaHelper: Table '{$table}' does not exist.");
                    self::$cache[$key] = false;
                } else {
                    self::$cache[$key] = Schema::hasColumn($table, $column);
                }
            } catch (\Throwable $e) {
                Log::error("SchemaHelper error on {$table}.{$column}: {$e->getMessage()}");
                self::$cache[$key] = false;
            }
        }

        return self::$cache[$key];
    }
}
