@extends('admin.layouts.app')

@section('title', 'Ürün Düzenle')
@section('header_title', 'Ürün Düzenle')

@section('content')
<div class="card" style="max-width: 800px;">
    <form action="{{ route('products.update', $product->id) }}" method="POST">
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

        <div class="form-group">
            <label for="FixFiyat">Fiyat (₺)</label>
            <input type="number" step="0.01" id="FixFiyat" name="FixFiyat" class="form-control" value="{{ $product->FixFiyat }}">
        </div>

        <div class="form-group">
            <label for="UrunResimPath">Ürün Görseli (URL veya Path)</label>
            <input type="text" id="UrunResimPath" name="UrunResimPath" class="form-control" value="{{ $product->UrunResimPath }}">
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
