@extends('admin.layouts.app')

@section('title', 'Yeni Ürün')
@section('header_title', 'Yeni Ürün Ekle')

@section('content')
<div class="card" style="max-width: 800px;">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="UrunGrubu_id">Kategori</label>
            <select id="UrunGrubu_id" name="UrunGrubu_id" class="form-control" required>
                <option value="">Kategori Seçin</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->Urungrubu }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="UrunAd">Ürün Adı</label>
            <input type="text" id="UrunAd" name="UrunAd" class="form-control" required placeholder="Örn: Klasik Burger">
        </div>
        
        <div class="form-group">
            <label for="UrunAciklama">Ürün Açıklaması</label>
            <textarea id="UrunAciklama" name="UrunAciklama" class="form-control" placeholder="Örn: 150gr dana köfte, karamelize soğan, cheddar peyniri..."></textarea>
        </div>

        <div class="form-group">
            <label for="FixFiyat">Fiyat (₺)</label>
            <input type="number" step="0.01" id="FixFiyat" name="FixFiyat" class="form-control" placeholder="Örn: 150.00">
        </div>

        <div class="form-group">
            <label for="UrunResimPath">Ürün Görseli (URL veya Path)</label>
            <input type="text" id="UrunResimPath" name="UrunResimPath" class="form-control" placeholder="Örn: images/burger.jpg">
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Kaydet</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
