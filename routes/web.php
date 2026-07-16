<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UrunGrubuController;
use App\Http\Controllers\UrunKartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\MenuController;

// Eski Ana Sayfa
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/{qrcode}', [MainController::class, 'index'])->where('qrcode', '[0-9]+')->name('homepage.qr');

// Yeni Modern QR Menü
Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::redirect('/index.php', '/');
Route::get('/product/{id}', [MainController::class, 'showproduct'])->name('product');
Route::get('/istek', [MainController::class, 'istek']);
Route::post('/istek', [MainController::class, 'UserForm'])->name('validate.form');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('categories', UrunGrubuController::class);
    Route::resource('products', UrunKartController::class);
});

Route::get('/otonom-rontgen', function () {
    $klasorYolu = storage_path('app/public/products');
    $rapor = "<h3>Sistem Röntgen Raporu</h3>";
    $rapor .= "<b>PHP'nin Baktığı Fiziksel Klasör:</b> " . $klasorYolu . "<br><br>";

    if (!is_dir($klasorYolu)) {
        return $rapor . "<span style='color:red;'>HATA: Sistem bu klasörü bulamıyor! Klasör yok.</span>";
    }

    $dosyalar = scandir($klasorYolu);
    $temizDosyalar = array_diff($dosyalar, array('.', '..', '.DS_Store', '.gitignore'));
    
    $rapor .= "<b>Bu Klasörün İçindeki Dosya Sayısı:</b> " . count($temizDosyalar) . "<br>";
    $rapor .= "<b>İlk 5 Dosya:</b> " . implode(", ", array_slice($temizDosyalar, 0, 5)) . "<br><br>";

    $ilkUrun = \Illuminate\Support\Facades\DB::table('t_urunkart')->first();
    $rapor .= "<b>Veritabanındaki İlk Ürün ID:</b> " . $ilkUrun->id . "<br>";
    $rapor .= "<b>Sistemin Aradığı Dosya Adı:</b> " . $ilkUrun->id . ".jpg<br>";

    $beklenenYol = $klasorYolu . '/' . $ilkUrun->id . '.jpg';
    $rapor .= "<b>Bu Dosya Gerçekten Orada Mı?</b> " . (file_exists($beklenenYol) ? "<span style='color:green;'>EVET</span>" : "<span style='color:red;'>HAYIR</span>");

    return $rapor;
});

Route::get('/gorsel', function () {
    $products = \Illuminate\Support\Facades\DB::table('t_urunkart')->get();
    $bulunan = 0;
    $bulunamayan = 0;

    foreach ($products as $product) {
        // Doğru id değerlerini (337, 338 vb.) kullanıyoruz
        $dosyaAdiKucuk = $product->id . '.jpg';
        $dosyaAdiBuyuk = $product->id . '.JPG';
        
        // Laravel'in sorunlu sanal diskini atlayıp, mutlak MAMP fiziksel yolunu (storage_path) zorluyoruz
        $fizikselYolKucuk = storage_path('app/public/products/' . $dosyaAdiKucuk);
        $fizikselYolBuyuk = storage_path('app/public/products/' . $dosyaAdiBuyuk);
        
        $kaydedilecekYol = null;

        // Native PHP 'file_exists' komutuyla diskte donanımsal arama yapıyoruz
        if (file_exists($fizikselYolKucuk)) {
            $kaydedilecekYol = 'products/' . $dosyaAdiKucuk;
            $bulunan++;
        } elseif (file_exists($fizikselYolBuyuk)) {
            $kaydedilecekYol = 'products/' . $dosyaAdiBuyuk;
            $bulunan++;
        } else {
            $bulunamayan++;
        }

        // Bulunanları veritabanına işliyor, bulunamayanları null/boş bırakıyoruz
        \Illuminate\Support\Facades\DB::table('t_urunkart')
            ->where('id', $product->id)
            ->update(['UrunResimPath' => $kaydedilecekYol]);
    }
    
    return " Protokolü Tamamlandı: $bulunan adet fiziksel dosya zorla bağlandı. Bulunamayan $bulunamayan ürün boş bırakıldı.";
});
  