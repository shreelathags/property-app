<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class FeatureTestCase extends TestCase
{
    use InteractsWithDatabase;

    /**
     * If true, setup has run at least once.
     * @var boolean
     */
    protected static $setUpHasRunOnce = false;

    /**
     * After the first run of setUp "migrate:fresh --seed"
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$setUpHasRunOnce) {
            Artisan::call('migrate:fresh --seed');

            static::$setUpHasRunOnce = true;
        }

        $tableNames = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");

        Schema::disableForeignKeyConstraints();

        foreach ($tableNames as $table) {
            //if you don't want to truncate migrations
            if (Str::contains($table->name, 'migrations')) {
                continue;
            }

            DB::table($table->name)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }
}
