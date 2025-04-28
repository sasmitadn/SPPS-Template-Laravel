<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('status', '!=', -1)->get();
        return view('invoice', [
            'invoices' => $invoices
        ]);
    }

    public function add()
    {
        return view('invoice-add');
    }

    public function edit($id)
    {
        $data = Invoice::find($id);
        return view('invoice-edit', [
            'data' => $data
        ]);
    }

    public function delete($id)
    {
        $data = Invoice::find($id);
        $data->status = -1;
        $data->save();
        return back()->with('success', 'Invoice berhasil dihapus');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|integer',
            'credit_amount' => 'nullable|integer|required_if:type,1|min:2'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $data = new Invoice();
        $data->title = $request->title;
        $data->amount = $request->amount;
        $data->type = $request->type;
        $data->credit_amount = $request->credit_amount == null ? 0 : $request->credit_amount;
        $data->status = $request->status;
        $data->save();
        return back()->with('success', 'Invoice berhasil dibuat.');
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ]);
        if ($validator->fails()) return back()->withErrors($validator)->withInput();
        $data = Invoice::find($id);
        $data->title = $request->title;
        $data->status = $request->status;
        $data->save();
        return back()->with('success', 'Invoice berhasil diperbarui.');
    }
}
