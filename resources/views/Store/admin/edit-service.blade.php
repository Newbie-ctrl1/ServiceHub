@extends('Store.admin.index')

@section('content')
<style>
    /* Form styling */
    .form-label {
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid var(--border-color);
        padding: 10px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.15);
    }
    
    .input-group-text {
        background-color: var(--background-light);
        border: 1.5px solid var(--border-color);
        border-radius: 8px;
    }
    
    /* Card styling */
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
        border: 1.5px solid var(--border-color);
    }
    
    /* Section title */
    .section-title {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    /* Image preview */
    .image-preview-container {
        margin-top: 20px;
        padding: 15px;
        border-radius: 12px;
        background-color: var(--background-light);
        border: 1.5px dashed var(--border-color);
    }
    
    .image-preview-row .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }
    
    .image-preview-row .card-img-top {
        height: 120px;
        object-fit: cover;
    }
    
    .card-img-top {
        height: 150px;
        object-fit: cover;
    }
</style>
<div class="store-content">
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="section-title">Edit Layanan Service</h2>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Layanan
                    </a>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" id="editServiceForm">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Informasi Dasar -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-3">Informasi Dasar</h5>
                                    
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Judul Layanan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ $service->title }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi Singkat <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $service->description }}</textarea>
                                        <small class="text-muted">Maksimal 150 karakter</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="full_description" class="form-label">Deskripsi Lengkap <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="full_description" name="full_description" rows="6" required>{{ $service->full_description }}</textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="badge" class="form-label">Badge</label>
                                                <select class="form-select" id="badge" name="badge">
                                                    <option value="" @if($service->badge == '') selected @endif>Tanpa Badge</option>
                                                    <option value="Premium" @if($service->badge == 'Premium') selected @endif>Premium</option>
                                                    <option value="Tersedia" @if($service->badge == 'Tersedia') selected @endif>Tersedia</option>
                                                    <option value="Terbatas" @if($service->badge == 'Terbatas') selected @endif>Terbatas</option>
                                                    <option value="Promo" @if($service->badge == 'Promo') selected @endif>Promo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Kategori</label>
                                                <select class="form-select" id="category" name="category">
                                                    <option value="" @if($service->category == '' || $service->category == null) selected @endif>Pilih Kategori</option>
                                                    <option value="Gadget" @if($service->category == 'Gadget') selected @endif>Gadget</option>
                                                    <option value="Electronic" @if($service->category == 'Electronic') selected @endif>Electronic</option>
                                                    <option value="Otomotif" @if($service->category == 'Otomotif') selected @endif>Otomotif</option>
                                                    <option value="Fashion" @if($service->category == 'Fashion') selected @endif>Fashion</option>
                                                    <option value="Other" @if($service->category == 'Other') selected @endif>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Lokasi Layanan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="location" name="location" value="{{ $service->location }}" required placeholder="Masukkan lokasi layanan">
                                    </div>
                                </div>
                                
                                <!-- Harga dan Waktu -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-3">Harga dan Waktu</h5>
                                    
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="price" name="price" min="0" value="{{ $service->price }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="min_time" class="form-label">Estimasi Waktu <span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="min_time" name="min_time" min="1" value="{{ $service->min_time }}" required>
                                                    <label for="min_time">Minimum (jam)</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="max_time" name="max_time" min="1" value="{{ $service->max_time }}" required>
                                                    <label for="max_time">Maksimum (jam)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="highlights" class="form-label">Highlight Layanan</label>
                                        <textarea class="form-control" id="highlights" name="highlights" rows="5" placeholder="Masukkan setiap highlight dalam baris terpisah">{{ is_array($service->highlights) ? implode("\n", $service->highlights) : '' }}</textarea>
                                        <small class="text-muted">Contoh: Teknisi berpengalaman, Garansi 30 hari, dll.</small>
                                    </div>
                                </div>
                                
                                <!-- Gambar Layanan -->
                                <div class="col-12 mb-4">
                                    <h5 class="mb-3">Gambar Layanan</h5>
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card h-100">
                                                <img src="data:image/jpeg;base64,{{ base64_encode($service->main_image) }}" class="card-img-top" alt="Gambar Utama">
                                                <div class="card-body p-2">
                                                    <p class="card-text small text-center mb-0">Gambar Utama Saat Ini</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($service->additionalImages->count() > 0)
                                            @foreach($service->additionalImages as $index => $imageObj)
                                                <div class="col-md-3">
                                                    <div class="card h-100">
                                                        <img src="data:image/jpeg;base64,{{ base64_encode($imageObj->image) }}" class="card-img-top" alt="Gambar Tambahan {{ $index + 1 }}">
                                                        <div class="card-body p-2">
                                                            <p class="card-text small text-center mb-0">Gambar Tambahan {{ $index + 1 }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="main_image" class="form-label">Ganti Gambar Utama</label>
                                        <input type="file" class="form-control" id="main_image" name="main_image" accept="image/*">
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar utama</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="additional_images" class="form-label">Ganti Gambar Tambahan</label>
                                        <input type="file" class="form-control" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar tambahan. Jika diisi, semua gambar tambahan akan diganti.</small>
                                    </div>
                                    
                                    <div class="image-preview-container mt-3 d-none">
                                        <h6>Preview Gambar Baru:</h6>
                                        <div class="row image-preview-row" id="imagePreview"></div>
                                    </div>
                                </div>
                                
                                <!-- Tombol Submit -->
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Preview gambar saat dipilih
        $('#main_image, #additional_images').on('change', function() {
            const container = $('#imagePreview');
            const previewContainer = $('.image-preview-container');
            
            // Tampilkan container preview
            previewContainer.removeClass('d-none');
            
            // Jika yang diubah adalah gambar utama
            if (this.id === 'main_image') {
                // Hapus preview gambar utama sebelumnya
                container.find('.main-image-preview').remove();
                
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        container.prepend(`
                            <div class="col-md-3 mb-3 main-image-preview">
                                <div class="card h-100">
                                    <img src="${e.target.result}" class="card-img-top" alt="Preview">
                                    <div class="card-body p-2">
                                        <p class="card-text small text-center mb-0">Gambar Utama Baru</p>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            }
            // Jika yang diubah adalah gambar tambahan
            else if (this.id === 'additional_images') {
                // Hapus preview gambar tambahan sebelumnya
                container.find('.additional-image-preview').remove();
                
                if (this.files && this.files.length > 0) {
                    for (let i = 0; i < Math.min(this.files.length, 4); i++) {
                        const reader = new FileReader();
                        const file = this.files[i];
                        
                        reader.onload = function(e) {
                            container.append(`
                                <div class="col-md-3 mb-3 additional-image-preview">
                                    <div class="card h-100">
                                        <img src="${e.target.result}" class="card-img-top" alt="Preview">
                                        <div class="card-body p-2">
                                            <p class="card-text small text-center mb-0">Tambahan Baru ${i+1}</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                        }
                        
                        reader.readAsDataURL(file);
                    }
                }
            }
        });
        
        // Validasi form sebelum submit
        $('#editServiceForm').on('submit', function(e) {
            let isValid = true;
            
            // Validasi judul
            if ($('#title').val().trim() === '') {
                alert('Judul layanan tidak boleh kosong');
                $('#title').focus();
                isValid = false;
            }
            
            // Validasi deskripsi
            else if ($('#description').val().trim() === '') {
                alert('Deskripsi singkat tidak boleh kosong');
                $('#description').focus();
                isValid = false;
            }
            
            // Validasi lokasi
            else if ($('#location').val().trim() === '') {
                alert('Lokasi layanan tidak boleh kosong');
                $('#location').focus();
                isValid = false;
            }
            
            // Validasi harga
            else if ($('#price').val() <= 0) {
                alert('Harga harus lebih dari 0');
                $('#price').focus();
                isValid = false;
            }
            
            // Validasi waktu
            else if (parseInt($('#min_time').val()) > parseInt($('#max_time').val())) {
                alert('Waktu minimal tidak boleh lebih besar dari waktu maksimal');
                $('#min_time').focus();
                isValid = false;
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
                    $('#editServiceForm').append(`<input type="hidden" name="highlights[]" value="${highlight.trim()}">`);
                });
            }
            
            return isValid;
        });
    });
</script>


@endsection