<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Http\Requests\BuildRequest;
use App\Http\Requests\EnterRequest;
use App\Http\Requests\StartRequest;
use Illuminate\Support\Facades\Auth;


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
        $input = $request['room'];
        $room->fill($input)->save();

        $userId = Auth::id();
        $user = User::findOrFail($userId);
        $room->users()->syncWithoutDetaching([
            $user->id => ['is_owner' => 1]
        ]);//中間テーブルの生成

        return redirect('/start/' .$room->id);

        //処理が上手くいった時は"/start"へリダイレクトされ、そうじゃないときは"/create"にエラー内容が表示されるようにしたい。
        //ここで中間テーブルも作成したいから、どのユーザーがアクセスしたかは大事。/create/{id}に後で修正してみる。
        //redirect後も'/start/{id}'にリダイレクトするようにする。
        //パスワードがかぶったとき、isownerがtrueの人はデータベースを更新できるようにする。
        //既存のパスワードの禁止(自分で生成した部屋のみ可能)
    }

    public function start(Room $room)
    {
        return view('start')->with(['room' => $room]);
    }

    public function startRoomPost(StartRequest $request, Room $room)
    {
        //gamepassの更新
        $room->gamepass = $request->gamepass;
        $room->save();

        
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
        if ($room){
            $room->users()->syncWithoutDetaching($user->id);
            return redirect('/wait/' .$room->id .'/' .$user->id);
        } else {
            return redirect('/enter')->with('error', '部屋に入れませんでした。');
            //無理矢理エラーにしてるが、ちゃんとエラーにできないか。
        }
        //同じパスワードを拒否
        //時間で部屋がアクティブか判断
        
        

    }

    public function wait(Room $room, User $user)
    {
    

        return view('wait', compact('room', 'user'));
    }

    public function lottery(Room $room)
    {
        //ユーザーのランダム抽出
        $randomUsers = Room::find($room->id)
            ->users()
            ->where('is_owner', 0)
            ->inRandomOrder()
            ->take(2)
            ->get();

        //is_activeも判断
        return view('lottery')->with('randomUsers', $randomUsers);;
    }
        
    
        
}