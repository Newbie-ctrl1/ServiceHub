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
            
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                <!-- Profile Header with Background -->
                <div class="position-relative">
                    <div class="profile-cover" style="height: 180px; background: linear-gradient(135deg, #4a6cf7 0%, #6e8df9 100%);"></div>
                    <div class="profile-header px-4 pb-4">
                        <div class="row align-items-end">
                            <div class="col-md-3 text-center text-md-start">
                                <div class="position-relative" style="margin-top: -60px;">
                                    @if(Auth::user()->profile_photo)
                                        <img src="data:image/jpeg;base64,{{ base64_encode(Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle border-4 border-white" style="width: 150px; height: 150px; object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle border-4 border-white" style="width: 150px; height: 150px; object-fit: cover; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-9 mt-4 mt-md-0">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                    <div>
                                        <h3 class="fw-bold mb-1">{{ Auth::user()->name }}</h3>
                                        <p class="text-muted mb-0">
                                            <i class="far fa-calendar-alt me-2"></i> Member sejak {{ Auth::user()->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="mt-3 mt-md-0">
                                        <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill px-4 me-2">
                                            <i class="fas fa-user-edit me-2"></i> Edit Profil
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Content -->
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-info">
                                <h4 class="mb-4 border-bottom pb-3">Informasi Pengguna</h4>
                                
                                <div class="row g-4">
                                    <!-- User Info Cards -->
                                    <div class="col-md-6">
                                        <div class="info-card p-4 rounded-lg bg-light mb-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <h5 class="mb-0">Nama Lengkap</h5>
                                            </div>
                                            <p class="mb-0 fs-5">{{ Auth::user()->name }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-card p-4 rounded-lg bg-light mb-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                                <h5 class="mb-0">Email</h5>
                                            </div>
                                            <p class="mb-0 fs-5">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-card p-4 rounded-lg bg-light mb-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-calendar-plus"></i>
                                                </div>
                                                <h5 class="mb-0">Bergabung Pada</h5>
                                            </div>
                                            <p class="mb-0 fs-5">{{ Auth::user()->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-card p-4 rounded-lg bg-light mb-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="icon-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <h5 class="mb-0">Terakhir Diperbarui</h5>
                                            </div>
                                            <p class="mb-0 fs-5">{{ Auth::user()->updated_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .icon-circle {
        transition: all 0.3s ease;
    }
    .info-card:hover .icon-circle {
        transform: scale(1.1);
    }
    .rounded-lg {
        border-radius: 0.5rem !important;
    }
    .profile-cover {
        background-size: cover;
        background-position: center;
    }
    @media (max-width: 767.98px) {
        .profile-header .position-relative {
            margin-top: -40px;
        }
    }
</style>
@endsection