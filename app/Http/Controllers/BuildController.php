<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Http\Requests\BuildRequest;

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

    public function rstore(BuildRequest $request, Room $room)
    {
        $input = $request['room'];
        $room->fill($input)->save();
        return redirect('/start/' .$room->id);

        //処理が上手くいった時は"/start"へリダイレクトされ、そうじゃないときは"/create"にエラー内容が表示されるようにしたい。
        //ここで中間テーブルも作成したいから、どのユーザーがアクセスしたかは大事。/create/{id}に後で修正してみる。
        //redirect後も'/start/{id}'にリダイレクトするようにする。
    }

    public function start(Room $room)
    {
        return view('start')->with(['room' => $room]);
    }

    public function enter()
    {
        return view('enter');
    }
}