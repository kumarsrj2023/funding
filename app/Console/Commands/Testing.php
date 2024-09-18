<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use MikeMcLin\WpPassword\Facades\WpPassword;

class Testing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dd(WpPassword::check('aqeeq', '$P$B0rYW2h4e/kVhBcBQRcuP1ibx/J2gl/'));

        $hashed_password = WpPassword::make('plain-text-password');

        dd($hashed_password);

        return Command::SUCCESS;
    }
}
