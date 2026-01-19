@extends('layouts.app')

@section('content')

<style>
/* ---------------- Body & Main Container ---------------- */
.main-dashboard {
    padding: 80px 20px;
    background-color: #f8f9fa;
    min-height: 100vh;
}

/* ---------------- Hero Section ---------------- */
.hero-section {
    position: relative;
    background-image: url("{{ asset('image/dashboard.jpeg') }}");
    background-size: cover;
    background-position: center;
    color: white;
    text-align: center;
    padding: 120px 20px;
    border-radius: 12px;
    margin-bottom: 60px;
}

.hero-section::before {
    content: "";
    position: absolute;
    inset: 0;
    background-color: rgba(0,0,0,0.5);
    border-radius: 12px;
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 700px;
    margin: 0 auto;
}

.hero-content h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 15px;
}

.hero-content p {
    font-size: 18px;
    margin-bottom: 30px;
}

.btn-hero {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 14px 32px;
    font-size: 18px;
    font-weight: 600;
    border-radius: 8px;
    transition: 0.3s;
}

.btn-hero:hover {
    background-color: #c0392b;
}

/* ---------------- Insurance Categories ---------------- */
.category-section {
    max-width: 1200px;
    margin: 0 auto 60px auto;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
}

.card-category {
    flex: 1 1 280px;
    height: 220px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    text-decoration: none;
    color: #2c3e50;
}

.card-category:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.18);
}

.card-category h3 {
    font-size: 36px;
    font-weight: 700;
    margin: 0;
}

.card-category p {
    font-size: 16px;
    font-weight: 600;
    margin: 5px 0 0 0;
}

/* ---------------- Quote Section ---------------- */
.quote-section {
    position: relative;
    background-image: url("{{ asset('image/requestform.jpeg') }}");
    background-size: cover;
    background-position: center;
    border-radius: 12px;
    padding: 80px 20px;
    text-align: center;
    color: white;
}

.quote-section::before {
    content: "";
    position: absolute;
    inset: 0;
    background-color: rgba(0,0,0,0.5);
    border-radius: 12px;
}

.quote-section .content {
    position: relative;
    z-index: 1;
    max-width: 700px;
    margin: 0 auto;
}

.quote-section h2 {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 15px;
}

.quote-section p {
    font-size: 18px;
    margin-bottom: 25px;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-content h1, .quote-section h2 { font-size: 32px; }
    .card-category h3 { font-size: 28px; }
}
</style>

<div class="main-dashboard">

    <!-- Hero -->
    <div class="hero-section">
        <div class="hero-content">
            <h1>Welcome to InsuranceWise</h1>
            <p>Your personalized insurance insight dashboard</p>
            <a href="{{ route('recommendationform') }}" class="btn btn-hero">Get Personalized Recommendations</a>
        </div>
    </div>

    <!-- Categories -->
    <div class="category-section">
        <a href="{{ route('categories.view', ['category' => 'medical']) }}" class="card-category">
            <h3>{{ $planCounts['medical'] ?? 0 }}</h3>
            <p>Medical Insurance</p>
        </a>
        <a href="{{ route('categories.view', ['category' => 'critical']) }}" class="card-category">
            <h3>{{ $planCounts['critical'] ?? 0 }}</h3>
            <p>Critical Illness Insurance</p>
        </a>
        <a href="{{ route('categories.view', ['category' => 'life']) }}" class="card-category">
            <h3>{{ $planCounts['life'] ?? 0 }}</h3>
            <p>Life Insurance</p>
        </a>
    </div>

    <!-- Quote -->
    <div class="quote-section">
        <div class="content">
            <h2>Still unsure?</h2>
            <p>Get a quote now. We are ready to help you.</p>
            <a href="{{ url('/quote-request') }}" class="btn btn-hero">Get Quote</a>
        </div>
    </div>

</div>

@endsection
