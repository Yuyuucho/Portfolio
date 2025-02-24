<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Http\Requests\BuildRequest;
use App\Http\Requests\EnterRequest;
use App\Http\Requests\StartRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class BuildController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function create()
    {
        return view('create');
    }

    public function storeRoom(BuildRequest $request, Room $room)
    {
        $input = $request->input('room');
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        //roompassの重複不可
        $existingRoom = $room->Where('roompass', $input['roompass'])->first();
        if ($existingRoom){
            $isOwner = $existingRoom->users()
                ->where('user_id', $userId)
                ->wherePivot('is_owner', true)
                ->exists();
                if($isOwner){
                    $existingRoom->fill($input)->save();
                    //中間テーブルの生成
                    $existingRoom->users()->syncWithoutDetaching([
                        $user->id => ['is_owner' => true]
                    ]);
                    return redirect('/start/' .$existingRoom->id);
                }
                return redirect('/create')->withErrors([
                    'error' => 'そのルームパスはすでに使用されています。'
                ]);
        }
        
        $room->fill($input)->save();
        //中間テーブルの生成
        $room->users()->syncWithoutDetaching([
            $user->id => ['is_owner' => true]
        ]);
        return redirect('/start/' .$room->id);
    }

    public function start(Room $room)
    {
        $accessNumber = $room->users()
            ->where('is_owner', false)
            ->where('room_user.updated_at', '>=', Carbon::now()->subHour())
            ->count();
        return view('start', compact('room', 'accessNumber'));
    }

    public function startRoomPost(StartRequest $request, Room $room)
    {
        $input = $request->input('room');
        $input['is_active'] = true;//is_activeをfalseにするロジックを後で追加する
        $room->fill($input)->save();
        return redirect('/lottery/' .$room->id);
    }

    public function enter()
    {
        return view('enter');
    }

    public function joinRoom(EnterRequest $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        $room = Room::where('roompass', $request->roompass)->first();
        /*banの記述の追加 */
        if ($room){
            $room->users()->syncWithoutDetaching([$user->id]);
            $room->users()->where('user_id', $userId)->update(['room_user.enter_timing' => now()]);
            return redirect('/wait/' .$room->id .'/' .$user->id);
        } else {
            return redirect('/enter')->withErrors([
                'error' => '部屋に入れませんでした。'
            ]);
        }
        
        //時間で部屋がアクティブか判断 バッチ
        //room解散をしたとき     
    }

    public function wait(Room $room, User $user)
    {
        /*ここにもbanの記述が必要そう */
        $owner = $room->users()
            ->where('is_owner', true)
            ->first(['name']);

        $pivotData = $room->users()->where('users.id', $user->id)->first()->pivot;

        $userRoomTimestamp = $room->users()
            ->where('user_id', $user->id)
            ->pluck('room_user.enter_timing')
            ->first();
        $enterTiming = $room->updated_at < $userRoomTimestamp;
        return view('wait', compact('room', 'user', 'owner', 'pivotData', 'enterTiming'));
    }

    public function lottery(Room $room)
    {   
        //is_winnerをリセット     
        $room->users()
            ->where('is_winner', true)
            ->update(['is_winner' => false]);
        //ユーザーのランダム抽出
        /*banの記述を追加 */
        $randomUserIds = $room->users()
            ->where('is_owner', false)
            ->where('win_count', '<', $room->max_win)
            ->inRandomOrder()
            ->take($room->number_of_winners)
            ->pluck('users.id');
            //is_activeも判断

        //is_winnerの更新
        if ($randomUserIds->isNotEmpty()){
            $room->users()
                ->whereIn('users.id', $randomUserIds)
                ->update(['is_winner' => true]);
            foreach ($randomUserIds as $userId) {
            $room->users()
                ->updateExistingPivot($userId, ['win_count' => \DB::raw('win_count + 1')]);
            }
        }        
        $randomUsers = $room->users()->whereIn('users.id', $randomUserIds)->get();
        $addUsers = '';
        return view('lottery', compact('randomUsers', 'addUsers', 'room'));
    }

    public function nextLottery(StartRequest $request, Room $room)
    {
        $input = $request->input('room');
        $input['is_active'] = true;
        $room->fill($input)->save();
        return redirect('/lottery/' .$room->id);
    }

    public function addLottery(StartRequest $request, Room $room)
    {
        $room->users()
        ->whereIn('status', ['kicked', 'added'])
        ->update(['status' => null]);
        $kickOrBanData = $request->input('kick_or_ban');
        foreach ($kickOrBanData as $userId => $action) {
            if ($action ==='kick' || $action === 'ban') {
                $status = $action === 'kick' ? 'kicked' : 'banned';/* */
                $room->users()
                ->updateExistingPivot($userId, [
                    'is_winner' => false,
                    'status' => $status,
                ]);
                /*banの記述を追加。 */
                $newWinnerId = $room->users()
                ->where('is_owner', false)
                ->where('is_winner', false)
                ->where('win_count', '<', $room->max_win)
                ->limit(1)
                ->pluck('users.id')
                ->first();

                if ($newWinnerId) {
                    $room->users()
                    ->updateExistingPivot($newWinnerId, [
                        'is_winner' => true,
                        'status' => 'added',
                    ]);
                }                
            }
        }
        $randomUsers = $room->users()
        ->where('is_winner', true)
        ->where('status', null)
        ->get();
        $addUsers = $room->users()
        ->where('is_winner', true)
        ->where('status', 'added')
        ->get();

        return view('lottery', compact('randomUsers', 'addUsers', 'room'));
    }

    
    public function destroyRoom(Room $room)
    {
        $room->delete();
        return redirect('/');
    }
        
}