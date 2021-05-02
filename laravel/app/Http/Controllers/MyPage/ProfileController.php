<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Mypage\Profile\EditRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfileEditForm()
    {
        return view('mypage.profile_edit_form')
            ->with('user', Auth::user());
    }

    // メソッドインジェクションを活用している
    //メソッドインジェクションとは、ルート定義からコントローラのメソッドを呼び出した際に、引数のクラスを自動的に生成して渡してくれる仕組み
    public function editProfile(EditRequest $request)
    {
        // ログインしているユーザー情報の取得
        $user = Auth::user();

        // フォームに入力された名前を、取得したユーザーの名前に割り当てる
        // $requestにより値を取得している。そして任意の値を指定して取り出せる
        $user->name = $request->input('name');

        // 名前を割り当てた状態で、情報を保存する save()によりDBに情報が保存される
        $user->save();

        // 前画面に戻り、保存完了の旨を伝えるメッセージを画面に表示させる with()で戻る際に情報を差し込む
        return redirect()->back()
            ->with('status', 'プロフィールを変更しました');
    }
}
