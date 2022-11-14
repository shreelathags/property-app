<?php

namespace App\Console\Commands;

use App\Jobs\GetPropertyData;
use Illuminate\Console\Command;

class SyncPropertyData extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:sync-property-data';

    /**
     * @var string
     */
    protected $description = 'This command initiates syncing of the property data from the external API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //Initiate syncing of property data from the API
        GetPropertyData::dispatch();

        return 0;
    }
}
