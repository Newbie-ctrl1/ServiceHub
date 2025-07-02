@extends('Store.admin.index')

@section('content')
<!-- Tambahkan meta tag untuk memastikan viewport yang benar -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<style>
    /* Perbaikan untuk masalah scrolling */
    html, body {
        height: 100%;
        overflow-y: auto !important;
        position: relative;
    }
    .page-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .store-container {
        flex: 1;
        overflow-y: auto;
        padding-bottom: 60px; /* Tambahkan padding bawah untuk footer */
    }
    .form-container {
        max-height: none; /* Hapus batasan tinggi maksimum */
        overflow-y: visible; /* Biarkan konten mengalir secara alami */
        padding-bottom: 40px;
    }
    
    /* Styling untuk form */
    .form-section {
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .form-section.active {
        display: block;
        opacity: 1;
    }
    
    .progress-container {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .progress-step {
        text-align: center;
        position: relative;
        z-index: 1;
    }
    
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        transition: all 0.3s ease;
    }
    
    .progress-step.active .step-icon,
    .progress-step.completed .step-icon {
        background-color: var(--bs-primary);
        color: white;
    }
    
    .step-text {
        font-size: 0.85rem;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .progress-step.active .step-text,
    .progress-step.completed .step-text {
        color: var(--bs-primary);
        font-weight: 600;
    }
    
    /* Image upload styling */
    .image-upload-container {
        min-height: 200px;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }
    
    .image-upload-container:hover {
        border-color: var(--bs-primary);
    }
    
    .upload-placeholder {
        padding: 30px 0;
    }
    
    .image-preview-wrapper {
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Highlight items styling */
    .highlight-item {
        transition: all 0.3s ease;
    }
    
    .highlight-item:hover {
        transform: translateY(-2px);
    }
    
    /* Form controls styling */
    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: var(--bs-primary);
        opacity: 0.8;
    }
    
    /* Button styling */
    .btn {
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .btn-success {
        background-color: #198754;
        border-color: #198754;
    }
    
    .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Animation classes */
    .animate__animated {
        animation-duration: 0.5s;
    }
    
    .animate__fadeIn {
        animation-name: fadeIn;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Image preview styling */
    .preview-image-container {
        position: relative;
        display: inline-block;
        margin: 5px;
    }
    
    .preview-image-container .remove-image {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #ff5e5e;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .preview-image-container:hover .remove-image {
        opacity: 1;
    }
</style>
<div class="container py-5 form-container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-plus-circle me-2 fs-4"></i>
                        <h5 class="mb-0">Tambah Layanan</h5>
                    </div>
                </div>
                <div class="card-body form-container p-4">
                    <div class="progress-container mb-4">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="progress-step active" data-step="1">
                                <div class="step-icon"><i class="fas fa-info-circle"></i></div>
                                <div class="step-text">Informasi Dasar</div>
                            </div>
                            <div class="progress-step" data-step="2">
                                <div class="step-icon"><i class="fas fa-dollar-sign"></i></div>
                                <div class="step-text">Harga & Waktu</div>
                            </div>
                            <div class="progress-step" data-step="3">
                                <div class="step-icon"><i class="fas fa-image"></i></div>
                                <div class="step-text">Media</div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('store.store') }}" method="POST" enctype="multipart/form-data" id="serviceForm">
                        @csrf
                        
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        <div class="row g-3">
                            <!-- Informasi Dasar -->
                            <div class="form-section active" id="section-1">
                                <div class="section-header d-flex align-items-center mb-3">
                                    <div class="section-icon me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Informasi Dasar</h5>
                                </div>
                                
                                <div class="card border-0 shadow-sm rounded-lg mb-4 animate__animated animate__fadeIn">
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Judul Layanan">
                                                    <label for="title">Judul Layanan <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="badge" name="badge">
                                                        <option value="" {{ old('badge') == '' ? 'selected' : '' }}>Pilih Badge</option>
                                                        <option value="Tersedia" {{ old('badge') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                        <option value="Terbatas" {{ old('badge') == 'Terbatas' ? 'selected' : '' }}>Terbatas</option>
                                                        <option value="Promo" {{ old('badge') == 'Promo' ? 'selected' : '' }}>Promo</option>
                                                    </select>
                                                    <label for="badge">Badge (opsional)</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="category" name="category">
                                                        <option value="" {{ old('category') == '' ? 'selected' : '' }}>Pilih Kategori</option>
                                                        <option value="Gadget" {{ old('category') == 'Gadget' ? 'selected' : '' }}>Gadget</option>
                                                        <option value="Electronic" {{ old('category') == 'Electronic' ? 'selected' : '' }}>Electronic</option>
                                                        <option value="Otomotif" {{ old('category') == 'Otomotif' ? 'selected' : '' }}>Otomotif</option>
                                                        <option value="Fashion" {{ old('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                                                    </select>
                                                    <label for="category">Kategori <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-floating mb-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                        <div class="form-floating flex-grow-1">
                                                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" placeholder="Lokasi Layanan">
                                                            <label for="location">Lokasi Layanan</label>
                                                            <div class="invalid-feedback">
                                                                Lokasi tidak boleh lebih dari 255 karakter.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <textarea class="form-control" id="short_description" name="short_description" style="height: 80px" required placeholder="Deskripsi Singkat">{{ old('short_description') }}</textarea>
                                                    <label for="short_description">Deskripsi Singkat <span class="text-danger">*</span></label>
                                                    <small class="text-muted">Maksimal 150 karakter</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <textarea class="form-control" id="description" name="description" style="height: 150px" required placeholder="Deskripsi Lengkap">{{ old('description') }}</textarea>
                                                    <label for="description">Deskripsi Lengkap <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-primary next-step" data-step="1">Lanjut <i class="fas fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Harga dan Waktu -->
                            <div class="form-section" id="section-2">
                                <div class="section-header d-flex align-items-center mb-3">
                                    <div class="section-icon me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Harga dan Waktu</h5>
                                </div>
                                
                                <div class="card border-0 shadow-sm rounded-lg mb-4 animate__animated animate__fadeIn">
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                        <div class="form-floating flex-grow-1">
                                                            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required placeholder="Harga">
                                                            <label for="price">Harga (Rp) <span class="text-danger">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                        <div class="form-floating flex-grow-1">
                                                            <input type="number" class="form-control" id="min_time" name="min_time" value="{{ old('min_time') }}" required placeholder="Waktu Minimum">
                                                            <label for="min_time">Waktu Minimum (jam) <span class="text-danger">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-floating mb-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-hourglass-end"></i></span>
                                                        <div class="form-floating flex-grow-1">
                                                            <input type="number" class="form-control" id="max_time" name="max_time" value="{{ old('max_time') }}" required placeholder="Waktu Maksimum">
                                                            <label for="max_time">Waktu Maksimum (jam) <span class="text-danger">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-3">
                                            <button type="button" class="btn btn-outline-secondary prev-step" data-step="2"><i class="fas fa-arrow-left me-2"></i> Kembali</button>
                                            <button type="button" class="btn btn-primary next-step" data-step="2">Lanjut <i class="fas fa-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Highlights -->
                            <div class="form-section" id="section-3">
                                <div class="section-header d-flex align-items-center mb-3">
                                    <div class="section-icon me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Fitur Unggulan & Media</h5>
                                </div>
                                
                                <div class="card border-0 shadow-sm rounded-lg mb-4 animate__animated animate__fadeIn">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-star me-2 text-warning"></i>Highlights (Fitur Unggulan)</h6>
                                        <div class="mb-4">
                                            <textarea class="form-control" id="highlights" name="highlights" rows="5" placeholder="Masukkan setiap highlight dalam baris terpisah"></textarea>
                                            <small class="text-muted">Contoh: Teknisi berpengalaman, Garansi 30 hari, dll. Tulis satu fitur per baris.</small>
                                        </div>
                                    </div>
                                </div>
                                        
                                        <h6 class="fw-bold mb-3 mt-4"><i class="fas fa-images me-2 text-primary"></i>Gambar</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="main_image" class="form-label d-block">Gambar Utama <span class="text-danger">*</span></label>
                                                    <div class="image-upload-container border rounded p-3 text-center position-relative">
                                                        <div class="image-preview-wrapper mb-3">
                                                            <img id="main_image_preview" src="#" alt="Preview" style="max-width: 100%; max-height: 200px; display: none;" class="rounded shadow-sm">
                                                            <div class="upload-placeholder" id="main_image_placeholder">
                                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                                                <p class="mb-0">Klik untuk memilih gambar utama</p>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*" required style="display: none;">
                                                        <button type="button" class="btn btn-outline-primary w-100" id="main_image_btn">Pilih Gambar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="additional_images" class="form-label d-block">Gambar Tambahan</label>
                                                    <div class="image-upload-container border rounded p-3 text-center position-relative">
                                                        <div id="additional_images_preview" class="d-flex flex-wrap gap-2 justify-content-center mb-3">
                                                            <div class="upload-placeholder" id="additional_images_placeholder">
                                                                <i class="fas fa-images fa-3x text-muted mb-2"></i>
                                                                <p class="mb-0">Klik untuk memilih beberapa gambar</p>
                                                            </div>
                                                        </div>
                                                        <input type="file" class="form-control" id="additional_images" name="additional_images[]" accept="image/*" multiple style="display: none;">
                                                        <button type="button" class="btn btn-outline-primary w-100" id="additional_images_btn">Pilih Gambar</button>
                                                    </div>
                                                    <small class="text-muted">Anda dapat memilih beberapa gambar</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-4">
                                            <button type="button" class="btn btn-outline-secondary prev-step" data-step="3"><i class="fas fa-arrow-left me-2"></i> Kembali</button>
                                            <div>
                                                <a href="{{ route('store') }}" class="btn btn-outline-danger me-2">Batal</a>
                                                <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Simpan Layanan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Fungsi untuk navigasi form multi-step
        function goToStep(step) {
            // Sembunyikan semua section
            $('.form-section').removeClass('active');
            
            // Tampilkan section yang dipilih
            $('#section-' + step).addClass('active');
            
            // Update progress bar
            let progressWidth = (step / 3) * 100;
            $('.progress-bar').css('width', progressWidth + '%');
            $('.progress-bar').attr('aria-valuenow', progressWidth);
            
            // Update status langkah
            $('.progress-step').removeClass('active completed');
            for (let i = 1; i <= 3; i++) {
                if (i < step) {
                    $('.progress-step[data-step="' + i + '"]').addClass('completed');
                } else if (i === step) {
                    $('.progress-step[data-step="' + i + '"]').addClass('active');
                }
            }
            
            // Scroll ke atas form
            $('html, body').animate({
                scrollTop: $('.progress-container').offset().top - 100
            }, 300);
        }
        
        // Event handler untuk tombol next
        $('.next-step').click(function() {
            let currentStep = parseInt($(this).data('step'));
            let nextStep = currentStep + 1;
            
            // Validasi form sebelum pindah ke langkah berikutnya
            let isValid = true;
            
            // Validasi untuk langkah 1 (Informasi Dasar)
            if (currentStep === 1) {
                if ($('#title').val().trim() === '') {
                    $('#title').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#title').removeClass('is-invalid');
                }
                
                if ($('#short_description').val().trim() === '') {
                    $('#short_description').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#short_description').removeClass('is-invalid');
                }
                
                if ($('#description').val().trim() === '') {
                    $('#description').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#description').removeClass('is-invalid');
                }
                
                // Validasi lokasi (opsional, hanya periksa format jika diisi)
                if ($('#location').val().trim() !== '' && $('#location').val().length > 255) {
                    $('#location').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#location').removeClass('is-invalid');
                }
            }
            
            // Validasi untuk langkah 2 (Harga dan Waktu)
            if (currentStep === 2) {
                if ($('#price').val().trim() === '' || parseInt($('#price').val()) <= 0) {
                    $('#price').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#price').removeClass('is-invalid');
                }
                
                if ($('#min_time').val().trim() === '' || parseInt($('#min_time').val()) <= 0) {
                    $('#min_time').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#min_time').removeClass('is-invalid');
                }
                
                if ($('#max_time').val().trim() === '' || parseInt($('#max_time').val()) <= 0) {
                    $('#max_time').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#max_time').removeClass('is-invalid');
                }
            }
            
            if (isValid) {
                goToStep(nextStep);
            } else {
                // Scroll ke elemen invalid pertama
                let firstInvalid = $('.is-invalid').first();
                if (firstInvalid.length) {
                    $('html, body').animate({
                        scrollTop: firstInvalid.offset().top - 100
                    }, 300);
                }
            }
        });
        
        // Event handler untuk tombol previous
        $('.prev-step').click(function() {
            let currentStep = parseInt($(this).data('step'));
            let prevStep = currentStep - 1;
            goToStep(prevStep);
        });
        
        // Preview main image
        $('#main_image_btn').click(function() {
            $('#main_image').click();
        });
        
        $('#main_image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#main_image_preview').attr('src', e.target.result).show();
                    $('#main_image_placeholder').hide();
                    
                    // Tambahkan tombol hapus
                    if ($('#main_image_preview').parent().find('.remove-image').length === 0) {
                        $('#main_image_preview').after('<div class="remove-image" id="remove_main_image"><i class="fas fa-times"></i></div>');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Hapus preview gambar utama
        $(document).on('click', '#remove_main_image', function() {
            $('#main_image_preview').attr('src', '#').hide();
            $('#main_image_placeholder').show();
            $('#main_image').val('');
            $(this).remove();
        });
        
        // Preview additional images
        $('#additional_images_btn').click(function() {
            $('#additional_images').click();
        });
        
        $('#additional_images').change(function() {
            const files = this.files;
            $('#additional_images_preview').empty();
            $('#additional_images_placeholder').hide();
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#additional_images_preview').append(`
                        <div class="preview-image-container">
                            <img src="${e.target.result}" alt="Preview" style="width: 100px; height: 75px; object-fit: cover;" class="rounded shadow-sm">
                            <div class="remove-image remove-additional-image" data-index="${i}"><i class="fas fa-times"></i></div>
                        </div>
                    `);
                }
                
                reader.readAsDataURL(file);
            }
            
            if (files.length === 0) {
                $('#additional_images_placeholder').show();
            }
        });
        
        // Hapus preview gambar tambahan (ini tidak benar-benar menghapus file dari input, hanya visual)
        $(document).on('click', '.remove-additional-image', function() {
            $(this).closest('.preview-image-container').remove();
            
            if ($('#additional_images_preview .preview-image-container').length === 0) {
                $('#additional_images_placeholder').show();
            }
        });
        
        // Highlight fields sekarang menggunakan textarea
        
        // Efek hover untuk form fields
        $('.form-control, .form-select').hover(function() {
            $(this).addClass('shadow-sm');
        }, function() {
            $(this).removeClass('shadow-sm');
        });
        
        // Validasi form sebelum submit
        $('#serviceForm').submit(function(e) {
            let isValid = true;
            
            // Validasi gambar utama
            if ($('#main_image').val() === '') {
                e.preventDefault();
                isValid = false;
                alert('Gambar utama wajib diisi!');
            }
            
            // Convert highlights textarea to array
            const highlightsText = $('#highlights').val();
            if (highlightsText.trim()) {
                const highlightsArray = highlightsText.split('\n').filter(line => line.trim() !== '');
                
                // Remove the original textarea and add hidden inputs for each highlight
                $('#highlights').prop('disabled', true);
                
                // Remove any existing highlight inputs
                $('input[name="highlights[]"]').remove();
                
                // Add array inputs
                highlightsArray.forEach(function(highlight, index) {
                    $('#serviceForm').append(`<input type="hidden" name="highlights[]" value="${highlight.trim()}">`);
                });
            }
            
            return isValid;
        });
        
        // Inisialisasi langkah pertama
        goToStep(1);
    });
</script>
@endsection