@extends('admin.layouts.app')

@section('title', 'Ürünler')
@section('header_title', 'Ürünler')

@section('content')
<div class="table-container">
    <div class="table-header" style="flex-wrap: wrap; gap: 1rem;">
        <h3 style="margin: 0;">Ürün Listesi</h3>
        
        <div style="flex-grow: 1; max-width: 400px; position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" id="adminProductSearch" onkeyup="filterAdminProducts()" placeholder="Ürün adı ile ara..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.95rem; outline: none;">
        </div>

        <a href="{{ route('products.create') }}" class="btn btn-primary" style="white-space: nowrap;">
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
                    <th style="text-align: center;">Öne Çıkan</th>
                    <th style="width: 150px;">İşlemler</th>
                </tr>
            </thead>
            <tbody id="adminProductsTableBody">
                @foreach($products as $product)
                <tr class="product-row">
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
                    <td style="text-align: center;">
                        <label class="switch">
                            <input type="checkbox" onchange="toggleFeatured({{ $product->id }}, this)" {{ $product->one_cikan ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </td>
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
                    <td colspan="6" style="text-align: center; padding: 2rem;">Henüz kayıtlı ürün bulunmamaktadır.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<style>
/* Toggle Switch CSS */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #cbd5e1;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
}

input:checked + .slider {
  background-color: #4f46e5;
}

input:focus + .slider {
  box-shadow: 0 0 1px #4f46e5;
}

input:checked + .slider:before {
  transform: translateX(20px);
}

.slider.round {
  border-radius: 24px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<script>
function filterAdminProducts() {
    const query = document.getElementById('adminProductSearch').value.toLocaleLowerCase('tr-TR');
    const rows = document.querySelectorAll('.product-row');
    
    rows.forEach(row => {
        const productName = row.cells[1].textContent.toLocaleLowerCase('tr-TR');
        if (productName.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function toggleFeatured(productId, checkbox) {
    fetch(`/admin/products/${productId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(!data.success) {
            alert('Bir hata oluştu!');
            checkbox.checked = !checkbox.checked;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('İşlem sırasında hata oluştu.');
        checkbox.checked = !checkbox.checked;
    });
}
</script>
@endsection
