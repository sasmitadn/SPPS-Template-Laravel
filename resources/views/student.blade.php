@extends('app')

@section('title')
    Daftar Siswa
@endsection

@section('subtitle')
    Daftar seluruh siswa
@endsection

@section('content')
    <div class="">
        <div class="d-flex flex-row justify-content-end align-items-center">
            <form class="input-group mb-3 w-50 pe-2" action="{{ route('student.index') }}" method="GET">
                <input type="text" class="form-control" placeholder="Cari nama atau ID Siswa" name="search" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Cari</button>
            </form>
            <a href="{{ route('export.student') }}" class="pe-2">
                <button class="btn btn-outline-primary mb-3" type="submit">Export Data</button>
            </a>
            <a href="{{ route('student.add') }}">
                <button class="btn btn-outline-primary mb-3" type="submit">Tambah Siswa</button>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table w-100">
                <thead>
                    <tr>
                        <th>Kode Siswa</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                        <th>Lainya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $key => $item)
                        <tr>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->class }}</td>
                            <td>
                                @if ($item->status == 1)
                                    <span class="status-success">Lunas</span>
                                @else
                                    <span class="status-pending">Belum Lunas</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->translatedFormat('d F Y') }}</td>
                            <td class="d-flex gap-2">
                                <form action="{{ route('student.edit', ['id' => $item->code]) }}" method="get">
                                    @csrf

                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </form>
                                <form action="{{ route('transaction.add', ['id_student' => $item->code]) }}" method="get">
                                    @csrf

                                    <button type="submit" class="btn btn-dark btn-sm">
                                        <i class="bi bi-credit-card-2-back"></i>
                                    </button>
                                </form>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{$key}}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>                              
                        </tr>

                        <div class="modal fade" id="deleteModal-{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
@endsection
