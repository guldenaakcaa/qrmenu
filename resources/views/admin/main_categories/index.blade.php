@extends('admin.layouts.app')

@section('title', 'Ana Gruplar')
@section('header_title', 'Ana Gruplar (Ana Kategoriler)')

@section('content')
<div class="table-container">
    <div class="table-header">
        <h3>Ana Grup Listesi</h3>
        @if(session('admin_role') == '0')
        <a href="{{ route('main-categories.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Yeni Ana Grup
        </a>
        @endif
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 1%; white-space: nowrap;">Sıra No</th>
                    <th style="width: 80px;">Görsel</th>
                    <th>Ana Grup Adı</th>
                    @if(session('admin_role') == '0')
                    <th style="width: 150px;">İşlemler</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($mainCategories as $mg)
                <tr>
                    <td style="width: 1%; white-space: nowrap;">{{ $mg->siraNo }}</td>
                    <td>
                        @if($mg->anaGrupResimPath)
                            <img src="{{ asset('storage/' . $mg->anaGrupResimPath) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 50px; height: 50px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #cbd5e1;">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight: 600;">{{ $mg->anaGrup }}</td>
                    @if(session('admin_role') == '0')
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('main-categories.edit', $mg->id) }}" class="btn-icon edit" title="Düzenle">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('main-categories.destroy', $mg->id) }}" method="POST" onsubmit="return confirm('Bu ana grubu silmek istediğinize emin misiniz? Altındaki kategoriler silinmez.');" style="display:inline-block;">
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
                @if($mainCategories->count() == 0)
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem;">Henüz kayıtlı ana grup bulunmamaktadır.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
