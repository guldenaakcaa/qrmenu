@extends('admin.layouts.app')

@section('title', 'Ürünler')
@section('header_title', 'Ürünler')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h3>Ürün Listesi</h3>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Yeni Ürün
        </a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Görsel</th>
                    <th>Ürün Adı</th>
                    <th>Kategori</th>
                    <th>Fiyat</th>
                    <th style="width: 150px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        @if($product->UrunResimPath)
                            <img src="{{ asset('storage/' . $product->UrunResimPath) }}" alt="{{ $product->UrunAd }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 50px; height: 50px; background-color: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $product->UrunAd }}</td>
                    <td>{{ isset($categories[$product->UrunGrubu_id]) ? $categories[$product->UrunGrubu_id]->Urungrubu : 'Kategori Yok' }}</td>
                    <td>₺{{ number_format((float)$product->FixFiyat, 2) }}</td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-icon edit" title="Düzenle">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete" title="Sil">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($products->count() == 0)
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem;">Henüz kayıtlı ürün bulunmamaktadır.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
