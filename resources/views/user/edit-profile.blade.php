@extends('layouts.app')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            
            <!-- Edit Profile Card -->
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <!-- Profile Header with Background -->
                <div class="position-relative">
                    <div class="profile-cover" style="height: 120px; background: linear-gradient(135deg, #4a6cf7 0%, #6e8df9 100%);"></div>
                    <div class="profile-header px-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="fw-bold text-white position-absolute" style="top: 40px; left: 25px;">Edit Profil</h3>
                            <a href="{{ route('profile') }}" class="btn btn-light rounded-pill position-absolute" style="top: 40px; right: 25px;">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Form Content -->
                <div class="card-body p-4 pt-5">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-edit-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-4 text-center mb-4">
                                <div class="profile-photo-container position-relative mx-auto" style="width: 180px;">
                                    <div class="position-relative d-inline-block">
                                        @if(Auth::user()->profile_photo)
                                            <img src="data:image/jpeg;base64,{{ base64_encode(Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle border-4 border-white mb-3" style="width: 180px; height: 180px; object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                        @else
                                            <img src="https://yudiz.com/codepen/nft-store/user-pic1.svg" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle border-4 border-white mb-3" style="width: 180px; height: 180px; object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                        @endif
                                        
                                        <div class="upload-overlay position-absolute rounded-circle d-flex align-items-center justify-content-center" style="top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); opacity: 0; transition: all 0.3s ease;">
                                            <label for="profile_photo" class="btn btn-light rounded-circle p-2" style="width: 45px; height: 45px;">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <input type="file" class="form-control d-none @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo">
                                        <label for="profile_photo" class="btn btn-outline-primary rounded-pill mt-2">
                                            <i class="fas fa-upload me-2"></i> Ubah Foto
                                        </label>
                                        @error('profile_photo')
                                            <div class="invalid-feedback d-block mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    @if(Auth::user()->profile_photo)
                                        <div class="mt-2">
                                            <a href="{{ route('profile.delete-photo') }}" class="btn btn-outline-danger rounded-pill" onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil?')">
                                                <i class="fas fa-trash-alt me-2"></i> Hapus Foto
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-lg-8">
                                <div class="info-card p-4 rounded-lg bg-light mb-4">
                                    <h4 class="mb-4 border-bottom pb-3">Informasi Pengguna</h4>
                                    
                                    <div class="mb-4">
                                        <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-user text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" placeholder="Masukkan nama lengkap" required>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-envelope text-primary"></i>
                                            </span>
                                            <input type="email" class="form-control border-start-0 bg-light" value="{{ Auth::user()->email }}" disabled>
                                        </div>
                                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle me-1"></i> Email tidak dapat diubah</small>
                                    </div>
                                    
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                        <a href="{{ route('profile') }}" class="btn btn-light rounded-pill px-4 me-md-2">
                                            <i class="fas fa-times me-2"></i> Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                                        </button>
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

<style>
    .border-4 {
        border-width: 4px !important;
        border-style: solid !important;
    }
    .info-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .rounded-lg {
        border-radius: 0.5rem !important;
    }
    .profile-cover {
        background-size: cover;
        background-position: center;
    }
    .profile-photo-container:hover .upload-overlay {
        opacity: 1;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(74, 108, 247, 0.15);
        border-color: #4a6cf7;
    }
    .input-group-text {
        color: #6c757d;
    }
    .btn-outline-primary {
        color: #4a6cf7;
        border-color: #4a6cf7;
    }
    .btn-outline-primary:hover {
        background-color: #4a6cf7;
        color: white;
    }
    .btn-outline-danger:hover {
        background-color: #ff5e5e;
        border-color: #ff5e5e;
    }
    @media (max-width: 767.98px) {
        .profile-header h3, .profile-header a {
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            margin-top: 15px;
        }
        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 15px;
        }
        .profile-header a {
            margin-top: 15px;
            margin-bottom: 15px;
        }
    }
</style>
@endsection