<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Http\Requests\BuildRequest;
use App\Http\Requests\EnterRequest;
use App\Http\Requests\StartRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\LotteryUpdated;
use App\Events\UserVisitedPage;


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
                    //中間テーブルのリセット
                    $existingRoom->users()
                        ->where('is_owner', false)
                        ->where('is_active', true)
                        ->update([
                            'is_active' => false,
                            'win_count' => null,
                            'enter_timing' => null,
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
            ->where('is_active', true)
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
        $userStatus = $room->users()
            ->where('users.id', $userId)
            ->value('status');
        //banの記述の追加 
        if ($userStatus === 'banned'){
            return redirect('/')->withErrors([
                'error' => 'BANされています'
            ]);
        }
        if ($room){
            $room->users()->syncWithoutDetaching([$user->id]);
            $room->users()
                ->where('user_id', $userId)
                ->update([
                    'room_user.enter_timing' => now(),
                    'is_active' => true
                ]);
            $accessNumber = $room->users()
                ->where('is_owner', false)
                ->where('is_active', true)
                ->count();

            event(new UserVisitedPage($room->id, $accessNumber));
            return redirect('/wait/' .$room->id .'/' .$user->id);
        } else {
            return redirect('/enter')->withErrors([
                'error' => '部屋に入れませんでした。'
            ]);
        }        

        //is_activeをtrueにする
        //時間で部屋がアクティブか判断 バッチ
        //room解散をしたとき     
    }

    public function wait(Room $room, User $user)
    {
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
    // statusのリセット
    $room->users()->syncWithoutDetaching(
        $room->users()
            ->wherePivot('status', '!=', 'banned')
            ->pluck('users.id')
            ->mapWithKeys(fn($id) => [$id => ['status' => null]])
    );

    // is_winnerをリセット
    $room->users()->syncWithoutDetaching(
        $room->users()
            ->wherePivot('is_winner', true)
            ->pluck('users.id')
            ->mapWithKeys(fn($id) => [$id => ['is_winner' => false]]) // ✅ false に戻す
    );

    // ユーザーのランダム抽出
    $randomUserIds = $room->users()
        ->where('is_owner', false)
        ->where('win_count', '<', $room->max_win)
        ->where('is_active', true)
        ->inRandomOrder()
        ->take($room->number_of_winners)
        ->pluck('users.id');

    // is_winnerの更新
    if ($randomUserIds->isNotEmpty()){
        $room->users()
            ->whereIn('users.id', $randomUserIds)
            ->update(['is_winner' => true]); // ✅ true に戻す
        
        foreach ($randomUserIds as $userId) {
            $room->users()
                ->updateExistingPivot($userId, ['win_count' => \DB::raw('win_count + 1')]);
        }
    }        

    $randomUsers = $room->users()->whereIn('users.id', $randomUserIds)->get();
    $addUsers = '';

    event(new LotteryUpdated($room, $randomUsers));

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
        $kickedUsers = $room->users()
            ->where('status', 'kicked')
            ->count();
        $bannedUsers = $room->users()
            ->where('status', 'banned')
            ->where('is_winner', true)
            ->count();
        $extraSlots = $bannedUsers + $kickedUsers;

        $newWinners = $room->users()
            ->where('is_owner', false)
            ->where('is_winner', false)
            ->where('status', null)
            ->where('win_count', '<', $room->max_win)
            ->limit($extraSlots)
            ->get();

        foreach ($newWinners as $winner) {
            $room->users()->updateExistingPivot($winner->id, [
                'status' => 'added',
                'is_winner' => true
            ]);
        }
        
        $randomUsers = $room->users()
        ->wherePivot('is_winner', true)
        ->wherePivot('status', '!=', 'added')
        ->get();
        $addUsers = $room->users()
        ->wherePivot('status', 'added') 
        ->wherePivot('is_winner', true)
        ->get();
        $room->users()->syncWithoutDetaching(
            $room->users()
                ->wherePivot('status', '!=', 'banned')
                ->pluck('users.id')
                ->mapWithKeys(fn($id) => [$id => ['status' => null]])
        );
        $room->users()->syncWithoutDetaching(
            $room->users()
                ->wherePivot('status', 'banned')
                ->pluck('users.id')
                ->mapWithKeys(fn($id) => [$id => ['is_winner' => 0]])
        );
        
        event(new LotteryUpdated($room, $randomUsers, $addUsers));
        return view('lottery', compact('randomUsers', 'addUsers', 'room'));
    }

    
    public function destroyRoom(Room $room)
    {
        $room->delete();
        return redirect('/');
    }

    public function leaveRoom(Room $room, User $user)
    {
        $room->users()->where('users.id', $user->id)->update(['is_active' => 0, ]);
        return redirect('/');
    }
        
}