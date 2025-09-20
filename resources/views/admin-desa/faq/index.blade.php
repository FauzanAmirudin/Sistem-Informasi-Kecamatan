@extends('layouts.admin')

@section('page-title', 'FAQ - Pertanyaan Umum')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.faq.panduan') }}" class="btn btn-info">
        <i class="fas fa-book me-1"></i>
        Panduan Penggunaan
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Pertanyaan yang Sering Diajukan (FAQ)
                </h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="faqAccordion">
                    <!-- Kategori: Umum -->
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingUmum">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUmum" aria-expanded="true" aria-controls="collapseUmum">
                                <i class="fas fa-info-circle me-2"></i> Pertanyaan Umum
                            </button>
                        </h2>
                        <div id="collapseUmum" class="accordion-collapse collapse show" aria-labelledby="headingUmum" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <div class="mb-4">
                                    <h5 class="text-primary">Apa itu Sistem Informasi Kecamatan?</h5>
                                    <p>Sistem Informasi Kecamatan adalah platform digital yang dirancang untuk membantu pengelolaan data dan informasi di tingkat kecamatan dan desa. Sistem ini memungkinkan pengelolaan data penduduk, aset desa, dokumen, dan berbagai informasi penting lainnya secara terintegrasi.</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary">Siapa saja yang dapat mengakses sistem ini?</h5>
                                    <p>Sistem ini dapat diakses oleh:</p>
                                    <ul>
                                        <li>Admin Kecamatan: Memiliki akses penuh untuk mengelola seluruh data kecamatan dan desa</li>
                                        <li>Admin Desa: Memiliki akses untuk mengelola data desa masing-masing</li>
                                    </ul>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary">Bagaimana cara mengubah password akun saya?</h5>
                                    <p>Untuk mengubah password:</p>
                                    <ol>
                                        <li>Klik pada nama pengguna Anda di bagian atas sidebar</li>
                                        <li>Pilih opsi "Profil"</li>
                                        <li>Klik tab "Ubah Password"</li>
                                        <li>Masukkan password lama dan password baru</li>
                                        <li>Klik "Simpan Perubahan"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kategori: Data Desa -->
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingDataDesa">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDataDesa" aria-expanded="false" aria-controls="collapseDataDesa">
                                <i class="fas fa-home me-2"></i> Data Desa
                            </button>
                        </h2>
                        <div id="collapseDataDesa" class="accordion-collapse collapse" aria-labelledby="headingDataDesa" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <div class="mb-4">
                                    <h5 class="text-primary">Bagaimana cara memperbarui informasi desa?</h5>
                                    <p>Untuk memperbarui informasi desa:</p>
                                    <ol>
                                        <li>Buka menu "Data Desa" di sidebar</li>
                                        <li>Klik tombol "Edit" pada halaman detail desa</li>
                                        <li>Perbarui informasi yang diperlukan</li>
                                        <li>Klik "Simpan Perubahan"</li>
                                    </ol>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary">Apakah saya bisa mengubah lokasi desa pada peta?</h5>
                                    <p>Ya, Anda dapat mengubah lokasi desa pada peta dengan cara:</p>
                                    <ol>
                                        <li>Buka menu "Data Desa" di sidebar</li>
                                        <li>Klik tombol "Edit" pada halaman detail desa</li>
                                        <li>Pada bagian peta, Anda dapat menggeser pin lokasi ke posisi yang benar</li>
                                        <li>Klik "Simpan Perubahan"</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kategori: Dokumen -->
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingDokumen">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDokumen" aria-expanded="false" aria-controls="collapseDokumen">
                                <i class="fas fa-folder me-2"></i> Dokumen
                            </button>
                        </h2>
                        <div id="collapseDokumen" class="accordion-collapse collapse" aria-labelledby="headingDokumen" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <div class="mb-4">
                                    <h5 class="text-primary">Bagaimana cara mengunggah dokumen baru?</h5>
                                    <p>Untuk mengunggah dokumen baru:</p>
                                    <ol>
                                        <li>Buka menu "Dokumen & Bantuan" di sidebar</li>
                                        <li>Klik tombol "Upload Dokumen"</li>
                                        <li>Isi formulir dengan informasi dokumen yang diperlukan</li>
                                        <li>Pilih file yang ingin diunggah</li>
                                        <li>Klik "Upload Dokumen"</li>
                                    </ol>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary">Format file apa saja yang didukung untuk upload dokumen?</h5>
                                    <p>Format file yang didukung untuk upload dokumen adalah:</p>
                                    <ul>
                                        <li>PDF (.pdf)</li>
                                        <li>Gambar (.jpg, .jpeg, .png)</li>
                                        <li>Dokumen Microsoft Office (.doc, .docx, .xls, .xlsx, .ppt, .pptx)</li>
                                    </ul>
                                    <p>Ukuran maksimum file yang dapat diunggah adalah 10MB.</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary">Apa perbedaan dokumen publik dan private?</h5>
                                    <p>Perbedaan dokumen publik dan private:</p>
                                    <ul>
                                        <li><strong>Dokumen Publik:</strong> Dapat diakses oleh semua pengguna sistem, termasuk admin kecamatan dan admin desa lainnya.</li>
                                        <li><strong>Dokumen Private:</strong> Hanya dapat diakses oleh admin desa yang mengunggah dokumen tersebut dan admin kecamatan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kategori: Aset Tanah Warga -->
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingAsetTanah">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAsetTanah" aria-expanded="false" aria-controls="collapseAsetTanah">
                                <i class="fas fa-map me-2"></i> Aset Tanah Warga
                            </button>
                        </h2>
                        <div id="collapseAsetTanah" class="accordion-collapse collapse" aria-labelledby="headingAsetTanah" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <div class="mb-4">
                                    <h5 class="text-primary">Bagaimana cara menambahkan data aset tanah warga?</h5>
                                    <p>Untuk menambahkan data aset tanah warga:</p>
                                    <ol>
                                        <li>Buka menu "Aset Tanah Warga" di sidebar</li>
                                        <li>Klik tombol "Tambah Aset Tanah"</li>
                                        <li>Isi formulir dengan informasi yang diperlukan</li>
                                        <li>Upload bukti kepemilikan jika ada</li>
                                        <li>Klik "Simpan"</li>
                                    </ol>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary">Bagaimana cara menghasilkan laporan rekap aset tanah warga?</h5>
                                    <p>Untuk menghasilkan laporan rekap aset tanah warga:</p>
                                    <ol>
                                        <li>Buka menu "Aset Tanah Warga" di sidebar</li>
                                        <li>Klik tombol "Rekap"</li>
                                        <li>Pilih filter yang diinginkan (opsional)</li>
                                        <li>Klik tombol "Export Excel" atau "Export PDF" untuk mengunduh laporan</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kategori: Masalah Teknis -->
                    <div class="accordion-item border mb-3 shadow-sm">
                        <h2 class="accordion-header" id="headingTeknis">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTeknis" aria-expanded="false" aria-controls="collapseTeknis">
                                <i class="fas fa-wrench me-2"></i> Masalah Teknis
                            </button>
                        </h2>
                        <div id="collapseTeknis" class="accordion-collapse collapse" aria-labelledby="headingTeknis" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <div class="mb-4">
                                    <h5 class="text-primary">Apa yang harus dilakukan jika saya lupa password?</h5>
                                    <p>Jika Anda lupa password:</p>
                                    <ol>
                                        <li>Klik link "Lupa Password" pada halaman login</li>
                                        <li>Masukkan alamat email yang terdaftar</li>
                                        <li>Cek email Anda untuk instruksi reset password</li>
                                        <li>Ikuti petunjuk untuk membuat password baru</li>
                                    </ol>
                                    <p>Jika Anda masih mengalami masalah, hubungi admin kecamatan untuk bantuan.</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary">Bagaimana cara mengatasi error saat upload file?</h5>
                                    <p>Jika Anda mengalami error saat upload file, coba langkah-langkah berikut:</p>
                                    <ol>
                                        <li>Pastikan ukuran file tidak melebihi 10MB</li>
                                        <li>Periksa format file apakah sesuai dengan yang didukung</li>
                                        <li>Coba kompres file jika terlalu besar</li>
                                        <li>Pastikan koneksi internet Anda stabil</li>
                                        <li>Coba refresh halaman dan upload ulang</li>
                                    </ol>
                                </div>
                                
                                <div class="mb-4">
                                    <h5 class="text-primary">Siapa yang harus dihubungi jika ada masalah teknis?</h5>
                                    <p>Jika Anda mengalami masalah teknis yang tidak dapat diselesaikan, silakan hubungi:</p>
                                    <ul>
                                        <li>Admin Kecamatan: Untuk masalah terkait akses dan penggunaan sistem</li>
                                        <li>Tim IT Kecamatan: Untuk masalah teknis yang lebih kompleks</li>
                                    </ul>
                                    <p>Anda dapat menghubungi mereka melalui email support@kecamatan.go.id atau telepon (021) 1234567.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection