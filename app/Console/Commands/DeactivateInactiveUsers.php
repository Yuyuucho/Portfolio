<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;
use Carbon\Carbon;

class DeactivateInactiveUsers extends Command
{
    protected $signature = 'users:deactivate-inactive';
    protected $description = '3時間以上アクティブでないユーザーを非アクティブ化する';

    public function handle()
    {
        Room::with('users')->get()->each(function ($room) {
            $room->users()
                ->where('is_active', true)
                ->where('is_owner', false)
                ->where('room_user.updated_at', '<', Carbon::now()->subHours(1))
                ->update(['is_active' => false]);
        });

        $this->info('非アクティブなユーザーを無効化しました。');
    }
}
