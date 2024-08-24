<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Mail;
use App\Mail\WithdrawalRequest;
use DB;

class EarningsController extends Controller
{
    public function index() {
        $user = auth()->user();

        
        // Get earnings
        $earnings = DB::table('survey_responses')
            ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
            ->where('survey_responses.user_id', $user->id)
            ->select('surveys.title as survey_title', 'surveys.reward', 'survey_responses.created_at')
            ->get()
            ->map(function($earning) {
                return [
                    'type' => 'earning',
                    'title' => $earning->survey_title,
                    'amount' => $earning->reward,
                    'date' => Carbon::parse($earning->created_at)
                ];
            });

        // Get withdrawals
        $withdrawals = $user->withdrawals()
            ->select('amount', 'created_at')
            ->get()
            ->map(function($withdrawal) {
                return [
                    'type' => 'withdrawal',
                    'title' => 'Withdrawal of Earnings',
                    'amount' => $withdrawal->amount,
                    'date' => Carbon::parse($withdrawal->created_at)
                ];
            });

        // Combine and sort by date
        $transactions = $earnings->merge($withdrawals)->sortByDesc('date');

        // Calculate total earnings and current balance
        $totalEarnings = $earnings->sum('amount');
        $withdrawnAmount = $withdrawals->sum('amount');
        $currentBalance = $user->balance;


        return view('user.earnings', compact('transactions', 'totalEarnings', 'currentBalance'));
    }

    public function requestWithdrawal(Request $request)
    {
        $user = auth()->user();

        // Calculate current balance
        $totalEarnings = DB::table('survey_responses')
        ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
        ->where('survey_responses.user_id', $user->id)
        ->sum('surveys.reward');

        $balance = $user->balance;

        // Check if the balance is greater than 0 before allowing withdrawal
        if ($balance <= 0) {
            return redirect()->back()->withErrors(['error' => 'Insufficient balance to withdraw.']);
        }

        // Create a withdrawal request
        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $balance,
        ]);

        // Send an email with the withdrawal details
        // Mail::to(env('WITHDRAWAL_EMAIL', 'kaleem@gmail.com'))->send(new WithdrawalRequest($withdrawal, $user));

        return redirect()->back()->with('success', 'Withdrawal request submitted successfully.');
    }
}

