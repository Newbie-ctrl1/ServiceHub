@extends('layouts.app')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Profil Pengguna</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <img src="https://yudiz.com/codepen/nft-store/user-pic1.svg" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #f0f0f0; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                            <h5>{{ Auth::user()->name }}</h5>
                            <p class="text-muted">Member sejak {{ Auth::user()->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama</label>
                                <p>{{ Auth::user()->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p>{{ Auth::user()->email }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Bergabung Pada</label>
                                <p>{{ Auth::user()->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Terakhir Diperbarui</label>
                                <p>{{ Auth::user()->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="btn btn-primary">Edit Profil</a>
                                <a href="#" class="btn btn-outline-secondary ms-2">Ubah Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection