@extends('Store.admin.index')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Detail Layanan</h2>
                <div>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Kembali</a>
                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gambar Utama</h5>
                    @if($service->main_image)
                        <img src="data:image/jpeg;base64,{{ base64_encode($service->main_image) }}" alt="{{ $service->title }}" class="img-fluid rounded">
                    @else
                        <div class="alert alert-info">Tidak ada gambar utama</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Layanan</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Judul</th>
                            <td>{{ $service->title }}</td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $service->location }}</td>
                        </tr>
                        <tr>
                            <th>Badge</th>
                            <td>
                                @if($service->badge)
                                    <span class="badge bg-primary">{{ $service->badge }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                @if($service->category)
                                    {{ $service->category }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Estimasi Waktu</th>
                            <td>{{ $service->min_time }}-{{ $service->max_time }} jam</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Deskripsi</h5>
                    <p>{{ $service->description }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($service->highlights && count($service->highlights) > 0)
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Highlights</h5>
                    <ul>
                        @foreach($service->highlights as $highlight)
                            <li>{{ $highlight }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($service->additionalImages && $service->additionalImages->count() > 0)
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Gambar Tambahan</h5>
                    <div class="row">
                        @foreach($service->additionalImages as $image)
                            <div class="col-md-3 mb-3">
                                <img src="data:image/jpeg;base64,{{ base64_encode($image->image) }}" alt="{{ $service->title }} - Image {{ $loop->iteration }}" class="img-fluid rounded">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection