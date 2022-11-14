<?php

namespace Tests\Unit\Commands;

use App\Jobs\GetPropertyData;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class SyncPropertyDataTest extends TestCase
{
    public function testSyncPropertydata()
    {
        Queue::fake();

        $this->artisan('app:sync-property-data')
            ->execute();

        Queue::assertPushed(GetPropertyData::class);
    }
}
