<?php

use App\Exports\CloseExport;
use App\Exports\InvoiceExport;
use App\Exports\StudentsExport;
use App\Exports\TransactionExport;
use App\Http\Controllers\CloseBookController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('dashboard');
// })->name('dashboard');


Route::get('/login', [UserController::class, 'index'])->name('login');
Route::post('/login-now', [UserController::class, 'login'])->name('login-now');

Route::group(['middleware' => ['admin.auth']], function () {
    Route::get('/', [Controller::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    Route::get('/transaction/index', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transaction/add/{id_student}', [TransactionController::class, 'add'])->name('transaction.add');
    Route::post('/transaction/store/{id_student}', [TransactionController::class, 'store'])->name('transaction.store');
    Route::delete('/transaction/delete/{id}', [TransactionController::class, 'delete'])->name('transaction.delete');
    Route::get('/transaction/receipt/{id}', [TransactionController::class, 'printReceipt'])->name('transaction.receipt');


    Route::get('/student/index', [StudentController::class, 'index'])->name('student.index');
    Route::get('/student/add', [StudentController::class, 'add'])->name('student.add');
    Route::post('/student/store', [StudentController::class, 'store'])->name('student.store');
    Route::get('/student/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/student/update/{id}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/student/delete/{id}', [StudentController::class, 'delete'])->name('student.delete');


    Route::get('/invoice/index', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/add', [InvoiceController::class, 'add'])->name('invoice.add');
    Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/invoice/edit/{id}', [InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::put('/invoice/update/{id}', [InvoiceController::class, 'update'])->name('invoice.update');
    Route::delete('/invoice/delete/{id}', [InvoiceController::class, 'delete'])->name('invoice.delete');

    
    Route::get('/closebook/index', [CloseBookController::class, 'index'])->name('closebook.index');
    Route::get('/export-students', function (Request $request) {
        $status = $request->query('status', '');
        return Excel::download(new CloseExport($status), 'laporan_keuangan.xlsx');
    })->name('export');
    Route::get('/export-invoices', function (Request $request) {
        return Excel::download(new InvoiceExport, 'laporan_tagihan.xlsx');
    })->name('export.invoice');
    Route::get('/export-student', function (Request $request) {
        return Excel::download(new StudentsExport, 'laporan_siswa.xlsx');
    })->name('export.student');
    Route::get('/export-transaction', function (Request $request) {
        $search = $request->query('search', '');
        return Excel::download(new TransactionExport($search), 'laporan_transaksi.xlsx');
    })->name('export.transaction');
});
