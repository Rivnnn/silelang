@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Petugas Lelang')

@section('content')
<style>
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
    }

    .alert.success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert.info {
        background: #d1ecf1;
        color: #0c5460;
        border-left: 4px solid #17a2b8;
    }

    /* ================= TABLE BOX ================= */
    .table-box {
        background: #fff;
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .table-header h3 {
        color: #2c3e50;
        font-size: 20px;
        font-weight: 600;
    }

    .search-box {
        display: flex;
        gap: 10px;
    }

    .search-box input {
        padding: 10px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        width: 280px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }

    /* ================= TABLE ================= */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: linear-gradient(135deg, #34495e, #2c3e50);
    }

    th {
        padding: 14px;
        text-align: left;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    td {
        padding: 16px 14px;
        color: #2c3e50;
        font-size: 14px;
    }

    /* ================= BADGES ================= */
    .badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge.active {
        background: #d4edda;
        color: #155724;
    }

    .badge.inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .badge.petugas {
        background: #d1ecf1;
        color: #0c5460;
    }

    /* ================= ACTION BUTTONS ================= */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-toggle {
        background: #ffc107;
        color: #856404;
    }

    .btn-toggle:hover {
        background: #e0a800;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255,193,7,0.3);
    }

    .btn-delete {
        background: #dc3545;
        color: #fff;
    }

    .btn-delete:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220,53,69,0.3);
    }

    .btn-activate {
        background: #28a745;
        color: #fff;
    }

    .btn-activate:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(40,167,69,0.3);
    }

    /* ================= EMPTY STATE ================= */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95a5a6;
    }

    .empty-state span {
        font-size: 64px;
        display: block;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #7f8c8d;
    }

    .empty-state p {
        font-size: 14px;
    }

    /* ================= MODAL ================= */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-box {
        background: #fff;
        border-radius: 16px;
        width: 90%;
        max-width: 480px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
        overflow: hidden;
    }

    .modal-header {
        padding: 24px 28px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-header.success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #fff;
    }

    .modal-header.warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: #fff;
    }

    .modal-header.danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
    }

    .modal-header .icon {
        font-size: 32px;
    }

    .modal-header h3 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .modal-body {
        padding: 28px;
    }

    .modal-body .user-info {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        margin: 16px 0;
    }

    .modal-body .user-info p {
        margin: 8px 0;
        font-size: 14px;
        color: #495057;
    }

    .modal-body .user-info strong {
        color: #2c3e50;
    }

    .modal-footer {
        padding: 20px 28px;
        background: #f8f9fa;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .modal-btn {
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modal-btn-cancel {
        background: #6c757d;
        color: #fff;
    }

    .modal-btn-cancel:hover {
        background: #5a6268;
    }

    .modal-btn-confirm {
        background: #28a745;
        color: #fff;
    }

    .modal-btn-confirm:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40,167,69,0.3);
    }

    .modal-btn-confirm.danger {
        background: #dc3545;
    }

    .modal-btn-confirm.danger:hover {
        background: #c82333;
        box-shadow: 0 4px 12px rgba(220,53,69,0.3);
    }

    .modal-btn-confirm.warning {
        background: #ffc107;
        color: #856404;
    }

    .modal-btn-confirm.warning:hover {
        background: #e0a800;
        box-shadow: 0 4px 12px rgba(255,193,7,0.3);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(30px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@if(session('success'))
    <div class="alert success">
        <span>✓</span> {{ session('success') }}
    </div>
@endif

<div class="alert info">
    <span>ℹ️</span>
    <div>
        <strong>Informasi:</strong> User baru yang mendaftar akan otomatis non-aktif. 
        Admin harus mengaktifkan manual untuk memberikan akses.
    </div>
</div>

<div class="table-box">
    <div class="table-header">
        <h3>Daftar Petugas</h3>
        <div class="search-box">
            <input type="text" id="searchUser" placeholder="🔍 Cari nama atau email..." onkeyup="filterUsers()">
        </div>
    </div>

    <table id="userTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $user->name }}</strong></td>
                <td>{{ $user->email }}</td>
                <td><span class="badge petugas">{{ ucfirst($user->role) }}</span></td>
                <td>
                    @if($user->is_active)
                        <span class="badge active">Aktif</span>
                    @else
                        <span class="badge inactive">Non-Aktif</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div class="action-buttons">
                        @if($user->is_active)
                            <button type="button" class="btn btn-toggle" 
                                    onclick="showModal('deactivate', {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')">
                                🔒 Nonaktifkan
                            </button>
                        @else
                            <button type="button" class="btn btn-activate"
                                    onclick="showModal('activate', {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')">
                                ✓ Aktifkan
                            </button>
                        @endif

                        <button type="button" class="btn btn-delete" 
                                onclick="showModal('delete', {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')">
                            🗑️ Hapus
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <span>👥</span>
                        <h4>Belum Ada Petugas Terdaftar</h4>
                        <p>Petugas yang mendaftar akan muncul di sini</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Aktifkan User -->
<div id="modalActivate" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header success">
            <span class="icon">✓</span>
            <h3>Terima & Aktifkan Petugas</h3>
        </div>
        <div class="modal-body">
            <p>Anda akan <strong>mengaktifkan</strong> petugas berikut:</p>
            <div class="user-info">
                <p><strong>Nama:</strong> <span id="activateUserName"></span></p>
                <p><strong>Email:</strong> <span id="activateUserEmail"></span></p>
            </div>
            <p>Setelah diaktifkan, petugas ini dapat login dan mengakses sistem.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('modalActivate')">
                Batal
            </button>
            <button type="button" class="modal-btn modal-btn-confirm" onclick="submitForm('formActivate')">
                ✓ Ya, Aktifkan
            </button>
        </div>
    </div>
</div>

<!-- Modal Nonaktifkan User -->
<div id="modalDeactivate" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header warning">
            <span class="icon">⚠️</span>
            <h3>Tolak & Nonaktifkan Petugas</h3>
        </div>
        <div class="modal-body">
            <p>Anda akan <strong>menonaktifkan</strong> petugas berikut:</p>
            <div class="user-info">
                <p><strong>Nama:</strong> <span id="deactivateUserName"></span></p>
                <p><strong>Email:</strong> <span id="deactivateUserEmail"></span></p>
            </div>
            <p>Petugas yang dinonaktifkan tidak dapat login ke sistem.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('modalDeactivate')">
                Batal
            </button>
            <button type="button" class="modal-btn modal-btn-confirm warning" onclick="submitForm('formDeactivate')">
                🔒 Ya, Nonaktifkan
            </button>
        </div>
    </div>
</div>

<!-- Modal Hapus User -->
<div id="modalDelete" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header danger">
            <span class="icon">🗑️</span>
            <h3>Hapus Petugas</h3>
        </div>
        <div class="modal-body">
            <p>Anda akan <strong>menghapus permanen</strong> petugas berikut:</p>
            <div class="user-info">
                <p><strong>Nama:</strong> <span id="deleteUserName"></span></p>
                <p><strong>Email:</strong> <span id="deleteUserEmail"></span></p>
            </div>
            <p><strong>⚠️ Perhatian:</strong> Aksi ini tidak dapat dibatalkan. Data yang dibuat oleh petugas akan tetap ada.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal('modalDelete')">
                Batal
            </button>
            <button type="button" class="modal-btn modal-btn-confirm danger" onclick="submitForm('formDelete')">
                🗑️ Ya, Hapus
            </button>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="formActivate" method="POST" style="display: none;">
    @csrf
</form>

<form id="formDeactivate" method="POST" style="display: none;">
    @csrf
</form>

<form id="formDelete" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function filterUsers() {
    const input = document.getElementById('searchUser').value.toLowerCase();
    const table = document.getElementById('userTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        if (cells.length > 1) {
            const name = cells[1].textContent.toLowerCase();
            const email = cells[2].textContent.toLowerCase();
            
            if (name.includes(input) || email.includes(input)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}

function showModal(action, userId, userName, userEmail) {
    if (action === 'activate') {
        document.getElementById('activateUserName').textContent = userName;
        document.getElementById('activateUserEmail').textContent = userEmail;
        document.getElementById('formActivate').action = `/admin/manajemen-user/${userId}/toggle`;
        document.getElementById('modalActivate').classList.add('active');
    } 
    else if (action === 'deactivate') {
        document.getElementById('deactivateUserName').textContent = userName;
        document.getElementById('deactivateUserEmail').textContent = userEmail;
        document.getElementById('formDeactivate').action = `/admin/manajemen-user/${userId}/toggle`;
        document.getElementById('modalDeactivate').classList.add('active');
    }
    else if (action === 'delete') {
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteUserEmail').textContent = userEmail;
        document.getElementById('formDelete').action = `/admin/manajemen-user/${userId}`;
        document.getElementById('modalDelete').classList.add('active');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function submitForm(formId) {
    document.getElementById(formId).submit();
}

// Close modal when clicking outside
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.classList.remove('active');
        });
    }
});
</script>
@endpush