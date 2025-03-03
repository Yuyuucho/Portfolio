<?php

namespace App\Http\Controllers;

use App\Events\KickOrBanUpdated;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class KickOrBanController extends Controller
{
    public function kick(Room $room, User $user)
    {
        // ユーザーのステータスを "kicked" に更新
        $room->users()
            ->where('users.id', $user->id)
            ->update([
                'status' => 'kicked',
                'is_winner' => false,
                'is_active' => false
        ]);

        // イベントを発火
        event(new KickOrBanUpdated($room->id, $user->id));

        return response()->json(['message' => 'User kicked successfully']);
    }

    public function ban(Room $room, User $user)
    {
        // ユーザーのステータスを "banned" に更新
        $room->users()
            ->where('users.id', $user->id)
            ->update([
                'status' => 'banned',
                'is_active' => false
            ]);

        // イベントを発火
        event(new KickOrBanUpdated($room->id, $user->id));

        return response()->json(['message' => 'User banned successfully']);
    }
}
