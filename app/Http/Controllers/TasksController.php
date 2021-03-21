<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // タスク一覧を取得
            $tasks = Task::orderBy('id', 'desc')->paginate(25);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
         // タスク一覧ビューでそれを表示
        return view('welcome', $data);
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
         $task = new Task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
         ]);
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10', 
            'content' => 'required|max:255',
        ]);
        
         // タスクを作成
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
        }else{
            // トップページへリダイレクトさせる
            return redirect('/');
        }

        // タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    // getでtasks/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
   public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
        }else{
            // トップページへリダイレクトさせる
            return redirect('/');
        }
        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }
    
    // putまたはpatchでtasks/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
         // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
         // タスクを更新
         $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // deleteでtasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを削除
        if (\Auth::id() === $task->user_id) {
        $task->delete();
        }else{
            // トップページへリダイレクトさせる
            return redirect('/');
        }
        // トップページへリダイレクトさせる
        return redirect('/');
            
    }
}

