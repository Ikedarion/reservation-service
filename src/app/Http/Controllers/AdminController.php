<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\SendEmailRequest;
use App\Models\User;
use App\Models\Review;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
                    Log::error("メール送信エラー: {$e->getMessage()} (ユーザーID: {$userId})");
                }
            }
        }

        if ($successCount > 0) {
            $message = "メール送信が完了しました。成功: {$successCount} 件, 失敗: {$failCount} 件";
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', '全てのメール送信に失敗しました。');
        }
    }

    public function showReviews()
    {
        $restaurants = Restaurant::all();

        $reviews = $restaurants->reservations()
            ->with('review')->get()->pluck('review')->filter()
            ->sortByDesc('created_at');
        $averageRating = $reviews->avg('rating');

        $ratingCounts = $reviews->groupBy('rating')
        ->map(fn($group) => $group->count());
        $totalReviews = $reviews->count();
        $ratingCounts = collect([5, 4, 3, 2, 1])->mapWithKeys(function ($rating) use ($ratingCounts) {
            return [$rating => $ratingCounts->get($rating, 0)];
        });

        return view('/admin/reviews', compact('reviews', 'ratingCounts', 'totalReviews', 'averageRating', 'restaurant'));
    }

    public function filter(Request $request)
    {
        $userId = Auth::id();
        $restaurant = Restaurant::where('user_id', $userId)->first();

        $reviews = $restaurant->reservations()
            ->with('review')->get()->pluck('review')->filter()
            ->sortByDesc('created_at');
        $averageRating = $reviews->avg('rating');

        $ratingCounts = $reviews->groupBy('rating')
        ->map(fn($group) => $group->count());
        $totalReviews = $reviews->count();
        $ratingCounts = collect([5, 4, 3, 2, 1])->mapWithKeys(function ($rating) use ($ratingCounts) {
            return [$rating => $ratingCounts->get($rating, 0)];
        });
        $reviews = Review::query()
            ->withStarRating($request->star_rating)
            ->sortBy($request->sort_by)
            ->status($request->status)
            ->keywordSearch($request->keyword)
            ->dateSearch($request->start_date, $request->end_date)
            ->get();

        return view('/admin/reviews', compact('reviews', 'ratingCounts', 'totalReviews', 'averageRating', 'restaurant'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_' . $id => 'required|string|max:255',
        ], [
            'reply_' . $id . '.required' => '返信内容は必須です。',
            'reply_' . $id . '.string' => '返信内容は文字列でなければなりません。',
            'reply_' . $id . '.max' => '返信内容は255文字以内で入力してください。',
        ]);

        $review = Review::find($id);
        $review->update([
            'reply' => $request->input('reply_' . $id),
        ]);
        return redirect()->back()->with('success', '返信を送信しました。');
    }
}
