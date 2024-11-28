<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\SendEmailRequest;
use App\Models\User;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\UserNotificationMail;


class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('role','ユーザー')->get();
        $managers = User::where('role','店舗代表者')->get();
        $admins = User::where('role','管理者')->get();

        return view('/admin/user',compact('users','managers','admins'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $users = User::where('role', 'ユーザー')
                    ->KeywordSearch($keyword)->get();
        $managers = User::with('restaurant')
                    ->where('role', '店舗代表者')
                    ->KeywordSearch($keyword)->get();
        $admins = User::where('role', '管理者')
                    ->KeywordSearch($keyword)->get();

        return view('/admin/user', compact('users', 'managers', 'admins'));
    }

    public function update(UserUpdateRequest $request,$id)
    {
        $userName = $request->input('name_' . $id);
        $userEmail = $request->input('email_' . $id);
        $userRole = $request->input('role_' . $id);
        $user = User::findOrFail($id);

        $user->update([
            'name' => $userName,
            'email' => $userEmail,
            'role' => $userRole,
        ]);

        if ($userRole !== '店舗代表者') {
            $restaurant = Restaurant::where('user_id', $id)->first();
            if ($restaurant) {
                $restaurant->user_id = null;
                $restaurant->save();
            }
        }
        return redirect()->route('admin.index')->with('success','ユーザー情報の更新が完了しました。')->withInput();
    }

    public function delete($id){
        $user = User::find($id);
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'ユーザー情報の削除が完了しました。')->withInput();
    }

    public function store(RegisterRequest $request)
    {
        $request->validate([
            'role' => 'required',
        ],[
            'role.required' => '※役割は必須です。'
        ]);
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'email_verified_at' => Carbon::now(),
        ]);

        return redirect()->route('admin.index')->with('success','ユーザーの登録が完了しました。');
    }

    public function showSendMailForm(Request $request)
    {
        $users = User::where('role', 'ユーザー')->get();
        $managers = User::where('role', '店舗代表者')->get();
        $admins = User::where('role', '管理者')->get();

        return view('admin/send_email', compact('users', 'managers', 'admins'));
    }


    public function sendMail(SendEmailRequest $request)
    {
        $user_ids = $request->input('user_ids');
        $successCount = 0;
        $failCount = 0;

        foreach ($user_ids as $userId) {
            $user = User::find($userId);
            if ($user) {
                $data = [
                    'subject' => $request->input('subject'),
                    'message' => $request->input('message'),
                    'user_name' => $user->name,
                ];
                try {
                    Mail::to($user->email)->send(new UserNotificationMail($data));
                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                    // ログに記録（オプション）
                    Log::error("メール送信エラー: {$e->getMessage()} (ユーザーID: {$userId})");
                }
            }
        }

        // メール送信結果をフィードバック
        if ($successCount > 0) {
            $message = "メール送信が完了しました。成功: {$successCount} 件, 失敗: {$failCount} 件";
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', '全てのメール送信に失敗しました。');
        }
    }
}
