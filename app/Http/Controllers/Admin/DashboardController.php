<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\VisitorTrack;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::all()->count();
        $totalUsers = User::all()->count();
        $totalReviews = ProductReview::all()->count();
        $latestOrder = Order::with('user', 'payment', 'items')->latest()->first();
        $totalSale = Order::sum('total_amount');
        // Get the start and end dates for the last month
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Get the count of orders from the last month
        $totalOrderMonthly = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $totalRevanue = Payment::where('payment_status', 'success')->sum('total_amount');
        // $latestPayment = Payment::with('order:id,order_num,user_id', 'order.user:id,name')->latest()->first();
        $latestOrders = Order::with('user')->latest()->take(10)->get();
        $latestPayment10 = Payment::with('order:id,order_num,user_id', 'order.user:id,name')->latest()->take(10)->get();
        $totalVisitor = VisitorTrack::all()->count();
        $dailyVisitor = VisitorTrack::whereDate('created_at', Carbon::today())->count();

        return view('admin.index', compact('totalOrders', 'totalUsers', 'totalReviews', 'latestOrder', 'totalVisitor', 'dailyVisitor', 'latestPayment10', 'latestOrders', 'totalSale', 'totalRevanue', 'totalOrderMonthly'));
    }

    public function profile()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'two_factor_auth'=> 'required|boolean'
        ]);
        $user = Auth::guard('admin')->user();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->two_factor_auth = $request->two_factor_auth;
        $user->save();
        return redirect()->back()->with('success', __('messages.profile_updated'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', 'min:8']
        ]);
        $user = Auth::guard('admin')->user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = bcrypt($request->password);
            $user->save();
            return redirect()->back()->with('success', __('messages.password_updated'));
        }
        return redirect()->back()->withErrors(['Incorrect Password']);
    }

    public function uploadEditorMedia(Request $request)
    {

        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move('uploads/media', $fileName);

            $url = asset('uploads/media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }
}
