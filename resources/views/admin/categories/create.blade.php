@extends('admin.layouts.app')

@section('title', 'Yeni Kategori')
@section('header_title', 'Yeni Kategori Ekle')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="Urungrubu">Kategori Adı</label>
            <input type="text" id="Urungrubu" name="Urungrubu" class="form-control" required placeholder="Örn: Tatlılar">
        </div>
        
        <div class="form-group">
            <label for="Sirano">Sıra No</label>
            <input type="number" id="Sirano" name="Sirano" class="form-control" placeholder="Örn: 1" value="{{ old('Sirano') }}">
            @error('Sirano')
                <span style="color: #e11d48; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Kaydet</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
