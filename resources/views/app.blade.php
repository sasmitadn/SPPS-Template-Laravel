<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPPS Tools</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('style/style.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.getElementById('success-popup')?.remove();
            }, 3000);
        });
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    @if (session('success'))
        <div id="success-popup" x-data="{ open: true }" x-show="open"
            class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            <span>{{ session('success') }}</span>
            <button @click="open = false" class="ml-2 font-bold">&times;</button>
        </div>
    @endif

    <!-- Mobile Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <i class="bi bi-bootstrap fs-4 me-2"></i>
            <span class="fs-5">SPPS Tools</span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <hr class="text-white my-2">
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="{{ is_active(['dashboard']) }}"><i
                            class="bi bi-house-door me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('student.index') }}" class="{{ is_active(['student.index']) }}"><i
                            class="bi bi-person me-2"></i> Siswa</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('invoice.index') }}" class="{{ is_active(['invoice.index']) }}"><i
                            class="bi bi-gear me-2"></i> Tagihan</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('transaction.index') }}" class="{{ is_active(['transaction.index']) }}"><i
                            class="bi bi-credit-card-2-front me-2"></i> Transaksi</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('closebook.index') }}" class="{{ is_active(['closebook.index']) }}"><i
                            class="bi bi-file-earmark-arrow-down me-2"></i> Tutup Buku</a>
                </li>
                <hr class="text-white my-2">
                <li class="nav-item mt-3">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar d-none d-md-block p-3">
        <a href="#" class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <i class="bi bi-bootstrap fs-4 me-2"></i>
            <span class="fs-5">SPPS Tools</span>
        </a>
        <hr class="text-white my-2">
        <a href="{{ route('dashboard') }}" class="{{ is_active(['dashboard']) }}"><i
                class="bi bi-house-door me-2"></i> Dashboard</a>
        <a href="{{ route('student.index') }}" class="{{ is_active(['student.index']) }}"><i
                class="bi bi-person me-2"></i> Siswa</a>
        <a href="{{ route('invoice.index') }}" class="{{ is_active(['invoice.index']) }}"><i
                class="bi bi-gear me-2"></i> Tagihan</a>
        <a href="{{ route('transaction.index') }}" class="{{ is_active(['transaction.index']) }}"><i
                class="bi bi-credit-card-2-front me-2"></i> Transaksi</a>
        <a href="{{ route('closebook.index') }}" class="{{ is_active(['closebook.index']) }}"><i
                class="bi bi-file-earmark-arrow-down me-2"></i> Tutup Buku</a>
        <hr class="text-white my-2">
        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>

    <div class="content">
        <div class="d-flex flex-row align-items-center pb-5">
            <!-- Mobile Button -->
            <button class="btn btn-primary d-md-none me-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <h2 class="fw-bold fs-3">@yield('title')</h2>
                <p>@yield('subtitle')</p>
            </div>
        </div>
        @yield('content')
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Logout</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Yakin ingin menghapus logout dari sesi ini? <br><br>
                        <b>Pastikan Anda mengingat email dan password</b>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="post">
                        @method('post')
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">Logout Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @yield('main')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
