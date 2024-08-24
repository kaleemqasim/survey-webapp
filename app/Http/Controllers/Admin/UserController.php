<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users', compact('users'));
    }

    public function withdraw_requests(Request $request) {
        $filter = $request->input('filter', 'pending');    
        $withdrawals = Withdrawal::where('status', $filter)
            ->with('user')
            ->get();
    
        return view('admin.withdraw_requests', compact('withdrawals', 'filter'));
    }

    public function getBankDetails($userId) {
        $user = User::findOrFail($userId);
    
        // Assuming bank details are stored in the User model
        return view('admin.partials.bank_details', compact('user'));
    }  
    
    public function updateWithdrawalStatus(Request $request, Withdrawal $withdrawal) {
        $withdrawal->status = $request->input('status');
        $withdrawal->save();
    
        return redirect()->route('admin.withdrawals.withdrawal_requests')->with('success', 'Withdrawal request updated successfully.');
    }
}
