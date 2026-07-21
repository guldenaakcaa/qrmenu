@extends('admin.layouts.app')

@section('title', 'Yöneticiler')
@section('header_title', 'Sistem Yöneticileri')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="table-container">
    <div class="table-header">
        <h3>Kayıtlı Yöneticiler</h3>
        <button class="btn btn-primary" onclick="openModal('addAdminModal')">
            <i class="fa-solid fa-plus"></i> Yeni Yönetici Ekle
        </button>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>İSİM</th>
                    <th>E-POSTA ADRESİ</th>
                    <th>ROL</th>
                    <th>İŞLEMLER</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                    <tr>
                        <td>#{{ $admin->id }}</td>
                        <td style="font-weight: 500;">{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @if($admin->kullanicitipi == 0)
                                <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Admin</span>
                            @else
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Garson (Personel)</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns">
                                <button type="button" class="btn-icon edit" onclick='openEditModal(@json($admin))' title="Düzenle">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                @if(session('admin_id') != $admin->id)
                                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Bu yöneticiyi silmek istediğinize emin misiniz?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon delete" title="Sil">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal CSS -->
<style>
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);
        display: none; justify-content: center; align-items: center; z-index: 1000;
        opacity: 0; transition: opacity 0.3s ease;
    }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-card {
        background: #fff; width: 90%; max-width: 500px; border-radius: 12px; padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        transform: translateY(-20px); transition: transform 0.3s ease;
    }
    .modal-overlay.active .modal-card { transform: translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .modal-header h3 { font-size: 1.25rem; margin: 0; color: var(--text-main); }
    .btn-close { background: none; border: none; font-size: 1.25rem; color: var(--text-muted); cursor: pointer; }
</style>

<!-- Add Admin Modal -->
<div class="modal-overlay" id="addAdminModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Yeni Yönetici Ekle</h3>
            <button class="btn-close" onclick="closeModal('addAdminModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>İsim Soyisim</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>E-posta Adresi</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="form-group">
                <label>Yetki Seçiniz</label>
                <select name="kullanicitipi" class="form-control" required>
                    <option value="0">Admin (Tam Yetkili)</option>
                    <option value="1">Garson (Sadece Okuma)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Şifre Tekrar</label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="6">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 2rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addAdminModal')">İptal</button>
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Admin Modal -->
<div class="modal-overlay" id="editAdminModal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Yönetici Düzenle</h3>
            <button class="btn-close" onclick="closeModal('editAdminModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="editAdminForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>İsim Soyisim</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="form-group">
                <label>E-posta Adresi</label>
                <input type="email" name="email" id="editEmail" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Yetki Seçiniz</label>
                <select name="kullanicitipi" id="editKullaniciTipi" class="form-control" required>
                    <option value="0">Admin (Tam Yetkili)</option>
                    <option value="1">Garson (Sadece Okuma)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Yeni Şifre <small style="color:var(--text-muted); font-weight:normal;">(Değiştirmek istemiyorsanız boş bırakın)</small></label>
                <input type="password" name="password" class="form-control" minlength="6">
            </div>
            <div class="form-group">
                <label>Yeni Şifre Tekrar</label>
                <input type="password" name="password_confirmation" class="form-control" minlength="6">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 2rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editAdminModal')">İptal</button>
                <button type="submit" class="btn btn-primary">Güncelle</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function openEditModal(admin) {
        document.getElementById('editAdminForm').action = '/admin/admins/' + admin.id;
        document.getElementById('editName').value = admin.name;
        document.getElementById('editEmail').value = admin.email;
        document.getElementById('editKullaniciTipi').value = admin.kullanicitipi;
        openModal('editAdminModal');
    }

    // Modal dışına tıklayınca kapatma
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            event.target.classList.remove('active');
        }
    }
</script>
@endsection
