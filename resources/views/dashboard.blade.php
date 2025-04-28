@extends('app')

@section('content')
    <div class="container" style="margin-top: -50px">
        <div class="row g-3">
            <!-- Invoice Summary -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Invoices</h5>
                        <h2 class="text-primary">{{ $totalInvoice }}</h2>
                        <p class="text-muted">Kredit & One-Time Payments</p>
                    </div>
                </div>
            </div>

            <!-- Transactions Summary -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Transaksi</h5>
                        <h2 class="text-success">{{ $totalTransaction }}</h2>
                        <p class="text-muted">Sudah Terbayar</p>
                    </div>
                </div>
            </div>

            <!-- Students Summary -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Siswa</h5>
                        <h2 class="text-warning">{{ $totalStudent }}</h2>
                        <p class="text-muted">Belum Lunas</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Recent Invoices -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Invoice Terbaru</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($recentInvoices as $item)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Invoice #{{ $item->id }}</span>
                                    <span class="badge bg-success">Rp {{ number_format($item->amount, 0, ",", ".") }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="d-block d-md-none mt-4"></div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">Transaksi Terbaru</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($recentTransactions as $item)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $item->student->name }} - Invoice #{{ $item->id_invoice }}</span>
                                    <span class="badge bg-success">Rp {{ number_format($item->invoice->amount, 0, ",", ".") }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
