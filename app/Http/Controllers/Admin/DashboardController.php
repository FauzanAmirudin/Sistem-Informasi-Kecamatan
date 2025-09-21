<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Penduduk;
use App\Models\PerangkatDesa;
use App\Models\AsetDesa;
use App\Models\AsetTanahWarga;
use App\Exports\StatistikExport;
use App\Exports\StatistikPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Export statistik ke Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $jenis = $request->jenis ?? 'penduduk';
        $fileName = 'statistik_' . $jenis . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new StatistikExport($jenis), $fileName);
    }
    
    /**
     * Export statistik ke PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $jenis = $request->jenis ?? 'penduduk';
        $export = new StatistikPdfExport($jenis);
        
        return $export->download();
    }
    
    public function index(Request $request)
    {
        // Ambil parameter tahun dari request, default ke tahun sekarang
        $tahun = $request->tahun ?? date('Y');
        
        // Statistik Umum
        $totalDesa = Desa::count();
        $totalPenduduk = Penduduk::count();
        $totalPerangkat = PerangkatDesa::current()->where('status', 'aktif')->count();
        $totalAsetDesa = AsetDesa::current()->count();
        $totalAsetWarga = AsetTanahWarga::count();

        // Statistik Penduduk
        $pendudukPria = Penduduk::where('jenis_kelamin', 'L')->count();
        $pendudukWanita = Penduduk::where('jenis_kelamin', 'P')->count();

        // Statistik KTP
        $pendudukBerKTP = Penduduk::where('memiliki_ktp', true)->count();
        $pendudukBelumKTP = Penduduk::where('memiliki_ktp', false)->count();

        // Klasifikasi Usia
        $klasifikasiUsia = Penduduk::selectRaw('klasifikasi_usia, COUNT(*) as jumlah')
            ->groupBy('klasifikasi_usia')
            ->get();

        // Top Pekerjaan
        $topPekerjaan = Penduduk::selectRaw('pekerjaan, COUNT(*) as jumlah')
            ->groupBy('pekerjaan')
            ->orderBy('jumlah', 'desc')
            ->limit(10)
            ->get();

        // Status Update Desa
        $desas = Desa::all();
        $statusUpdate = [
            'hijau' => 0,
            'kuning' => 0,
            'merah' => 0
        ];

        foreach ($desas as $desa) {
            $statusUpdate[$desa->status_update]++;
        }

        // Nilai Total Aset
        $totalNilaiAsetDesa = AsetDesa::current()->sum('nilai_sekarang');
        
        // Hitung total nilai aset tanah warga dengan mengalikan luas_tanah dan nilai_per_meter
        $totalNilaiAsetWarga = AsetTanahWarga::selectRaw('SUM(luas_tanah * nilai_per_meter) as total_nilai')->value('total_nilai') ?? 0;
        
        // Data untuk grafik nilai aset per desa
        $desas = Desa::all();
        $desaList = $desas->pluck('nama_desa')->toArray();
        
        // Nilai aset desa per desa
        $nilaiAsetDesa = [];
        foreach ($desas as $desa) {
            $nilaiAsetDesa[] = AsetDesa::where('desa_id', $desa->id)
                ->where('is_current', true)
                ->sum('nilai_sekarang');
        }
        
        // Nilai aset warga per desa
        $nilaiAsetWarga = [];
        foreach ($desas as $desa) {
            $nilaiAsetWarga[] = AsetTanahWarga::where('desa_id', $desa->id)
                ->selectRaw('SUM(luas_tanah * nilai_per_meter) as total_nilai')
                ->value('total_nilai') ?? 0;
        }

        // Data untuk grafik bulanan berdasarkan tahun yang dipilih
        $grafikBulanan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $tanggal = Carbon::createFromDate($tahun, $bulan, 1);
            $grafikBulanan[] = [
                'bulan' => $tanggal->format('M Y'),
                'penduduk' => Penduduk::whereYear('created_at', $tanggal->year)
                    ->whereMonth('created_at', $tanggal->month)
                    ->count(),
                'perangkat' => PerangkatDesa::whereYear('created_at', $tanggal->year)
                    ->whereMonth('created_at', $tanggal->month)
                    ->count(),
            ];
        }
        
        // Daftar tahun untuk filter grafik
        $tahunList = [];
        $tahunAwal = 2020; // Tahun awal data
        $tahunSekarang = date('Y');
        for ($tahun = $tahunSekarang; $tahun >= $tahunAwal; $tahun--) {
            $tahunList[] = $tahun;
        }

        return view('admin.dashboard', compact(
            'totalDesa',
            'totalPenduduk',
            'totalPerangkat',
            'totalAsetDesa',
            'totalAsetWarga',
            'pendudukPria',
            'pendudukWanita',
            'pendudukBerKTP',
            'pendudukBelumKTP',
            'klasifikasiUsia',
            'topPekerjaan',
            'statusUpdate',
            'totalNilaiAsetDesa',
            'totalNilaiAsetWarga',
            'grafikBulanan',
            'tahunList',
            'desaList',
            'nilaiAsetDesa',
            'nilaiAsetWarga'
        ));
    }

    public function monitoring()
    {
        $desas = Desa::with(['penduduks', 'perangkatDesas'])->get();

        return view('admin.monitoring', compact('desas'));
    }

    public function statistik()
    {
        // Statistik Detail per Desa
        $statistikPerDesa = Desa::with(['penduduks', 'perangkatDesas', 'asetDesas', 'asetTanahWargas'])
            ->get()
            ->map(function ($desa) {
                return [
                    'nama_desa' => $desa->nama_desa,
                    'total_penduduk' => $desa->penduduks->count(),
                    'penduduk_pria' => $desa->penduduks->where('jenis_kelamin', 'L')->count(),
                    'penduduk_wanita' => $desa->penduduks->where('jenis_kelamin', 'P')->count(),
                    'total_perangkat' => $desa->perangkatDesas->where('status', 'aktif')->count(),
                    'total_aset' => $desa->asetDesas->where('is_current', true)->count(),
                    'nilai_aset' => $desa->asetDesas->where('is_current', true)->sum('nilai_sekarang'),
                    'status_update' => $desa->status_update,
                ];
            });

        // Grafik Perbandingan Antar Desa
        $grafikPerbandingan = [
            'labels' => $statistikPerDesa->pluck('nama_desa')->toArray(),
            'penduduk' => $statistikPerDesa->pluck('total_penduduk')->toArray(),
            'perangkat' => $statistikPerDesa->pluck('total_perangkat')->toArray(),
            'aset' => $statistikPerDesa->pluck('total_aset')->toArray(),
        ];

        // Klasifikasi Usia
        $klasifikasiUsia = Penduduk::selectRaw('klasifikasi_usia, COUNT(*) as jumlah')
            ->groupBy('klasifikasi_usia')
            ->get();

        // Top Pekerjaan
        $topPekerjaan = Penduduk::selectRaw('pekerjaan, COUNT(*) as jumlah')
            ->groupBy('pekerjaan')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get();

        // Data Pendidikan
        $pendidikanData = Penduduk::selectRaw('pendidikan_terakhir, COUNT(*) as jumlah')
            ->groupBy('pendidikan_terakhir')
            ->orderBy('jumlah', 'desc')
            ->get();

        // Statistik Aset
        $totalAsetDesa = AsetDesa::current()->count();
        $totalNilaiAsetDesa = AsetDesa::current()->sum('nilai_sekarang');
        
        $totalAsetWarga = AsetTanahWarga::count();
        $totalNilaiAsetWarga = AsetTanahWarga::selectRaw('SUM(luas_tanah * nilai_per_meter) as total_nilai')->value('total_nilai') ?? 0;

        return view('admin.statistik', compact(
            'statistikPerDesa', 
            'grafikPerbandingan',
            'klasifikasiUsia',
            'topPekerjaan',
            'pendidikanData',
            'totalAsetDesa',
            'totalNilaiAsetDesa',
            'totalAsetWarga',
            'totalNilaiAsetWarga'
        ));
    }
}