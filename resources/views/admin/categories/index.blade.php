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
                @if($categories->count() == 0)
                <tr>
                    <td colspan="3" style="text-align: center; padding: 2rem;">Henüz kayıtlı kategori bulunmamaktadır.</td>
                </tr>
                @else
                    @foreach($categoriesByMain as $mainName => $subCategories)
                        @php $slug = \Illuminate\Support\Str::slug($mainName ?: 'diger'); @endphp
                        <tr class="accordion-header" style="background-color: #f8fafc; cursor: pointer; border-bottom: 2px solid #e2e8f0;" onclick="toggleSubcats('{{ $slug }}')">
                            <td colspan="3" style="font-size: 1.05rem; padding: 1rem;">
                                <i class="fa-solid fa-chevron-right" id="icon-{{ $slug }}" style="transition: transform 0.3s; margin-right: 8px; color: #64748b;"></i> 
                                <strong style="color: #1e293b;">{{ $mainName ?: 'Diğer / Ana Grup Yok' }}</strong>
                                <span style="float: right; font-size: 0.85rem; color: #94a3b8; background: #e2e8f0; padding: 2px 8px; border-radius: 12px;">{{ count($subCategories) }} Alt Kategori</span>
                            </td>
                        </tr>
                        @foreach($subCategories as $category)
                        <tr class="subcat-row-{{ $slug }}" style="display: none; background-color: #ffffff;">
                            <td style="width: 1%; white-space: nowrap; padding-left: 2rem; border-left: 3px solid #cbd5e1;">{{ $category->Sirano }}</td>
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
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleSubcats(slug) {
        const rows = document.querySelectorAll('.subcat-row-' + slug);
        const icon = document.getElementById('icon-' + slug);
        let isExpanded = false;

        rows.forEach(row => {
            if (row.style.display === 'none' || row.style.display === '') {
                row.style.display = 'table-row';
                isExpanded = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (isExpanded) {
            icon.style.transform = 'rotate(90deg)';
        } else {
            icon.style.transform = 'rotate(0deg)';
        }
    }
</script>
@endsection
