@extends('layouts.app')

@section('content')

<style>
/* =========================
   GLOBAL RESET
========================= */
* {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

/* =========================
   ADMIN HEADER SECTION
========================= */
.admin-header {
    height: 60vh;
    background-image: url("/image/admindashboard.jpeg");
    background-size: cover;
    background-position: center;
    position: relative;
}

.admin-header::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);
}

.admin-header-content {
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff;
    text-align: center;
    padding: 20px;
}

.admin-header-content h1 {
    font-size: 48px;
    font-weight: 600;
    margin-bottom: 10px;
}

.admin-header-content p {
    font-size: 18px;
    opacity: 0.9;
}

/* =========================
   SECTION BASE STYLE
========================= */
.section {
    padding: 80px 20px;
}

.section-gray {
    background: #f5f6fa;
}

.section-title {
    text-align: center;
    font-size: 32px;
    font-weight: 600;
    margin-bottom: 50px;
}

/* =========================
   ADMIN STAT CARDS
========================= */
.admin-card-wrapper {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
}

.admin-card {
    width: 320px;
    height: 220px;
    border-radius: 18px;
    padding: 30px;
    color: #fff;
    position: relative;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: transform 0.25s ease;
    cursor: pointer;
}

.admin-card:hover {
    transform: translateY(-8px);
}

.admin-card h4 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 20px;
}

.admin-card h2 {
    font-size: 48px;
    font-weight: 700;
}

/* clickable card */
.card-link {
    position: absolute;
    inset: 0;
    z-index: 5;
}

/* =========================
   GRADIENT COLORS
========================= */
.bg-red {
    background: linear-gradient(135deg, #ff6b6b, #ff8787);
}

.bg-blue {
    background: linear-gradient(135deg, #4d96ff, #7ab3ff);
}

.bg-green {
    background: linear-gradient(135deg, #00c49f, #48e6c3);
}

/* =========================
   ADMIN MANAGEMENT SECTION
========================= */
.admin-management {
    max-width: 1000px;
    margin: auto;
}

.admin-management h3 {
    font-size: 26px;
    font-weight: 600;
    margin-bottom: 20px;
}

.admin-management table {
    width: 100%;
    background: #fff;
    border-radius: 15px;
    border-collapse: collapse;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

.admin-management th,
.admin-management td {
    padding: 16px;
    text-align: left;
}

.admin-management thead {
    background: #f0f2f5;
}

.admin-management tbody tr:not(:last-child) {
    border-bottom: 1px solid #eee;
}
</style>

<!-- =========================
     ADMIN HEADER
========================= -->
<section class="admin-header">
    <div class="admin-header-content">
        <h1>Admin Control Panel</h1>
        <p>Manage system data, insurance plans and quote requests</p>
    </div>
</section>

<!-- =========================
     ADMIN STATISTIC SECTION
========================= -->
<section class="section section-gray">
    <h2 class="section-title">Quick Statistics</h2>

    <div class="admin-card-wrapper">

        <div class="admin-card bg-red">
            <a href="{{ route('quote.assignment') }}" class="card-link"></a>
            <h4>Pending Quote Requests</h4>
            <h2>{{ $pendingCount ?? 0 }}</h2>
        </div>

        <div class="admin-card bg-blue">
            <a href="#" class="card-link"></a>
            <h4>Registered Users</h4>
            <h2>{{ $userCount ?? 0 }}</h2>
        </div>

        <div class="admin-card bg-green">
            <a href="{{ route('admin.manage-plans') }}" class="card-link"></a>
            <h4>Insurance Plans</h4>
            <h2>{{ $planCount ?? 0 }}</h2>
        </div>

    </div>
</section>


@endsection
