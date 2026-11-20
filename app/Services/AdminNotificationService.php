<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Cache;

class AdminNotificationService
{
    public function notifyAdmins(string $type, string $title, string $message, ?string $url = null): void
    {
        $notification = new AdminActivityNotification([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ]);

        User::query()
            ->where('role', 'admin')
            ->each(fn (User $admin) => $admin->notify($notification));
    }

    public function notifyJudge0Failure(?int $statusCode = null): void
    {
        $cacheKey = 'admin-notification:judge0:'.($statusCode ?? 'connection');

        if (! Cache::add($cacheKey, true, now()->addMinutes(5))) {
            return;
        }

        $isRateLimited = $statusCode === 429;
        $this->notifyAdmins(
            $isRateLimited ? 'system_warning' : 'system_error',
            $isRateLimited ? 'Batas layanan Judge0 tercapai' : 'Layanan Judge0 bermasalah',
            $isRateLimited
                ? 'Judge0 mengembalikan HTTP 429. Periksa kuota atau batas permintaan API.'
                : 'Eksekusi kode gagal menghubungi Judge0. Periksa koneksi dan kesehatan layanan.',
            route('admin.pengaturan'),
        );
    }
}
