@extends('app')

@section('content')
    <div class="">
        <div class="d-flex flex-row justify-content-between align-items-center mb-5">
            <a href="{{ route('student.index') }}">
                <button class="btn btn-outline-primary mb-3" type="submit">Kembali</button>
            </a>
        </div>
        <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data"
            class="d-flex flex-column justify-content-center">
            @csrf

            <div class="mb-3">
                <label for="" class="form-label">Kode Siswa</label>
                <input type="text" class="form-control" name="code" value="{{ old('code') }}">
                @error('code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Nama Siswa</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Kelas</label>
                <input type="text" class="form-control" name="class" value="{{ old('class') }}">
                @error('class')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Tanggal Mendaftar</label>
                <input type="date" class="form-control" name="created_at" value="{{ old('created_at') }}">
                @error('created_at')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 mt-5">
                <h4>Pilih Invoice</h4>
                <p>Tentukan tagihan untuk Siswa ini</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Tagihan</th>
                            <th>Nominal</th>
                            <th>Jenis</th>
                            <th>Jumlah Bayar</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <input type="checkbox" name="invoices[]" value="{{ $invoice->id }}">
                                </td>
                                <td>{{ $invoice->title }}</td>
                                <td>Rp {{ number_format($invoice->amount, 0, ",", ".") }}</td>
                                <td>{{ $invoice->type == 1 ? 'Kredit' : 'One-Time Payment' }}</td>
                                <td>
                                    @if ($invoice->type == 1)
                                        {{ $invoice->credit_amount . ' Kali Bayar' ?? '-' }}
                                    @else
                                        1 Kali Bayar
                                    @endif
                                </td>
                                <td>
                                    @if ($invoice->credit_amount > 0)
                                        Rp {{ number_format($invoice->amount * $invoice->credit_amount, 0, ",", ".") }}
                                    @else
                                        Rp {{ number_format($invoice->amount, 0, ",", ".") }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button class="btn btn-primary my-5" type="submit">Simpan Sekarang</button>
        </form>
    </div>
@endsection
