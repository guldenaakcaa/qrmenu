@extends('admin.layouts.app')

@section('title', 'Ürün Düzenle')
@section('header_title', 'Ürün Düzenle')

@section('content')
<div class="card" style="max-width: 800px;">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="UrunGrubu_id">Kategori</label>
            <select id="UrunGrubu_id" name="UrunGrubu_id" class="form-control" required>
                <option value="">Kategori Seçin</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->UrunGrubu_id == $category->id ? 'selected' : '' }}>
                        {{ $category->Urungrubu }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="UrunAd">Ürün Adı</label>
            <input type="text" id="UrunAd" name="UrunAd" class="form-control" value="{{ $product->UrunAd }}" required>
        </div>
        
        <div class="form-group">
            <label for="UrunAciklama">Ürün Açıklaması</label>
            <textarea id="UrunAciklama" name="UrunAciklama" class="form-control">{{ $product->UrunAciklama }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 1.5rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="FixFiyat">Fiyat (₺)</label>
                <input type="number" step="0.01" id="FixFiyat" name="FixFiyat" class="form-control" value="{{ $product->FixFiyat }}">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="kalori">Kalori (kcal)</label>
                <input type="text" id="kalori" name="kalori" class="form-control" value="{{ $product->kalori }}">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="hazirlanma_suresi">Hazırlanma Süresi</label>
                <input type="text" id="hazirlanma_suresi" name="hazirlanma_suresi" class="form-control" value="{{ $product->hazirlanma_suresi }}" placeholder="Örn: 15-20 dk">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="hidden" name="has_lactose" value="0">
                <input type="checkbox" name="has_lactose" value="1" {{ $product->has_lactose ? 'checked' : '' }} style="width: 18px; height: 18px;">
                <span style="font-weight: 500; color: #1e293b;">Laktozsuz Seçeneği Bulunmaktadır</span>
            </label>
        </div>

        <div class="form-group">
            <label for="UrunResimPath">Ürün Görseli</label>
            @if($product->UrunResimPath && $product->UrunResimPath !== '0')
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $product->UrunResimPath) }}" alt="Ürün Görseli" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                </div>
            @endif
            <input type="file" id="UrunResimPath" name="UrunResimPath" class="form-control">
            <small style="color: #64748b;">Yeni resim yüklemek isterseniz seçin.</small>
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
