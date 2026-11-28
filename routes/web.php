<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Landing page template (hero section)
Route::view('/landing', 'landing')->name('landing');

// SEO sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Katalog
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/courses/{course}', [CatalogController::class, 'show'])->name('catalog.show');

// Keranjang
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{course}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/{course}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout & pembayaran
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout.store');
Route::post('/payment/duitku/callback', [PaymentController::class, 'duitkuCallback'])->name('payment.duitku.callback');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');

// Download PDF (watermark)
Route::get('/courses/{course}/download', [DownloadController::class, 'download'])
    ->middleware('auth')
    ->name('course.download');
Route::get('/guest/download/{order}/{course}', [DownloadController::class, 'guestDownload'])
    ->middleware('signed')
    ->name('course.download.guest');

// Auth
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard user
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Halaman statis
Route::view('/syarat-ketentuan', 'pages.syarat-ketentuan')->name('page.terms');
Route::view('/kebijakan-refund', 'pages.refund')->name('page.refund');
Route::view('/kontak', 'pages.kontak')->name('page.contact');
Route::post('/kontak', [ContactController::class, 'submit'])->name('page.contact.submit');
Route::view('/tentang-kami', 'pages.tentang')->name('page.about');

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('courses', AdminCourseController::class)->except('show');
    Route::get('courses/{course}/preview', [AdminCourseController::class, 'preview'])->name('courses.preview');
    Route::get('courses/{course}/preview-watermark', [AdminCourseController::class, 'previewWatermark'])->name('courses.preview-watermark');
    Route::post('courses/preview-upload', [AdminCourseController::class, 'previewUpload'])->name('courses.preview-upload');
    Route::get('courses/export', [AdminCourseController::class, 'export'])->name('courses.export');
    Route::post('courses/import', [AdminCourseController::class, 'import'])->name('courses.import');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/confirm', [AdminOrderController::class, 'confirm'])->name('orders.confirm');
    Route::post('orders/{order}/reset-download', [AdminOrderController::class, 'resetDownload'])->name('orders.reset-download');

    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});


