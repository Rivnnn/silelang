@extends('layouts.petugas')

@section('title', 'Nomor Surat')
@section('page-title', 'Nomor Surat')

@section('content')
<style>
    /* ================= PAGE HEADER ================= */
    .page-header {
        margin-bottom: 40px;
        text-align: center;
    }

    .page-header h3 {
        color: #2c3e50;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .page-header h3::before,
    .page-header h3::after {
        content: '';
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #39C6C9, transparent);
    }

    .page-header p {
        color: #7f8c8d;
        font-size: 15px;
        line-height: 1.6;
        max-width: 600px;
        margin: 0 auto;
    }

    /* ================= CARDS GRID ================= */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 28px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .card {
        background: #fff;
        padding: 40px 32px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: #2c3e50;
        position: relative;
        overflow: hidden;
        border: 2px solid transparent;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--card-gradient);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(57, 198, 201, 0.1), transparent);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .card:hover {
        transform: translateY(-12px);
        box-shadow: 0 16px 40px rgba(57, 198, 201, 0.25);
        border-color: rgba(57, 198, 201, 0.3);
    }

    .card:hover::before {
        transform: scaleX(1);
    }

    .card:hover::after {
        width: 300px;
        height: 300px;
    }

    .card:active {
        transform: translateY(-8px);
    }

    /* Card Variations */
    .card.card-1 { --card-gradient: linear-gradient(90deg, #39C6C9, #2FB3B6); }
    .card.card-2 { --card-gradient: linear-gradient(90deg, #667eea, #764ba2); }
    .card.card-3 { --card-gradient: linear-gradient(90deg, #f093fb, #f5576c); }

    .card-icon {
        font-size: 64px;
        display: block;
        margin-bottom: 20px;
        transition: all 0.4s ease;
        position: relative;
        z-index: 1;
    }

    .card:hover .card-icon {
        transform: scale(1.15) rotate(5deg);
    }

    .card h3 {
        color: #2c3e50;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 12px;
        position: relative;
        z-index: 1;
        transition: color 0.3s ease;
    }

    .card:hover h3 {
        color: #39C6C9;
    }

    .card p {
        font-size: 14px;
        color: #7f8c8d;
        line-height: 1.6;
        position: relative;
        z-index: 1;
        margin: 0;
    }

    .card-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        background: linear-gradient(135deg, #39C6C9, #2FB3B6);
        color: #fff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.3s ease;
        z-index: 1;
    }

    .card:hover .card-badge {
        opacity: 1;
        transform: translateX(0);
    }

    /* ================= INFO BOX ================= */
    .info-box {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        padding: 18px 24px;
        border-radius: 12px;
        margin-bottom: 32px;
        color: #01579b;
        border-left: 4px solid #2196f3;
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.1);
        display: flex;
        align-items: center;
        gap: 14px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .info-box .icon {
        font-size: 28px;
        flex-shrink: 0;
    }

    .info-box .content {
        flex: 1;
    }

    .info-box .content strong {
        display: block;
        margin-bottom: 4px;
        font-size: 15px;
        font-weight: 700;
    }

    .info-box .content p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .page-header h3 {
            font-size: 22px;
        }

        .page-header h3::before,
        .page-header h3::after {
            width: 30px;
        }

        .cards {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .card {
            padding: 32px 24px;
        }

        .card-icon {
            font-size: 56px;
        }
    }

    /* ================= ANIMATIONS ================= */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: fadeInUp 0.6s ease forwards;
    }

    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }
</style>

<!-- Page Header -->
<div class="page-header">
    <h3>Pilih Jenis Dokumen</h3>
    <p>Generate nomor surat otomatis sesuai dengan jenis dokumen yang Anda butuhkan</p>
</div>

<!-- Info Box -->
<div class="info-box">
    <div class="icon">💡</div>
    <div class="content">
        <strong>Sistem Penomoran Otomatis</strong>
        <p>Setiap dokumen akan mendapatkan nomor unik secara otomatis berdasarkan tanggal dan urutan pembuatan</p>
    </div>
</div>

<!-- Cards -->
<div class="cards">
    <a href="{{ route('petugas.surat.keluar.index') }}" class="card card-1">
        <span class="card-badge">Dokumen</span>
        <span class="card-icon">📄</span>
        <h3>Surat Keluar</h3>
        <p>Generate nomor surat keluar dengan format otomatis untuk korespondensi eksternal</p>
    </a>

    <a href="{{ route('petugas.surat.memo.index') }}" class="card card-2">
        <span class="card-badge">Internal</span>
        <span class="card-icon">📝</span>
        <h3>Memo</h3>
        <p>Generate nomor memo internal untuk komunikasi antar divisi dan departemen</p>
    </a>

    <a href="{{ route('petugas.surat.nota.index') }}" class="card card-3">
        <span class="card-badge">Dinas</span>
        <span class="card-icon">🧾</span>
        <h3>Nota Dinas</h3>
        <p>Generate nomor nota dinas untuk keperluan administrasi dan dokumentasi</p>
    </a>
</div>
@endsection