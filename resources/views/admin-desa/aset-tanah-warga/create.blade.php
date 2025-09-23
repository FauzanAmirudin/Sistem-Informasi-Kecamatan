@extends('layouts.admin-desa')

@section('page-title', 'Tambah SPH Aset Tanah Warga')
@section('page-subtitle', 'Tambah data aset tanah warga')

@section('page-actions')
<a href="{{ route('admin-desa.aset-tanah-warga.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i>
    Kembali
</a>
@endsection

@section('admin-content')
<div class="card shadow-sm">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin-desa.aset-tanah-warga.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="desa_id" value="{{ auth()->user()->desa_id }}">
            
            <!-- Data Pemilik -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data Pemilik</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nama_pemilik" class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_pemilik') is-invalid @enderror" id="nama_pemilik" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required>
                            @error('nama_pemilik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nik_pemilik" class="form-label">NIK Pemilik <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik_pemilik') is-invalid @enderror" id="nik_pemilik" name="nik_pemilik" value="{{ old('nik_pemilik') }}" required maxlength="16" minlength="16">
                            @error('nik_pemilik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">NIK harus 16 digit</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data SPH -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data SPH</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nomor_sph" class="form-label">Nomor SPH <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor_sph') is-invalid @enderror" id="nomor_sph" name="nomor_sph" value="{{ old('nomor_sph') }}" required>
                            @error('nomor_sph')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_sph" class="form-label">Tanggal SPH <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_sph') is-invalid @enderror" id="tanggal_sph" name="tanggal_sph" value="{{ old('tanggal_sph') }}" required>
                            @error('tanggal_sph')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status_kepemilikan" class="form-label">Status Kepemilikan <span class="text-danger">*</span></label>
                            <select class="form-select @error('status_kepemilikan') is-invalid @enderror" id="status_kepemilikan" name="status_kepemilikan" required>
                                <option value="" selected disabled>Pilih Status Kepemilikan</option>
                                <option value="milik_sendiri" {{ old('status_kepemilikan') == 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                                <option value="warisan" {{ old('status_kepemilikan') == 'warisan' ? 'selected' : '' }}>Warisan</option>
                                <option value="hibah" {{ old('status_kepemilikan') == 'hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="jual_beli" {{ old('status_kepemilikan') == 'jual_beli' ? 'selected' : '' }}>Jual Beli</option>
                            </select>
                            @error('status_kepemilikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="bukti_kepemilikan" class="form-label">Bukti Kepemilikan (PDF) <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('bukti_kepemilikan') is-invalid @enderror" id="bukti_kepemilikan" name="bukti_kepemilikan" accept=".pdf" required>
                            @error('bukti_kepemilikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">File harus berformat PDF, maksimal 2MB</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tanah -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data Tanah</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="jenis_tanah" class="form-label">Jenis Tanah <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_tanah') is-invalid @enderror" id="jenis_tanah" name="jenis_tanah" required>
                                <option value="" selected disabled>Pilih Jenis Tanah</option>
                                <option value="tanah_kering" {{ old('jenis_tanah') == 'tanah_kering' ? 'selected' : '' }}>Tanah Kering</option>
                                <option value="tanah_sawah" {{ old('jenis_tanah') == 'tanah_sawah' ? 'selected' : '' }}>Tanah Sawah</option>
                                <option value="tanah_pekarangan" {{ old('jenis_tanah') == 'tanah_pekarangan' ? 'selected' : '' }}>Tanah Pekarangan</option>
                                <option value="tanah_perkebunan" {{ old('jenis_tanah') == 'tanah_perkebunan' ? 'selected' : '' }}>Tanah Perkebunan</option>
                            </select>
                            @error('jenis_tanah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="luas_tanah" class="form-label">Luas Tanah (m²) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('luas_tanah') is-invalid @enderror" id="luas_tanah" name="luas_tanah" value="{{ old('luas_tanah') }}" required>
                            @error('luas_tanah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nilai_per_meter" class="form-label">Nilai Per Meter (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('nilai_per_meter') is-invalid @enderror" id="nilai_per_meter" name="nilai_per_meter" value="{{ old('nilai_per_meter') }}" required>
                            @error('nilai_per_meter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="lokasi" class="form-label">Lokasi/Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" rows="3" required>{{ old('lokasi') }}</textarea>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_perolehan" class="form-label">Tanggal Perolehan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_perolehan') is-invalid @enderror" id="tanggal_perolehan" name="tanggal_perolehan" value="{{ old('tanggal_perolehan') }}" required>
                            @error('tanggal_perolehan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hitung nilai total otomatis
    document.addEventListener('DOMContentLoaded', function() {
        const luasTanahInput = document.getElementById('luas_tanah');
        const nilaiPerMeterInput = document.getElementById('nilai_per_meter');
        
        function hitungNilaiTotal() {
            const luasTanah = parseFloat(luasTanahInput.value) || 0;
            const nilaiPerMeter = parseFloat(nilaiPerMeterInput.value) || 0;
            const nilaiTotal = luasTanah * nilaiPerMeter;
            
            // Tampilkan nilai total jika diperlukan
            console.log('Nilai Total: ' + nilaiTotal.toLocaleString('id-ID'));
        }
        
        luasTanahInput.addEventListener('input', hitungNilaiTotal);
        nilaiPerMeterInput.addEventListener('input', hitungNilaiTotal);
    });
</script>
@endpush