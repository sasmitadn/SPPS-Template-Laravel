<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\RedisJob;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('search')) {
            $search = $request->search;
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }

        $students = $query->get();
        return view('student', [
            'students' => $students
        ]);
    }

    public function add()
    {
        $invoices = Invoice::where('status', 1)->get();
        return view('student-add', [
            'invoices' => $invoices
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:students,code',
            'name' => 'required',
            'class' => 'required',
            'created_at' => 'required',
            'invoices' => 'nullable|array',
            'invoices.*' => 'integer|exists:invoices,id'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $data = new Student();
        $data->code = $request->code;
        $data->name = $request->name;
        $data->class = $request->class;
        $data->created_at = $request->created_at;
        $data->selected_invoices = json_encode($request['invoices'] ?? []);
        $data->save();
        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Student::find($id);
        $invoices = Invoice::where('status', 1)->get();
        $savedInvoices = $data->selected_invoices;
        return view('student-edit', [
            'data' => $data,
            'invoices' => $invoices,
            'savedInvoices' => $savedInvoices
        ]);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'class' => 'required',
            'created_at' => 'required',
            'invoices' => 'nullable|array',
            'invoices.*' => 'integer|exists:invoices,id'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $data = Student::findOrFail($id);
        $data->name = $request->name;
        $data->class = $request->class;
        $data->created_at = $request->created_at;
        $data->selected_invoices = json_encode($request['invoices'] ?? []);
        $data->save();
        return back()->with('success', 'Data berhasil di update.');
    }

    public function delete($id)
    {
        $student = Student::findOrFail($id);
        Transaction::where('id_student', $student->code)->delete();
        $student->delete();
        return back()->with('success', 'Siswa dan semua data nya berhasil dihapus.');
    }
}
