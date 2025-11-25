<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Admin\ServiceCategory;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\Booking;
use App\Models\Card;
use App\Models\Cart;
use App\Models\Courses;
use App\Models\Courses_Item;
use App\Models\Courses_Review;
use App\Models\Courses_Time;
use App\Models\Expenses;
use App\Models\Faq;
use App\Models\Order;
use App\Models\Policy;
use App\Models\Product;
use App\Models\Rateing;
use App\Models\Review;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Story;
use App\Models\SuccessStory;
use App\Models\Terms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Torann\GeoIP\Facades\GeoIP;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function cart()
    {
        return view('web.cart');
    }



    public function products(Request $request)
    {

        $query = Product::query()
            ->withAvg('review', 'rate');

        // إذا كان هناك طلب بحث
        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->where('title_en', 'like', '%' . $searchTerm . '%')
                ->orWhere('title_ar', 'like', '%' . $searchTerm . '%');
        }
        // dd($request->all());

        // الفرز
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'popularity':
                    $query->orderBy('review_avg_rate', 'desc'); // ترتيب حسب متوسط التقييم.
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
            }
        }

        // التقسيم (Pagination)
        $products = $query->paginate(20);

        return view('web.products', get_defined_vars());
    }
}
