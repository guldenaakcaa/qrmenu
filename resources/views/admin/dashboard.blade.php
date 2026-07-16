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
            <p>12</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-burger"></i>
        </div>
        <div class="stat-info">
            <h3>Toplam Ürün</h3>
            <p>148</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fa-solid fa-eye"></i>
        </div>
        <div class="stat-info">
            <h3>Menü Görüntülenmesi</h3>
            <p>3,402</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <div class="stat-info">
            <h3>Aktif Masalar</h3>
            <p>24</p>
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
                <tr>
                    <td>Klasik Burger</td>
                    <td>Ana Yemekler</td>
                    <td>₺180.00</td>
                    <td><span class="status active" style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background-color: #dcfce7; color: #166534;">Aktif</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-icon edit" title="Düzenle"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn-icon delete" title="Sil"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
