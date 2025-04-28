<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CloseBookController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }

        if ($request->filled('status')) {
            $status = $request->status;
            $query->where('status', $status);
        }

        $students = $query->get();
        // Loop setiap student untuk mengambil invoice terkait
        foreach ($students as $student) {
            $selected_invoices = json_decode($student->selected_invoices, true) ?? [];
            $invoices = Invoice::whereIn('id', $selected_invoices)->get();
            $transactions = Transaction::where([
                ['id_student', '=', $student->code],
                ['status', '=', '1']
            ])->with('invoice')->get();

            $totalTagihan = 0;
            foreach ($invoices as $invoice) {
                if ($invoice->status != 1) continue;
                if ($invoice->type == 1) {
                    // credit
                    $totalTagihan += $invoice->amount * $invoice->credit_amount;
                } else {
                    $totalTagihan += $invoice->amount;
                }
            }

            $totalBayar = 0;
            foreach ($transactions as $transaction) {
                $totalBayar += $transaction->invoice->amount;
            }

            $student->total_tagihan = $totalTagihan;
            $student->total_bayar = $totalBayar;
        }

        return view('close', [
            'students' => $students
        ]);
    }
}
