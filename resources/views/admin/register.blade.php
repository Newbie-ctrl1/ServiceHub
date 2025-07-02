@extends('layouts.app')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Daftar Sebagai Admin Toko</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <p class="lead">Sebagai admin toko, Anda akan memiliki kemampuan untuk:</p>
                    <ul class="mb-4">
                        <li>Mengelola layanan dan produk</li>
                        <li>Melihat dan mengelola pesanan</li>
                        <li>Mengakses dashboard admin</li>
                        <li>Menyesuaikan pengaturan toko</li>
                    </ul>
                    
                    <form method="POST" action="{{ route('admin.register.submit') }}">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="reason">Mengapa Anda ingin menjadi admin toko?</label>
                            <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" rows="5" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Jelaskan alasan Anda ingin menjadi admin toko dan apa yang dapat Anda tawarkan.</small>
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary px-5">
                                Daftar Sebagai Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection