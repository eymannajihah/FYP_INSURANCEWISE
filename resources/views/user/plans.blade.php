@extends('layouts.app')

@section('content')

<style>
  /* Full-page background */
  .plans-background {
    min-height: calc(100vh - 70px); /* minus navbar height */
    padding: 80px 0;
    background-image: url("{{ asset('image/requestform.jpeg') }}");
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
  }

  /* Plan cards - larger */
  .plan-card {
      border-radius: 15px;
      transition: all 0.25s ease;
      cursor: pointer;
      border: 2px solid transparent;
      background-color: #fff;
      padding: 20px;
      height: 230px; /* increased height */
      display: flex;
      flex-direction: column;
      justify-content: space-between;
  }

  .plan-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.15);
  }

  .plan-card.selected {
      border-color: #dc3545;
      background-color: #fff5f5;
  }

  .category-badge {
      font-size: 13px;
      padding: 6px 12px;
      border-radius: 20px;
      background: #f1f1f1;
      color: #555;
      display: inline-block;
      margin-top: 10px;
  }

  .compare-btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
  }

  .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
  }
</style>

<!-- FULL PAGE BACKGROUND -->
<div class="plans-background">
  <div class="container my-5">

      <h1 class="text-center mb-3 fw-bold">Compare Insurance Plans</h1>
      <p class="text-center text-muted mb-4">
          Select <strong>2 to 3 plans</strong> to see a detailed comparison
      </p>

      <form method="POST" action="{{ route('plans.compare') }}">
          @csrf

          <div class="row g-4">
              @foreach($plans as $id => $plan)
                  <div class="col-md-4">
                      <div class="card plan-card h-100" onclick="toggleCard('{{ $id }}')">
                          <div class="card-body">

                              <div class="form-check mb-2">
                                  <input
                                      class="form-check-input plan-checkbox"
                                      type="checkbox"
                                      name="plans[]"
                                      value="{{ $id }}"
                                      id="plan_{{ $id }}"
                                      onclick="event.stopPropagation(); updateSelection()"
                                  >
                                  <label class="form-check-label fw-semibold" for="plan_{{ $id }}">
                                      {{ $plan['name'] }}
                                  </label>
                              </div>

                              <span class="category-badge">
                                  {{ $plan['category'] ?? 'General' }}
                              </span>

                          </div>
                      </div>
                  </div>
              @endforeach
          </div>

          <div class="text-center mt-4">
              <button
                  type="submit"
                  class="btn btn-danger px-5 py-2 compare-btn"
                  id="compareBtn"
                  disabled
              >
                  Compare Selected Plans
              </button>
          </div>
      </form>
  </div>
</div>

<script>
    const maxSelection = 3;

    function toggleCard(id) {
        const checkbox = document.getElementById('plan_' + id);
        checkbox.checked = !checkbox.checked;
        updateSelection();
    }

    function updateSelection() {
        const checkboxes = document.querySelectorAll('.plan-checkbox');
        const checked = document.querySelectorAll('.plan-checkbox:checked');

        // Limit to max 3
        if (checked.length > maxSelection) {
            checked[checked.length - 1].checked = false;
            alert('You can compare a maximum of 3 plans.');
            return;
        }

        // Update card UI
        checkboxes.forEach(cb => {
            const card = cb.closest('.plan-card');
            card.classList.toggle('selected', cb.checked);
        });

        // Enable button only if 2 or more selected
        document.getElementById('compareBtn').disabled = checked.length < 2;
    }
</script>

@endsection
