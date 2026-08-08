<?php

namespace App\Http\API;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Products\ProductRepositoryInterface;
use App\Http\Repositories\ProductGroups\ProductGroupRepositoryInterface;
use App\Http\Repositories\QrCode\QrCodeKartRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\UrunGrubu;
use App\Models\AnaGrup;

class APIController extends Controller
{

    private $productRepo;
    private $productGroupRepo;
    private $qrCodeRepo;

    public function __construct(
        ProductGroupRepositoryInterface $productGroupRepo,
        ProductRepositoryInterface $productRepo,
        QrCodeKartRepositoryInterface $qrCodeRepo
    ) {
        $this->productRepo = $productRepo;
        $this->productGroupRepo = $productGroupRepo;
        $this->qrCodeRepo = $qrCodeRepo;
    }

    public function AddWaiterCallToTable($qrCode)
    {
        // GÜVENLİK: Oturum süresi kontrolü
        \App\Models\Ayar::first();
        $scanTime = session('qr_scan_time');
        if (!$scanTime) {
            $scanTime = request()->input('qr_scan_time');
        }
        
        \Log::info("QR Scan Time Check in API: " . ($scanTime ?: "NULL"));
        $settings = \App\Models\Ayar::first();
        $timeoutMinutes = $settings ? ($settings->session_timeout_minutes ?? 120) : 120;
        $timeoutSeconds = $timeoutMinutes * 60;

        if (!$scanTime || (now()->timestamp - $scanTime) > $timeoutSeconds) {
            return response()->json(['success' => false, 'message' => 'Lütfen masadaki QR kodu tekrar okutun. (Güvenlik nedeniyle süreniz dolmuştur)'], 403);
        }

        return $this->qrCodeRepo->AddCallToTable($qrCode);
    }

    public function GetActiveWaiterCalls(Request $request)
    {
        $pendingCalls = \App\Models\QrCodeCagri::where('Status', 0)->get();
        if ($request->input('mark_as_pulled') == 1 && $pendingCalls->count() > 0) {
            \App\Models\QrCodeCagri::where('Status', 0)->update(['Status' => 1]);
        }
        return response()->json(['success' => true, 'cagrilar' => $pendingCalls]);
    }

    public function GetLocaleLang()
    {

        $ProductGroups = UrunGrubu::orderBy('Sirano')->get();
        return $ProductGroups;
    }
    public function GetAllProducts()
    {
        if (!Session::has('locale')) {
            $availablelanguages = ['en', 'ru', 'ua', 'tr', 'de'];
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            if (in_array($lang, $availablelanguages)) {
                App::setLocale($lang);
                session()->put('locale', $lang);
            } else {
                App::setLocale("en");
                session()->put('locale', "en");
            }
        } else {
            App::setLocale(session('locale'));
            session()->put('locale', session('locale'));
        }
        $products = $this->productRepo->GetAllProducts();

        $allProducts = $products->pluck("UrunAd")->toArray();
        $allProductsIds = $products->pluck("Urun_id")->toArray();
        return json_encode(array_merge($allProducts, $allProductsIds));
    }

    public function GetProductCategories($id)
    {
        if (!Session::has('locale')) {
            $availablelanguages = ['en', 'ru', 'ua', 'tr', 'de'];
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            if (in_array($lang, $availablelanguages)) {
                App::setLocale($lang);
                session()->put('locale', $lang);
            } else {
                App::setLocale("en");
                session()->put('locale', "en");
            }
        } else {
            App::setLocale(session('locale'));
            session()->put('locale', session('locale'));
        }

        $products = $this->productRepo->GetProductsBelongsToCategory($id);

        return view('parts.category', ['urunler' => $products]);
    }

    public function GetSubCategories($AnaGrup)
    {
        if (!Session::has('locale')) {
            $availablelanguages = ['en', 'ru', 'ua', 'tr', 'de'];
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

            if (in_array($lang, $availablelanguages)) {
                App::setLocale($lang);
                session()->put('locale', $lang);
            } else {
                App::setLocale("en");
                session()->put('locale', "en");
            }
        } else {
            App::setLocale(session('locale'));
            session()->put('locale', session('locale'));
        }

        $productsGroups = $this->productGroupRepo->GetSubMainGroups($AnaGrup);

        return view('parts.searchlist', ['ugrup' => $productsGroups]);
    }

    public function SaveImageFileToServer(Request $request, $gelensifre)
    {
        $tumu = substr($gelensifre, 0, 10);
        $x1   = substr($gelensifre, 0, 1) * 4;
        $x2   = substr($gelensifre, 2, 1) * 7;
        $x3   = substr($gelensifre, 3, 1) + 3;
        $x4   = substr($gelensifre, 5, 1) + 2;


        $islem = $tumu . $x1 . $x2 . $x3 . $x4;

        if ($gelensifre == $islem) {
            $json = $request->all();

            $data = base64_decode($json['base64']);
            $file = "assets/img/urunler/" . $json['filename'];

            $success = file_put_contents($file, $data);
            print $success ? $file : 'Unable to save the file.';
        }
    }

    public function Insert(Request $request, $table, $gelensifre)
    {
        $tumu = substr($gelensifre, 0, 10);
        $x1   = substr($gelensifre, 0, 1) * 4;
        $x2   = substr($gelensifre, 2, 1) * 7;
        $x3   = substr($gelensifre, 3, 1) + 3;
        $x4   = substr($gelensifre, 5, 1) + 2;


        $islem = $tumu . $x1 . $x2 . $x3 . $x4;

        if ($gelensifre == $islem) {
            $data = $request->all();


            //var_dump($data);

            $isok = 1;
            foreach ($data as $d) {
                $keys = array_keys($d);
                $values = array_values($d);

                //var_dump($values);

                if (!$this->DuplicateQuery($table, $keys, $values))
                    $isok = 0;
            }


            if ($isok)
                echo "OK";
            else
                echo "NO";

            // $keys = array_keys($data );
            // $values = array_values( $data );


            //var_dump($data);


        } else {
            return "api key hatalı!";
        }
    }

    public function AddTranslateToLanguageFile(Request $request, $gelensifre)
    {
        $tumu = substr($gelensifre, 0, 10);
        $x1   = substr($gelensifre, 0, 1) * 4;
        $x2   = substr($gelensifre, 2, 1) * 7;
        $x3   = substr($gelensifre, 3, 1) + 3;
        $x4   = substr($gelensifre, 5, 1) + 2;


        $islem = $tumu . $x1 . $x2 . $x3 . $x4;

        if ($gelensifre == $islem) {
            $json = $request->all();

            $langfile = $json['langfile'];
            $word = $json['word'];
            $translate = $json['trans'];

            $file = "../resources/lang/" . $langfile;
            $jsonString = file_get_contents($file);
            $data = json_decode($jsonString, true);

            $data[$word] = $translate;

            $data = json_encode($data, JSON_UNESCAPED_UNICODE);

            file_put_contents($file, $data);

            echo "OK";
        }
    }

    public function DuplicateQuery($table, $data, $values)
    {
        if (count($data) != count($values)) {
            echo "HATA : Data ve Values sayısı birbirine uyuşmuyor!";
            return null;
        }

        $query = "INSERT INTO " . $table . " (";

        for ($i = 0; $i < count($data); $i++) {
            if ($i != count($data) - 1)
                $query .= $data[$i] . ',';
            else
                $query .= $data[$i] . ') ';
        }

        $query .= "VALUES(";

        for ($i = 0; $i < count($values); $i++) {
            if ($i != count($values) - 1)
                $query .= "'" . $values[$i] . "',";
            else
                $query .= "'" . $values[$i] . "') ";
        }

        $query .= "ON DUPLICATE KEY UPDATE ";


        for ($i = 1; $i < count($data); $i++) {
            if ($i != count($data) - 1)
                $query .= $data[$i] . " = '" . $values[$i] . "', ";
            else
                $query .= $data[$i] . " = '" . $values[$i] . "'";
        }

        $query = DB::unprepared(DB::raw($query));

        return $query;
    }
}
