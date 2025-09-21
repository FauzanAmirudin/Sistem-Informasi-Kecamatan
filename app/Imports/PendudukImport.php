<?php

namespace App\Imports;

use App\Models\Penduduk;
use App\Models\Desa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class PendudukImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $desaId;

    public function __construct($desaId = null)
    {
        $this->desaId = $desaId;
    }

    public function model(array $row)
    {
        // Jika desaId diberikan, gunakan itu. Jika tidak, cari berdasarkan nama
        if ($this->desaId) {
            $desa = Desa::find($this->desaId);
        } else {
            $desa = Desa::where('nama_desa', $row['desa'])->first();
        }
        
        if (!$desa) {
            throw new \Exception("Desa tidak ditemukan");
        }

        // Convert jenis kelamin
        $jenisKelamin = strtolower($row['jenis_kelamin']) == 'laki-laki' ? 'L' : 'P';

        return new Penduduk([
            'desa_id' => $desa->id,
            'nik' => $row['nik'],
            'nama_lengkap' => $row['nama_lengkap'],
            'jenis_kelamin' => $jenisKelamin,
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => Carbon::createFromFormat('d/m/Y', $row['tanggal_lahir']),
            'agama' => $row['agama'],
            'status_perkawinan' => $row['status_perkawinan'],
            'pekerjaan' => $row['pekerjaan'],
            'pendidikan_terakhir' => $row['pendidikan_terakhir'],
            'alamat' => $row['alamat'],
            'rt' => $row['rt'],
            'rw' => $row['rw'],
            'memiliki_ktp' => strtolower($row['memiliki_ktp']) == 'ya',
            'tanggal_rekam_ktp' => !empty($row['tanggal_rekam_ktp']) && $row['tanggal_rekam_ktp'] != '-' 
                ? Carbon::createFromFormat('d/m/Y', $row['tanggal_rekam_ktp']) 
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|size:16|unique:penduduks,nik',
            'nama_lengkap' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date_format:d/m/Y',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'required|string',
            'pendidikan_terakhir' => 'required|in:Tidak Sekolah,SD,SMP,SMA,D3,S1,S2,S3',
            'alamat' => 'required|string',
            'rt' => 'required|string',
            'rw' => 'required|string',
            'desa' => 'required|string',
            'memiliki_ktp' => 'required|in:Ya,Tidak',
        ];
    }
}