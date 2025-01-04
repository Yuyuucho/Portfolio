<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Http\Requests\BuildRequest;
use App\Http\Requests\EnterRequest;
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
        return redirect('/start/' .$room->id);

        //処理が上手くいった時は"/start"へリダイレクトされ、そうじゃないときは"/create"にエラー内容が表示されるようにしたい。
        //ここで中間テーブルも作成したいから、どのユーザーがアクセスしたかは大事。/create/{id}に後で修正してみる。
        //redirect後も'/start/{id}'にリダイレクトするようにする。
        //パスワードがかぶったとき、isownerがtrueの人はデータベースを更新できるようにする。
    }

    public function start(Room $room)
    {
        return view('start')->with(['room' => $room]);
    }

    public function startRoomPost(Room $room)
    {
        return view('start')->with(['room' => $room]);
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
            return redirect('/wait/' .$room->id .'/' .$user->id);//ユーザーとルームのidを合わせた固有の値を使いたい。.$room->id .$user->id
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

        
        
    
        
}