@extends('admin.layouts.app')

@section('title', 'Yeni Ürün')
@section('header_title', 'Yeni Ürün Ekle')

@section('content')
<div class="card" style="max-width: 800px;">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
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

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 1.5rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="FixFiyat">Fiyat (₺)</label>
                <input type="number" step="0.01" id="FixFiyat" name="FixFiyat" class="form-control" placeholder="Örn: 150.00">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="kalori">Kalori (kcal)</label>
                <input type="text" id="kalori" name="kalori" class="form-control" placeholder="Örn: 450">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="hazirlanma_suresi">Hazırlanma Süresi</label>
                <input type="text" id="hazirlanma_suresi" name="hazirlanma_suresi" class="form-control" placeholder="Örn: 15-20 dk">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="hidden" name="has_lactose" value="0">
                <input type="checkbox" name="has_lactose" value="1" style="width: 18px; height: 18px;">
                <span style="font-weight: 500; color: #1e293b;">Laktozsuz Seçeneği Bulunmaktadır</span>
            </label>
        </div>

        <div class="form-group">
            <label for="UrunResimPath">Ürün Görseli</label>
            <input type="file" id="UrunResimPath" name="UrunResimPath" class="form-control">
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Kaydet</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
