@extends('app')

@section('title')
    Tutup Buku
@endsection

@section('subtitle')
    Menu tutup buku seluruh siswa
@endsection

@section('content')
    <div class="">
        <div class="d-flex flex-row justify-content-between align-items-center mt-4">
            <form class="input-group mb-3 w-25 pe-2" action="{{ route('closebook.index') }}" method="GET">
                <input type="text" class="form-control" placeholder="Cari nama atau ID Siswa" name="search" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Cari</button>
            </form>
            <div class="d-flex flex-row justify-content-end align-items-center mb-3">
                <form class="input-group pe-2" action="{{ route('closebook.index') }}" method="GET">
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Lunas</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Filter Data</button>
                </form>
                <a href="{{ route('export') }}">
                    <button class="btn btn-outline-primary align-items-center whitespace-nowrap" id="exportBtn">Export Data</button>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table w-100">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Total Tagihan</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->class }}</td>
                            <td>{{ "Rp " . number_format($item->total_tagihan, 0, ",", ".") }}</td>
                            <td>{{ "Rp " . number_format($item->total_bayar, 0, ",", ".") }}</td>
                            <td>
                                @if ($item->status == 1)
                                    <span class="status-success">Lunas</span>
                                @else
                                    <span class="status-pending">Belum Lunas</span>
                                @endif
                            </td>                   
                        </tr>

                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Siswa</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            Yakin ingin menghapus {{ $item->name }} dari sistem? <br><br>
                                            <b>Semua data transaksi dari siswa ini juga akan dihapus</b>
                                        </p>                     
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('student.delete', [$item->code]) }}" method="post">
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


    <script>
        document.getElementById("exportBtn").addEventListener("click", function(event) {
            event.preventDefault();
            let status = document.querySelector("select[name='status']").value;
            let url = "{{ route('export') }}" + "?status=" + encodeURIComponent(status);
            window.location.href = url; // Redirect ke URL dengan status
        });
    </script>
@endsection
