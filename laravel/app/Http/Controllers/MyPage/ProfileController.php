<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Mypage\Profile\EditRequest;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

        // $requestに画像ファイルがあれば、アップロード(POST)された画像の保存処理を行う
        if ($request->has('avatar')) {
            // saveAvatar() によりアバター画像をストレージに保存する
            $fileName = $this->saveAvatar($request->file('avatar'));
            $user->avatar_file_name = $fileName;
        }

        // 名前を割り当てた状態で、情報を保存する save()によりDBに情報が保存される
        $user->save();

        // 前画面に戻り、保存完了の旨を伝えるメッセージを画面に表示させる with()で戻る際に情報を差し込む
        return redirect()->back()
            ->with('status', 'プロフィールを変更しました');
    }
    /**
      * アバター画像をリサイズして保存します
      *
      * @param UploadedFile $file アップロードされたアバター画像
      * @return string ファイル名
      */
    private function saveAvatar(UploatedFile $file):string
    {
        // 画像のパスはmakeTempPath()を利用して作成する(このメソッドの下で作成する) ここで作成した画像ファイルは一時的なもの。pushFile()で指定ストレージに正式に保存する
        $tempPath = $this->makeTempPath();

        // Intervention Imageを利用して画像をリサイズ。リサイズした画像を一時ファイルに保存する
        // Image::make() この記述で UploadedFileインスタンスからIntervention Imageのインスタンスを生成
        Image::make($file)->fit(200, 200)->save($tempPath);

        // 画像をストレージに保存
        // Storage::disk() により、Filesystemクラスのインスタンスを取得 保存先の指定をしている
        // putFile() で画像の保存 第一引数にフォルダを指定。第二引数で保存したい画像のFileインスタンスを指定
        // 先ほどまで作成した一時ファイルを指定して、正式にストレージに保存する
        $filePath = Storage::disk('public')
            ->putFile('avatars', newFile($tempPath));

        return basename($filePath);
    }
    /**
      * 一時的なファイルを生成してパスを返します。
      *
      * @return string ファイルパス
      */
    private function makeTempPath():string
    {
        // tmpfile() により/tmpに一時ファイルの生成 返り値はファイルポインタ（ファイルの格納場所の情報）
        $tmp_fp = tmpfile();

        //  stream_get_meta_data($data) ファイルのmeta情報の取得が返り値となる 引数にファイルポインタを指定すること
        $meta = stream_get_meta_data($tmp_fp);

        // メタ情報からURI(ファイルのパス)を取得
        return $meta["uri"];
    }
}
