<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Faskes;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $wilayah = Desa::with(['kasusCovid' => function ($query) {
            $query->orderBy('suspek', 'desc');
        }])->get();

        $faskes = Faskes::with(['type', 'fasilitas'])->get();
        return view('home', compact([
            'wilayah',
            'faskes',
        ]));
    }

    function caseProvince()
    {
        $provinsi = Provinsi::with([
            'kabupaten.kecamatan.desa.kasusCovid' => function ($query) {
                $query->orderBy('suspek', 'desc');
            }
        ])->get();

        $sumByProvinsi = [];

        foreach ($provinsi as $prov) {
            $totalSuspek = 0;
            $totalDirawat = 0;
            $totalSembuh = 0;
            $totalMeninggal = 0;
            $i = 0;

            foreach ($prov->kabupaten as $kab) {
                foreach ($kab->kecamatan as $kec) {
                    foreach ($kec->desa as $des) {
                        foreach ($des->kasusCovid as $kasus) {
                            $i += 1;
                            $totalSuspek += $kasus->suspek;
                            $totalDirawat += $kasus->dirawat;
                            $totalSembuh += $kasus->sembuh;
                            $totalMeninggal += $kasus->meninggal;
                        }
                    }
                }
            }

            array_push($sumByProvinsi, [
                'nama' => $prov->nama,
                'total_suspek' => $totalSuspek,
                'total_dirawat' => $totalDirawat,
                'total_sembuh' => $totalSembuh,
                'total_meninggal' => $totalMeninggal,
                'geojson' => $prov->geojson
            ]);
        }

        return response()->json($sumByProvinsi);
    }

    function caseRegency()
    {
        $kabupaten = Kabupaten::with([
            'kecamatan.desa.kasusCovid' => function ($query) {
                $query->orderBy('suspek', 'desc');
            }
        ])->get();

        $sumByKabupaten = [];


        foreach ($kabupaten as $kab) {
            $totalSuspek = 0;
            $totalDirawat = 0;
            $totalSembuh = 0;
            $totalMeninggal = 0;
            $i = 0;

            foreach ($kab->kecamatan as $kec) {
                foreach ($kec->desa as $des) {
                    foreach ($des->kasusCovid as $kasus) {
                        $i += 1;
                        $totalSuspek += $kasus->suspek;
                        $totalDirawat += $kasus->dirawat;
                        $totalSembuh += $kasus->sembuh;
                        $totalMeninggal += $kasus->meninggal;
                    }
                }
            }

            array_push($sumByKabupaten, [
                'nama' => $kab->nama,
                'total_suspek' => $totalSuspek,
                'total_dirawat' => $totalDirawat,
                'total_sembuh' => $totalSembuh,
                'total_meninggal' => $totalMeninggal,
                'geojson' => $kab->geojson
            ]);
        }

        return response()->json($sumByKabupaten);
    }

    function caseDistrict()
    {
        $kecamatan = Kecamatan::with([
            'desa.kasusCovid' => function ($query) {
                $query->orderBy('suspek', 'desc');
            }
        ])->get();

        $sumByKecamatan = [];

        foreach ($kecamatan as $kec) {
            $totalSuspek = 0;
            $totalDirawat = 0;
            $totalSembuh = 0;
            $totalMeninggal = 0;
            $i = 0;

            foreach ($kec->desa as $des) {
                foreach ($des->kasusCovid as $kasus) {
                    $i += 1;
                    $totalSuspek += $kasus->suspek;
                    $totalDirawat += $kasus->dirawat;
                    $totalSembuh += $kasus->sembuh;
                    $totalMeninggal += $kasus->meninggal;
                }
            }

            array_push($sumByKecamatan, [
                'nama' => $kec->nama,
                'total_suspek' => $totalSuspek,
                'total_dirawat' => $totalDirawat,
                'total_sembuh' => $totalSembuh,
                'total_meninggal' => $totalMeninggal,
                'geojson' => $kec->geojson
            ]);
        }

        return response()->json($sumByKecamatan);
    }

    function caseVillage() {

        $desa = Desa::with(['kasusCovid' => function ($query) {
            $query->orderBy('suspek', 'desc');
        }])->get();

        $sumByDesa = [];

        foreach ($desa as $des) {
            $totalSuspek = 0;
            $totalDirawat = 0;
            $totalSembuh = 0;
            $totalMeninggal = 0;
            $i = 0;

            foreach ($des->kasusCovid as $kasus) {
                $totalSuspek += $kasus->suspek;
                $totalDirawat += $kasus->dirawat;
                $totalSembuh += $kasus->sembuh;
                $totalMeninggal += $kasus->meninggal;
            }

            array_push($sumByDesa, [
                'nama' => $des->nama,
                'total_suspek' => $totalSuspek,
                'total_dirawat' => $totalDirawat,
                'total_sembuh' => $totalSembuh,
                'total_meninggal' => $totalMeninggal,
                'geojson' => $des->geojson
            ]);
        }

        return response()->json($sumByDesa);
        
    }
}
