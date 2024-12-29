<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class BuildController extends Controller
{
    public function index(User $user)//インポートしたUserをインスタンス化して$userとして使用
    {
        return $user->get();//$userの中身を戻り値にする。
    }
}