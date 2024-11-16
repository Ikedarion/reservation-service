<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ReservationUpdateRequest;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    private $areas = [
        '北海道','青森','岩手','宮城','秋田','山形','福島','茨城','栃木','群馬',
        '埼玉','千葉','東京','神奈川','新潟','富山','石川','福井','山梨','長野',
        '岐阜','静岡','愛知','三重','滋賀','京都','大阪','兵庫','奈良','和歌山',
        '鳥取','島根','岡山','広島','山口','徳島','香川','愛媛','高知','福岡',
        '佐賀','長崎','熊本','大分','宮崎','鹿児島','沖縄'
    ];

    private $genres = ['寿司', 'ラーメン', '焼肉', '居酒屋', 'イタリアン', 'カフェ', '和食', '洋食', '韓国料理'];


    public function index()
    {
        $restaurants = Restaurant::all();
        $userId = Auth::id();

        $favorites = Restaurant::whereHas('favoriteUsers',function($query) use($userId) {
            $query->where('user_id',$userId);
        })->get();

        $favoriteIds = $favorites->pluck('id')->toArray();

        return view('user/index',[
            'areas' => $this->areas,
            'genres' => $this->genres,
            'restaurants' => $restaurants,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function search(Request $request)
    {
        $restaurants = Restaurant::query()
                    ->AreaSearch($request->area)
                    ->GenreSearch($request->genre)
                    ->KeywordSearch($request->keyword)
                    ->get();

        $userId = Auth::id();

        $favorites = Restaurant::whereHas('favoriteUsers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        $favoriteIds = $favorites->pluck('id')->toArray();

        return view('user/index',[
            'restaurants' => $restaurants,
            'favoriteIds' => $favoriteIds,
            'areas' => $this->areas,
            'genres' => $this->genres,
        ]);
    }

    public function favorite($id)
    {
        $userId = Auth::id();

        $favorite = Favorite::where('user_id', $userId)
            ->where('restaurant_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back();
        } else {
            Favorite::create([
                'user_id' => $userId,
                'restaurant_id' => $id,
            ]);
        }
        return redirect()->back();
    }

    public function detail($id)
    {
        $restaurant = Restaurant::find($id);

        $numbers = ['1','2','3','4','5','6','7','8','9','10'];

        return view('user/detail', compact('restaurant','numbers'));
    }

    public function store(ReservationRequest $request)
    {
        $userId = Auth::id();
        $restaurantId = $request->restaurant_id;

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $time = Carbon::parse($request->time)->format('H:i:s');
        $dateTime = $date . ' ' . $time;

        Reservation::create([
            'user_id' => $userId,
            'restaurant_id' => $restaurantId,
            'date' => $dateTime,
            'number' => $request->number,
        ]);

        return redirect()->route('done');
    }

    public function show()
    {
        $userId = Auth::id();

        $numbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];

        $reservations = Reservation::where('user_id', $userId)
            ->where('status', '予約済み')
            ->with('restaurant')
            ->orderBy('date','asc')->get();

        $restaurants = Restaurant::whereHas(
            'favoriteUsers',
            function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }
        )->get();

        return view('user/my_page', compact('restaurants', 'reservations','numbers'));
    }

    public function delete($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->route('my-page')->with('error', '該当する予約情報はありません。');
        }

        $reservation->delete();

        return redirect()->route('my-page')->with('success', '予約のキャンセルが完了しました。');
    }

    public function update(ReservationUpdateRequest $request , $id)
    {
        $userId = Auth::id();
        $restaurantId = $request->input('restaurant_id');

        $date = Carbon::parse($request->input('date_' . $id))->format('Y-m-d');
        $time = Carbon::parse($request->input('time_' .$id))->format('H:i:s');
        $dateTime = $date . ' ' . $time;

        $reservation =Reservation::find($id);

        if (!$reservation) {
            return back()->with('error', '予約が見つかりませんでした')->withInput();
        }

        $reservation->update([
            'date' => $dateTime,
            'number' => $request->input('number_' . $id),
            'user_id' => $userId,
            'restaurant_id' => $restaurantId,
        ]);

        return redirect()->route('my-page')->with('success', '予約内容が更新されました。');
    }

    public function showPublicMenu()
    {
        return view('user/public_menu');
    }

    public function showUserMenu()
    {
        return view('user/user_menu');
    }

    public function showThanks()
    {
        return view('auth/thanks');
    }

    public function showDone()
    {
        return view('user/done');
    }


}
