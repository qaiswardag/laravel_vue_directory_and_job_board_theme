<?php

namespace App\Console\Commands;

use App\Models\Store\Store as StoreStore;
use Illuminate\Console\Command;

class Store extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "app:store";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Command description";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        StoreStore::factory(20)->create();

        $this->info("Seeded successfully, new Stores for production.");
    }
}
