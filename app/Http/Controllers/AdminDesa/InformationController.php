<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\Information;
use App\Models\Penduduk;
use App\Models\PerangkatDesa;
use App\Models\AsetDesa;
use App\Models\AsetTanahWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InformationController extends Controller
{
    /**
     * Display informasi dan statistik desa
     */
    public function index()
    {
        // Ambil informasi yang sudah dipublikasikan
        $informations = Information::published()
            ->with('creator')
            ->orderBy('published_at', 'desc')
            ->paginate(5);

        // Ambil data statistik desa berdasarkan user yang login
        $desaId = Auth::user()->desa_id;
        
        if (!$desaId) {
            return redirect()->route('admin-desa.dashboard')
                ->with('error', 'Desa tidak ditemukan untuk user ini.');
        }

        // Inisialisasi variabel dengan nilai default
        $totalPenduduk = 0;
        $pendudukPria = 0;
        $pendudukWanita = 0;
        $pendudukBerKTP = 0;
        $pendudukBelumKTP = 0;
        $totalPerangkat = 0;
        $totalAsetDesa = 0;
        $totalNilaiAsetDesa = 0;
        $totalAsetWarga = 0;
        $totalNilaiAsetWarga = 0;
        $klasifikasiUsia = collect();
        $topPekerjaan = collect();
        $perangkatPerJabatan = collect();

        try {
            // Statistik penduduk
            $totalPenduduk = Penduduk::where('desa_id', $desaId)->count();
            $pendudukPria = Penduduk::where('desa_id', $desaId)->where('jenis_kelamin', 'L')->count();
            $pendudukWanita = Penduduk::where('desa_id', $desaId)->where('jenis_kelamin', 'P')->count();
            $pendudukBerKTP = Penduduk::where('desa_id', $desaId)->where('memiliki_ktp', true)->count();
            $pendudukBelumKTP = $totalPenduduk - $pendudukBerKTP;

            // Statistik perangkat desa
            $totalPerangkat = PerangkatDesa::where('desa_id', $desaId)->count();

            // Statistik aset desa
            $totalAsetDesa = AsetDesa::where('desa_id', $desaId)->where('is_current', true)->count();
            $totalNilaiAsetDesa = AsetDesa::where('desa_id', $desaId)->where('is_current', true)
                ->get()
                ->sum(function($aset) {
                    return $aset->nilai_sekarang ?? $aset->nilai_perolehan ?? 0;
                });

            // Statistik aset tanah warga
            $totalAsetWarga = AsetTanahWarga::where('desa_id', $desaId)->count();
            $totalNilaiAsetWarga = AsetTanahWarga::where('desa_id', $desaId)
                ->get()
                ->sum(function($aset) {
                    return $aset->nilai_tanah;
                });

            // Klasifikasi usia penduduk
            // Pastikan semua penduduk memiliki klasifikasi usia
            $pendudukList = Penduduk::where('desa_id', $desaId)->get();
            foreach ($pendudukList as $penduduk) {
                if (!$penduduk->klasifikasi_usia) {
                    $penduduk->updateKlasifikasiUsia();
                }
            }
            
            $klasifikasiUsia = Penduduk::where('desa_id', $desaId)
                ->whereNotNull('klasifikasi_usia')
                ->selectRaw('klasifikasi_usia, COUNT(*) as jumlah')
                ->groupBy('klasifikasi_usia')
                ->orderBy('jumlah', 'desc')
                ->get();

            // Top pekerjaan
            $topPekerjaan = Penduduk::where('desa_id', $desaId)
                ->whereNotNull('pekerjaan')
                ->where('pekerjaan', '!=', '')
                ->selectRaw('pekerjaan, COUNT(*) as jumlah')
                ->groupBy('pekerjaan')
                ->orderBy('jumlah', 'desc')
                ->limit(10)
                ->get();

            // Grafik Perangkat Desa per Jabatan
            $perangkatPerJabatan = PerangkatDesa::where('desa_id', $desaId)
                ->where('status', 'aktif')
                ->selectRaw('jabatan, COUNT(*) as jumlah')
                ->groupBy('jabatan')
                ->get();
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in AdminDesa InformationController: ' . $e->getMessage());
            
            // Tetap tampilkan halaman dengan data default
            session()->flash('warning', 'Beberapa data statistik tidak dapat dimuat. Silakan periksa data penduduk dan aset desa.');
        }

        // Debug data yang akan dikirim ke view
        Log::info('Data untuk chart:', [
            'totalPenduduk' => $totalPenduduk,
            'pendudukPria' => $pendudukPria,
            'pendudukWanita' => $pendudukWanita,
            'pendudukBerKTP' => $pendudukBerKTP,
            'pendudukBelumKTP' => $pendudukBelumKTP,
            'klasifikasiUsia' => $klasifikasiUsia->toArray(),
            'topPekerjaan' => $topPekerjaan->toArray()
        ]);

        return view('admin-desa.information.index', compact(
            'informations',
            'totalPenduduk',
            'pendudukPria',
            'pendudukWanita',
            'pendudukBerKTP',
            'pendudukBelumKTP',
            'totalPerangkat',
            'totalAsetDesa',
            'totalNilaiAsetDesa',
            'totalAsetWarga',
            'totalNilaiAsetWarga',
            'klasifikasiUsia',
            'topPekerjaan',
            'perangkatPerJabatan'
        ));
    }

    /**
     * Display detail informasi
     */
    public function show(Information $information)
    {
        if ($information->status !== 'published') {
            abort(404);
        }

        $information->load('creator');
        return view('admin-desa.information.show', compact('information'));
    }
}
