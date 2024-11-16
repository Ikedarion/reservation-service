<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\Restaurant;


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
        } else {
            $restaurantName = $request->input('restaurant_name_' . $id);
            if ($restaurantName) {
                $restaurant = Restaurant::where('user_id', $id)->first();
                if ($restaurant) {
                    $restaurant->update(['name' => $restaurantName]);
                }
            }

        }
        return redirect()->route('admin.index')->with('success','ユーザー情報の更新が完了しました。')->withInput();
    }

    public function delete($id){
        $user = User::find($id);
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'ユーザー情報の削除が完了しました。')->withInput();
    }

}
