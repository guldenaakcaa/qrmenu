@extends('admin.layouts.app')

@section('title', 'Kategoriler')
@section('header_title', 'Kategoriler')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h3>Kategori Listesi</h3>
        @if(session('admin_role') == '0')
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Yeni Kategori
        </a>
        @endif
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 1%; white-space: nowrap;">Sıra No</th>
                    <th>Kategori Adı</th>
                    @if(session('admin_role') == '0')
                    <th style="width: 150px;">İşlemler</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td style="width: 1%; white-space: nowrap;">{{ $category->Sirano }}</td>
                    <td>{{ $category->Urungrubu }}</td>
                    @if(session('admin_role') == '0')
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn-icon edit" title="Düzenle">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon delete" title="Sil">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
                @if($categories->count() == 0)
                <tr>
                    <td colspan="3" style="text-align: center; padding: 2rem;">Henüz kayıtlı kategori bulunmamaktadır.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
