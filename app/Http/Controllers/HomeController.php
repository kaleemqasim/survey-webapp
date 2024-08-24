<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $completedSurveys = DB::table('survey_responses')
            ->select(DB::raw('COUNT(*) as count'), DB::raw('MONTH(created_at) as month'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->whereYear('created_at', Carbon::now()->year)
            ->pluck('count', 'month')
            ->toArray();

        // Initialize chart data with zeroes for all months
        $chartData = array_fill(1, 12, 0);

        // Fill chart data with actual survey counts
        foreach ($completedSurveys as $month => $count) {
            $chartData[$month] = $count;
        }

        // Convert the data into a JSON-friendly format
        $chartData = array_values($chartData);
        return view('home', compact('chartData'));
        // return view('home');
    }

    public function profile() {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    public function update_profile(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',

            // Validate other fields as necessary
            'bank_full_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'iban' => 'required|string|max:34',
            'swift_code' => 'required|string|max:11',
            'account_number' => 'required|string|max:34',
            'branch_code' => 'nullable|string|max:11',
            // Add any other validations for the fields
        ]);
    
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;

        $user->address = $request->address;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->postal_code = $request->postal_code;

    
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
    
        // Update bank details
        $user->full_name = $request->bank_full_name;
        $user->bank_name = $request->bank_name;
        $user->iban = $request->iban;
        $user->swift_code = $request->swift_code;
        $user->account_number = $request->account_number;
    
        $user->save();
    
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}
