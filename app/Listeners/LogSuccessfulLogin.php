<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct(public Request $request)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        DB::table('activity_logs')->insert([
            'user_id' => $event->user->id,
            'action' => 'LOGIN',
            'description' => 'User berhasil login ke sistem',
            'ip_address' => $this->request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
