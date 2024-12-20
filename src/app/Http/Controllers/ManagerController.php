<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Review;
use App\Http\Requests\ManagerReservationRequest;
use App\Http\Requests\CreateRestaurantRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;




class ManagerController extends Controller
{
    private $areas = [
        '北海道','青森','宮城','秋田','山形','福島','茨城','栃木','群馬',
        '埼玉','千葉','東京','神奈川','新潟','富山','石川','福井','山梨',
        '長野','岐阜','静岡','愛知','三重','滋賀','京都','大阪','兵庫','奈良',
        '和歌山','鳥取','島根','岡山','広島','山口','徳島','香川','愛媛',
        '高知','福岡','佐賀','長崎','熊本','大分','宮崎','鹿児島','沖縄'
    ];

    private $genres = ['寿司', 'ラーメン', '焼肉', '居酒屋', 'イタリアン', 'カフェ', '和食', '洋食', '韓国料理'];

    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::today();

        $reservations = Reservation::whereHas('restaurant',function($query) use($userId) {
            $query->where('user_id',$userId);
        })->where('date', '>=' , $today)->orderBy('date', 'asc')->paginate(15);
        return view('/manager/reservation',compact('reservations'));
    }

    public function search(Request $request)
    {
        $userId = Auth::id();

        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $res_id = $request->input('res_id');
        $number = $request->input('number');
        $status = $request->input('status');
        $keyword = $request->input('keyword');

        $reservations = Reservation::whereHas('restaurant', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->DateSearch($start_time, $end_time, $start_date, $end_date)
        ->IdSearch($res_id)
        ->NumberSearch($number)
        ->StatusSearch($status)
        ->KeywordSearch($keyword)
        ->orderBy('date', 'asc')
        ->paginate(15)
        ->appends($request->query());

        return view('/manager/reservation', compact('reservations'));
    }

    public function update(ManagerReservationRequest $request, $id)
    {
        $date = Carbon::parse($request->input('date_'. $id))->format('Y-m-d');
        $time = Carbon::parse($request->input('time_' . $id))->format('H:i:s');
        $dateTime = $date . ' ' . $time;
        $number = $request->input('number_' . $id);
        $status = $request->input('status_' . $id);

        $reservation = Reservation::find($id);
        if (!$reservation) {
            return redirect()->back()->with('error', '予約が見つかりません');
        }
        $reservation->update([
            'date' => $dateTime,
            'number' => $number,
            'status' => $status,
        ]);

        return redirect()->route('manager.index')->with('success', '予約の更新が完了しました');
    }

    public function delete($id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return redirect()->route('manager.index')->with('error', '予約が見つかりません');
        }
        $reservation->delete();

        return redirect()->route('manager.index')->with('success', '予約の削除が完了しました');
    }

    public function show()
    {
        $userId = Auth::id();
        $restaurant = Restaurant::where('user_id', $userId)->first();

        if (!$restaurant) {
            $areas = $this->areas;
            $genres = $this->genres;
            return view('/manager/restaurant-form', compact('areas', 'genres'));
        }

        return redirect()->route('manager.detail');
    }

    public function showDetail()
    {
        $userId = Auth::id();
        $areas = $this->areas;
        $genres = $this->genres;
        $restaurant = Restaurant::where('user_id', $userId)->first();

        if (!$restaurant) {
            return redirect()->route('manager.create');
        }

        return view('/manager/restaurant-detail', compact('restaurant', 'areas', 'genres'));
    }

    public function store(CreateRestaurantRequest $request)
    {
        $userId = Auth::id();
        $input = $request->only('name','genre','address','description');

        $path = storage_path('app/public/images');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 's3');
            $imageUrl = env('AWS_URL') . '/' . $imagePath;
            $input['image'] = $imageUrl;
        } else {
            $imageUrl = "https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg";
            $input['image'] = $imageUrl;
        }

        Restaurant::create([
            'user_id' => $userId,
            'name' => $input['name'],
            'genre' => $input['genre'],
            'address' => $input['address'],
            'description' => $input['description'],
            'image' => $input['image'],
        ]);

        return redirect()->route('manager.detail')->with('success','店舗情報の登録が完了しました。');
    }

    public function updateDetail(CreateRestaurantRequest $request)
    {
        $userId = Auth::id();
        $restaurant = Restaurant::where('user_id',$userId)->first();
        $input = $request->only('name', 'genre', 'address', 'description');

        if($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images','s3');
            $imageUrl = env('AWS_URL') . '/' . $imagePath;
            $input['image'] = $imageUrl;
            if($restaurant->image) {
                Storage::disk('s3')->delete(str_replace(env('AWS_URL') . '/', '', $restaurant->image));
            }
        } else {
            $imageUrl = "https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg";
            $input['image'] = $imageUrl;
        }

        $restaurant->update($input);
        return redirect()->route('manager.detail')->with('success', '店舗情報を更新しました。');
    }

    public function showReviews()
    {
        $userId = Auth::id();
        $restaurant = Restaurant::where('user_id',$userId)->first();

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

        return view('/manager/reviews',compact('reviews', 'ratingCounts', 'totalReviews', 'averageRating','restaurant'));
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

        return view('/manager/reviews', compact('reviews', 'ratingCounts', 'totalReviews', 'averageRating', 'restaurant'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_' . $id => 'required|string|max:255',
        ],[
            'reply_' . $id . '.required' => '返信内容は必須です。',
            'reply_' . $id . '.string' => '返信内容は文字列でなければなりません。',
            'reply_' . $id . '.max' => '返信内容は255文字以内で入力してください。',
        ]);

        $review = Review::find($id);
        $review->update([
            'reply' => $request->input('reply_' . $id),
        ]);
        return redirect()->back()->with('success','返信を送信しました。');
    }
}
