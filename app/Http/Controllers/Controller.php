<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function dashboard()
    {
        $totalInvoice = Invoice::where('status', '!=', -1)->count();
        $totalTransaction = Transaction::count();
        $totalStudent = Student::where('status', '!=', 1)->count();
        $recentInvoices = Invoice::whereYear('updated_at', Carbon::now()->year)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
        $recentTransactions = Transaction::whereYear('updated_at', Carbon::now()->year)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
        return view('dashboard', [
            'totalInvoice' => $totalInvoice,
            'totalTransaction' => $totalTransaction,
            'totalStudent' => $totalStudent,
            'recentInvoices' => $recentInvoices,
            'recentTransactions' => $recentTransactions
        ]);
    }
}
