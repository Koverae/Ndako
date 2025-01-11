<?php

namespace Modules\ChannelManager\Console\Commands\Connector;

use Illuminate\Console\Command;
use Modules\ChannelManager\Services\Connector\BookingComConnector;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncBookingComReservations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sync:bookingcom-reservations';

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connector = new BookingComConnector();
        $reservations = $connector->getReservations();
    
        if ($reservations) {
            // Store reservations in the database
            foreach ($reservations as $reservation) {
                // Logic to save or update reservations
            }
            $this->info('Reservations synced successfully.');
        } else {
            $this->error('Failed to sync reservations.');
        }
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
