@extends('admin.layouts.app')

@section('title', 'Yeni Ana Grup')
@section('header_title', 'Yeni Ana Grup Ekle')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('main-categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="anaGrup">Ana Grup Adı</label>
            <input type="text" id="anaGrup" name="anaGrup" class="form-control" required placeholder="Örn: İçecekler">
        </div>
        
        <div class="form-group">
            <label for="siraNo">Sıra No</label>
            <input type="number" id="siraNo" name="siraNo" class="form-control" placeholder="Örn: 1" value="{{ old('siraNo') }}">
            @error('siraNo')
                <span style="color: #e11d48; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Görsel (İsteğe Bağlı)</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            <small style="color: #64748b; margin-top: 5px; display: block;">Müşterilerin üst menüde göreceği görsel.</small>
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Kaydet</button>
            <a href="{{ route('main-categories.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
