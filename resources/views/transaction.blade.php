@extends('app')

@section('title')
    Transaksi
@endsection

@section('subtitle')
    Daftar seluruh transaksi
@endsection

@section('content')
    <div class="">
        <div class="d-flex flex-row justify-content-end align-items-center">
            <form class="input-group mb-3 w-50 pe-2" action="{{ route('transaction.index') }}" method="GET">
                <input type="text" class="form-control" placeholder="Cari nama atau ID Siswa" name="search" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Cari</button>
            </form>
            <a href="{{ route('export.invoice') }}">
                <button class="btn btn-outline-primary align-items-center whitespace-nowrap mb-3" id="exportBtn">Export Data</button>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table w-100">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Siswa</th>
                        <th>Invoice</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                        <th>Lainya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $item)
                        <tr>
                            <td>{{ $item->student->code }}</td>
                            <td>{{ $item->student->name }}</td>
                            <td>{{ $item->invoice->title }}</td>
                            <td>{{ 'Rp ' . number_format($item->invoice->amount, 0, ",", ".") }}</td>
                            <td>{{ $item->created_at->translatedFormat('d F Y') }}</td>
                            <td>
                                <a class="btn btn-success btn-sm" href="{{ route('transaction.receipt', $item->id) }}">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                <button class="btn btn-danger btn-sm d-none" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                  <i class="bi bi-trash"></i>
                                </button>
                            </td>                              
                        </tr>

                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Transaksi</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            Yakin ingin menghapus transaksi ini dari sistem? <br><br>
                                            <b>Aksi ini tidak dapat dibatalkan</b>
                                        </p>                     
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('transaction.delete', [$item->id]) }}" method="post">
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

    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleSelect" class="form-label">Pilih Invoice</label>
                        <select class="form-select" id="exampleSelect">
                          <option selected>Silakan Pilih</option>
                          <option value="1">Opsi 1</option>
                          <option value="2">Opsi 2</option>
                          <option value="3">Opsi 3</option>
                        </select>
                    </div>                      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.getElementById("exportBtn").addEventListener("click", function(event) {
            event.preventDefault();
            let status = document.querySelector("input[name='search']").value;
            let url = "{{ route('export.transaction') }}" + "?search=" + encodeURIComponent(status);
            window.location.href = url; // Redirect ke URL dengan status
        });
    </script>
@endsection
