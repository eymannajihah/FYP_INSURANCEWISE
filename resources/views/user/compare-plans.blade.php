@extends('layouts.app')

@section('content')

<style>
  /* Full-page background */
  .compare-background {
      min-height: calc(100vh - 70px); /* minus navbar */
      padding: 80px 0;
      background-image: url("{{ asset('image/requestform.jpeg') }}");
      background-repeat: no-repeat;
      background-position: center center;
      background-size: cover;
  }

  /* Container for cards */
  .compare-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 25px;
  }

  /* Individual cards */
  .compare-card {
      background-color: #ffffff;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      padding: 20px;
      width: 320px;
      min-height: 450px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      transition: transform 0.2s ease-in-out;
  }

  .compare-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.15);
  }

  .compare-card .card-header {
      background-color: #dc3545;
      color: #fff;
      border-radius: 12px 12px 0 0;
      text-align: center;
      padding: 12px 10px;
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 15px;
  }

  .compare-card .card-body p,
  .compare-card .card-body ul {
      font-size: 0.95rem;
      color: #2c3e50;
  }

  .compare-card .card-body ul {
      padding-left: 20px;
  }

  .back-btn {
      margin-top: 30px;
  }
</style>

<div class="compare-background">
    <div class="container my-5">
        <h1 class="text-center mb-4 fw-bold text-dark">Compare Plans</h1>

        @if(empty($plans))
            <div class="alert alert-warning text-center">
                No plans selected.
            </div>
        @else
            <div class="compare-container">
                @foreach($plans as $plan)
                    <div class="compare-card">

                        <div class="card-header">
                            {{ $plan['name'] }}
                        </div>

                        <div class="card-body">
                            <p><strong>Annual Limit:</strong><br>
                                {{ $plan['annual_limit'] ?? '-' }}
                            </p>

                            <p><strong>Lifetime Limit:</strong><br>
                                {{ $plan['lifetime_limit'] ?? '-' }}
                            </p>

                            <p><strong>Hospitalisation Benefit:</strong><br>
                                {{ $plan['hospitalisation'] ?? '-' }}
                            </p>

                            <hr>

                            <strong>Benefits / Features:</strong>
                            @if(!empty($plan['benefits']))
                                <ul class="mt-2">
                                    @foreach(explode("\n", $plan['benefits']) as $benefit)
                                        @if(trim($benefit) !== '')
                                            <li>{{ $benefit }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p>-</p>
                            @endif

                            <hr>

                            <strong>Eligibility:</strong>
                            <ul class="mt-2">
                                <li><strong>Entry Age:</strong> {{ $plan['entry_age'] ?? '-' }}</li>
                                <li><strong>Nationality:</strong> {{ $plan['nationality'] ?? '-' }}</li>
                                <li><strong>Coverage Age:</strong> {{ $plan['coverage_age'] ?? '-' }}</li>
                                <li><strong>Coverage Term:</strong> {{ $plan['coverage_term'] ?? '-' }}</li>
                            </ul>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="text-center back-btn">
                <a href="{{ route('plans') }}" class="btn btn-secondary px-4 py-2">
                    ← Back to Plan Selection
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
