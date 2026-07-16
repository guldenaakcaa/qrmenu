@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Genel Bakış')

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div class="stat-info">
            <h3>Toplam Kategori</h3>
            <p>{{ $totalCategories }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-burger"></i>
        </div>
        <div class="stat-info">
            <h3>Toplam Ürün</h3>
            <p>{{ $totalProducts }}</p>
        </div>
    </div>
</div>

<!-- Table Container -->
<div class="table-container">
    <div class="table-header">
        <h3>Son Eklenen Ürünler</h3>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Yeni Ürün
        </a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Ürün Adı</th>
                    <th>Kategori</th>
                    <th>Fiyat</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentProducts as $product)
                <tr>
                    <td>{{ $product->UrunAd }}</td>
                    <td>{{ $product->UrunGrubu }}</td>
                    <td>₺{{ number_format((float)$product->FixFiyat, 2) }}</td>
                    <td><span class="status active" style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: #dcfce7; color: #166534;">Aktif</span></td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-icon edit" title="Düzenle"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete" title="Sil" style="background: none; border: none;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem;">Henüz ürün bulunmuyor.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
