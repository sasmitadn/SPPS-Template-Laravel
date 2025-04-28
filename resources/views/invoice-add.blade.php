@extends('app')

@section('content')
    <div class="">
        <div class="d-flex flex-row justify-content-between align-items-center mb-5">
            <a href="{{ route('invoice.index') }}">
                <button class="btn btn-outline-primary mb-3" type="submit">Kembali</button>
            </a>
        </div>
        <form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data"
            class="d-flex flex-column justify-content-center">
            @csrf

            <div class="mb-3">
                <label for="" class="form-label">Nama Tagihan</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                @error('title')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Nominal Tagihan</label>
                <input type="text" class="form-control" name="amount" value="{{ old('amount') }}">
                @error('amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Jumlah Tagihan Harus Dibayar Sebanyak</label>
                <input type="number" class="form-control" placeholder="0 Kali" name="credit_amount" value="{{ old('credit_amount') }}">
                @error('credit_amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Pilih Jenis Tagihan</label>
                <select class="form-select" name="type">
                    <option value="1" @if (old('type') == '1') selected @endif>Kredit</option>
                    <option value="2" @if (old('type') == '2') selected @endif>One-Time Payment</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Status Tagihan</label>
                <select class="form-select" name="status">
                    <option value="1" @if(old('status') == '1') selected @endif >Aktif</option>
                    <option value="0" @if(old('status') == '0') selected @endif >Tidak Aktif</option>
                </select>
            </div>
            <button class="btn btn-primary my-5" type="submit">Simpan Sekarang</button>
        </form>
    </div>
@endsection
