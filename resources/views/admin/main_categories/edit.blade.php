@extends('admin.layouts.app')

@section('title', 'Ana Grup Düzenle')
@section('header_title', 'Ana Grup Düzenle')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('main-categories.update', $mainCategory->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Gizli varsayılan buton (Enter'da yanlışlıkla resmi silmesin) -->
        <button type="submit" style="display: none;" aria-hidden="true"></button>
        
        <div class="form-group">
            <label for="anaGrup">Ana Grup Adı</label>
            <input type="text" id="anaGrup" name="anaGrup" class="form-control" required value="{{ $mainCategory->anaGrup }}">
        </div>
        
        <div class="form-group">
            <label for="siraNo">Sıra No</label>
            <input type="number" id="siraNo" name="siraNo" class="form-control" value="{{ $mainCategory->siraNo }}">
            @error('siraNo')
                <span style="color: #e11d48; font-size: 0.85rem; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Yeni Görsel Yükle (İsteğe Bağlı)</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            <small style="color: #64748b; margin-top: 5px; display: block;">Yeni bir görsel yüklerseniz eskisiyle değişir.</small>
            
            @if($mainCategory->anaGrupResimPath)
                <div style="margin-top: 15px; display: flex; align-items: center; gap: 15px; padding: 15px; border: 1px dashed var(--border-color); border-radius: 8px;">
                    <img src="{{ asset('storage/' . $mainCategory->anaGrupResimPath) }}" style="max-height: 80px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <button type="submit" name="remove_image" value="1" class="btn btn-sm" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; padding: 6px 12px; font-size: 0.85rem; border-radius: 4px; margin-left: auto;" onclick="return confirm('Görseli silmek istediğinize emin misiniz?')">
                        <i class="fa-solid fa-trash"></i> Görseli Sil
                    </button>
                </div>
            @endif
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
            <a href="{{ route('main-categories.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </form>
</div>
@endsection
