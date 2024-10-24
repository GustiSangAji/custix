<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;

class ClearExpiredAccess extends Command
{
    protected $signature = 'clear-expired-access';
    protected $description = 'Menghapus user_id yang sudah kadaluarsa dari tabel ticket_access';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expirationTime = Carbon::now()->subMinutes(1);
        DB::table('ticket_access')
            ->where('created_at', '<', $expirationTime)
            ->delete();

        $this->info('Expired ticket access cleared successfully.');
    }
}
