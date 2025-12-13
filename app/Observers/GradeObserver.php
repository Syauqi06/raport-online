<?php

namespace App\Observers;
use App\Models\Grade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GradeObserver
{
    /**
     * Handle the Grade "created" event.
     */
    public function created(Grade $grade): void
    {
        $this->log('CREATE', "Input nilai {$grade->score} untuk siswa ID: {$grade->student_id}");
    }

    /**
     * Handle the Grade "updated" event.
     */
    public function updated(Grade $grade): void
    {
        $oldScore = $grade->getOriginal('score');
        $this->log('UPDATE', "Ubah nilai dari {$oldScore} menjadi {$grade->score}");
    }

    /**
     * Handle the Grade "deleted" event.
     */
    public function deleted(Grade $grade): void
    {
        $this->log('DELETE', "Hapus nilai ID: {$grade->id}");
    }

    /**
     * Handle the Grade "restored" event.
     */
    public function restored(Grade $grade): void
    {
        //
    }

    /**
     * Handle the Grade "force deleted" event.
     */
    public function forceDeleted(Grade $grade): void
    {
        //
    }

    public function log($action, $desc)
    {
        DB::table('activity_logs')->insert([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $desc,
            'ip_address' => request()->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
