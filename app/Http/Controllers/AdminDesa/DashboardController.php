<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\AsetDesa;
use App\Models\AsetTanahWarga;
use App\Models\Penduduk;
use App\Models\PerangkatDesa;
use App\Exports\StatistikExport;
use App\Exports\StatistikPdfExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin desa
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Statistik Penduduk
        $totalPenduduk = Penduduk::where('desa_id', $desaId)->count();
        $pendudukPria = Penduduk::where('desa_id', $desaId)->where('jenis_kelamin', 'L')->count();
        $pendudukWanita = Penduduk::where('desa_id', $desaId)->where('jenis_kelamin', 'P')->count();
        $pendudukBerKTP = Penduduk::where('desa_id', $desaId)->where('memiliki_ktp', true)->count();
        $pendudukBelumKTP = Penduduk::where('desa_id', $desaId)->where('memiliki_ktp', false)->count();
        
        // Statistik Perangkat Desa
        $totalPerangkat = PerangkatDesa::where('desa_id', $desaId)->where('status', 'aktif')->count();
        
        // Statistik Aset
        $totalAsetDesa = AsetDesa::where('desa_id', $desaId)->count();
        $totalNilaiAsetDesa = AsetDesa::where('desa_id', $desaId)->sum('nilai_sekarang');
        
        $totalAsetWarga = AsetTanahWarga::where('desa_id', $desaId)->count();
        $totalNilaiAsetWarga = AsetTanahWarga::where('desa_id', $desaId)
            ->selectRaw('SUM(luas_tanah * nilai_per_meter) as total_nilai')
            ->value('total_nilai') ?? 0;
        
        // Klasifikasi Usia
        $klasifikasiUsia = Penduduk::where('desa_id', $desaId)
            ->selectRaw('klasifikasi_usia, COUNT(*) as jumlah')
            ->groupBy('klasifikasi_usia')
            ->get();
        
        // Top Pekerjaan
        $topPekerjaan = Penduduk::where('desa_id', $desaId)
            ->selectRaw('pekerjaan, COUNT(*) as jumlah')
            ->groupBy('pekerjaan')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get();
        
        // Grafik Perangkat Desa per Jabatan
        $perangkatPerJabatan = PerangkatDesa::where('desa_id', $desaId)
            ->where('status', 'aktif')
            ->selectRaw('jabatan, COUNT(*) as jumlah')
            ->groupBy('jabatan')
            ->get();
        
        // Grafik Perkembangan Bulanan
        $tahun = request('tahun', date('Y'));
        $grafikBulanan = [];
        
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $tanggal = Carbon::createFromDate($tahun, $bulan, 1);
            $grafikBulanan[] = [
                'bulan' => $tanggal->format('M'),
                'penduduk' => Penduduk::where('desa_id', $desaId)
                    ->whereYear('created_at', $tanggal->year)
                    ->whereMonth('created_at', $tanggal->month)
                    ->count(),
                'perangkat' => PerangkatDesa::where('desa_id', $desaId)
                    ->whereYear('created_at', $tanggal->year)
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

        return view('admin-desa.dashboard', compact(
            'totalPenduduk',
            'pendudukPria',
            'pendudukWanita',
            'pendudukBerKTP',
            'pendudukBelumKTP',
            'totalPerangkat',
            'totalAsetDesa',
            'totalAsetWarga',
            'totalNilaiAsetDesa',
            'totalNilaiAsetWarga',
            'klasifikasiUsia',
            'topPekerjaan',
            'perangkatPerJabatan',
            'grafikBulanan',
            'tahunList'
        ));
    }

    /**
     * Export statistik ke Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $desa_id = Auth::user()->desa_id;
        $desa = Auth::user()->desa;
        $tahun = $request->tahun ?? date('Y');
        
        $fileName = 'statistik_desa_' . Str::slug($desa->nama_desa) . '_' . $tahun . '.xlsx';
        
        return Excel::download(new StatistikExport($desa_id), $fileName);
    }

    /**
     * Export statistik ke PDF
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportPdf(Request $request)
    {
        $desa_id = Auth::user()->desa_id;
        $desa = Auth::user()->desa;
        $tahun = $request->tahun ?? date('Y');
        
        $fileName = 'statistik_desa_' . Str::slug($desa->nama_desa) . '_' . $tahun . '.pdf';
        
        return Excel::download(new StatistikPdfExport($desa_id), $fileName);
    }
}