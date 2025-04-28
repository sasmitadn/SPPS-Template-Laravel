@extends('app')

{{-- @section('title')
    Pembayaran
@endsection

@section('subtitle')
    Buat pembayaran untuk
@endsection --}}

@section('content')
    <div class="">
        <div class="d-flex flex-row justify-content-between align-items-center mb-3">
            <a href="{{ url()->previous() }}">
                <button class="btn btn-outline-primary mb-3" type="submit">
                    Kembali
                </button>
            </a>
        </div>
        <h4>{{ 'Nama : ' . $student->name }}</h4>
        <p>Daftar tagihan yang harus dibayar</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tagihan</th>
                    <th>Nominal Tagihan</th>
                    <th>Jenis Tagihan</th>
                    <th>Total Dibayar</th>
                    <th>Jumlah Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    @php
                        $filteredTransactions = $transactions->where('id_invoice', $invoice->id);
                        $totalPaid = $filteredTransactions->sum('amount'); // Hitung total pembayaran
                        $totalTransactions = $filteredTransactions->count(); // Hitung jumlah transaksi
                    @endphp
                    <tr>
                        <td>{{ $invoice->title }}</td>
                        <td>Rp {{ number_format($invoice->amount, 0, ",", ".") }}</td>
                        @if ($invoice->type == 1)
                            <td>Kredit</td>
                            <td>Rp {{ number_format($invoice->amount * $totalTransactions, 0, ",", ".") }}</td>
                            <td>{{ $totalTransactions . '/' . $invoice->credit_amount }}</td>
                        @else
                            <td>One-Time Payment</td>
                            <td>Rp {{ number_format($invoice->amount * $totalTransactions, 0, ",", ".") }}</td>
                            <td>{{ $totalTransactions . '/1' }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form class="mt-5" action="{{ route('transaction.store', [$student->code]) }}" method="POST">
            @csrf

            <h4>Lakukan Pembayaran</h4>
            <p>Pilih tagihan yang ingin dibayar</p>
            <div class="mb-3">
                <select class="form-select" name="id_invoice">
                    <option value="" selected>Silakan Pilih</option>
                    @foreach ($invoices as $item)
                        @php
                            $filteredTransactions = $transactions->where('id_invoice', $item->id);
                            $totalTransactions = $filteredTransactions->count();
                        @endphp
                        @if ($item->type == 1)
                            @if ($totalTransactions < $item->credit_amount)
                                <option value="{{ $item->id }}">
                                    {{ $item->title . ' -> Rp ' . number_format($item->amount, 0, ",", ".") }}</option>
                            @endif
                        @else
                            @if ($totalTransactions < 1)
                                <option value="{{ $item->id }}">
                                    {{ $item->title . ' -> Rp ' . number_format($item->amount, 0, ",", ".") }}</option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Bayar</button>
        </form>
    </div>
    </div>
@endsection
