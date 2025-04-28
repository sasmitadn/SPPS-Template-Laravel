<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query()
                    ->join('students', 'students.code', '=', 'transactions.id_student') // Join tabel student
                    ->select('transactions.*') // Pilih kolom transaction agar tidak bentrok
                    ->orderBy('students.name', 'asc'); // Urutkan by name

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
            });
        }

        $transactions = $query->with('student')->get();
        return view('transaction', [
            'transactions' => $transactions
        ]);
    }

    public function add($id_student)
    {
        $student = Student::find($id_student);
        $selected_invoices = json_decode($student->selected_invoices);
        $invoices = Invoice::whereIn('id', $selected_invoices)->get();
        $transactions = Transaction::where('id_student', $id_student)
            ->whereIn('id_invoice', $invoices->pluck('id')) // Ambil semua id dari invoice
            ->get();

        return view('payment', [
            'student' => $student,
            'invoices' => $invoices,
            'transactions' => $transactions
        ]);
    }

    public function store($id_student, Request $request)
    {
        $data = new Transaction();
        $data->id_invoice = $request->id_invoice;
        $data->id_student = $id_student;
        
        $student = Student::findOrFail($id_student);
        $invoice = Invoice::findOrFail($data->id_invoice);
        $selected_invoices = json_decode($student->selected_invoices);
        $transactions = Transaction::where('id_student', $id_student)->whereIn('id_invoice', $selected_invoices)->get();

        $totalDebit = Invoice::where('type', 2)->whereIn('id', $selected_invoices)->count();
        $totalCredit = Invoice::where('type', 1)->whereIn('id', $selected_invoices)->sum('credit_amount');
        $total = $totalDebit + $totalCredit;
        if (count($transactions) + 1 == $total) {
         // +1 krn transaksi saat ini belum disimpan
            $student->status = 1;
            $student->save();
        }

        $data->save();
        return redirect()->route('transaction.receipt', $data->id);
    }

    public function delete($id) {
        $data = Transaction::find($id);
        $student = Student::findOrFail($data->id_student);
        $selected_invoices = json_decode($student->selected_invoices);
        $transactions = Transaction::where('id_student', $student->code)->whereIn('id_invoice', $selected_invoices)->get();
        $totalDebit = Invoice::where('type', 2)->whereIn('id', $selected_invoices)->count();
        $totalCredit = Invoice::where('type', 1)->whereIn('id', $selected_invoices)->sum('credit_amount');
        $total = $totalDebit + $totalCredit;
        if (count($transactions) == $total) {
            $student->status = 0;
            $student->save();
        }
        $data->delete();
        return back()->with('success', 'Transaksi berhasil dihapus');
    }

    public function printReceipt($id) {
        $data = Transaction::findOrFail($id);
        
        $student = Student::findOrFail($data->id_student);
        $invoice = Invoice::findOrFail($data->id_invoice);
        $selected_invoices = json_decode($student->selected_invoices);
        $transactions = Transaction::where('id_student', $data->id_student)->whereIn('id_invoice', $selected_invoices)->get();

        $totalDebit = Invoice::where('type', 2)->whereIn('id', $selected_invoices)->count();
        $totalCredit = Invoice::where('type', 1)->whereIn('id', $selected_invoices)->sum('credit_amount');
        $total = $totalDebit + $totalCredit;
        return view('receipt', [
            'student' => $student,
            'transactions' => $transactions,
            'transaction' => $data,
            'total' => $total,
            'invoice' => $invoice
        ]);
    }
}
