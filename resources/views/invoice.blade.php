@extends('app')

@section('title')
    Pengaturan Tagihan
@endsection

@section('subtitle')
    Tentukan tagihan apa saja yang harus dibayar Siswa
@endsection

@section('content')
    <div class="">
        <div class="d-flex flex-row justify-content-end align-items-center">
            <a href="{{ route('export.invoice') }}">
                <button class="btn btn-outline-primary align-items-center whitespace-nowrap mb-3" id="exportBtn">Export Data</button>
            </a>
            <a href="{{ route('invoice.add') }}">
                <button class="btn btn-outline-primary mb-3 ms-3" type="submit">Tambah Tagihan</button>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table w-100">
                <thead>
                    <tr>
                        <th>Nama Tagihan</th>
                        <th>Jumlah</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Lainya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>Rp {{ number_format($item->amount, 0, ",", ".") }}</td>
                            <td>
                                @if ($item->type == 2)
                                    One-Time Payment
                                @endif
                                @if ($item->type == 1)
                                    Kredit
                                @endif
                            </td>
                            <td>
                                @if ($item->status == 1)
                                    Aktif
                                @endif
                                @if ($item->status == 0)
                                    Tidak Aktif
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <form action="{{ route('invoice.edit', ['id' => $item->id]) }}" method="get">
                                    @csrf

                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </form>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{$item->id}}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>                              
                        </tr>

                        <div class="modal fade" id="deleteModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Invoice</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            Yakin ingin menghapus {{ $item->title }} dari sistem? <br><br>
                                            <b>Invoice akan diletakan di tempat sampah agar tidak mengganggu transaksi yang sudah dibuat</b>
                                        </p>                     
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('invoice.delete', [$item->id]) }}" method="post">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary">Hapus Sekarang</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
