@extends('layouts.app')

@section('content')



<style>
/* Body & Navbar */
 body {
    background-image: url("{{ asset('image/requestform.jpeg') }}");
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
}
/* Form Section */
.form-section { 
    padding: 100px 20px 60px 20px; /* added top padding to drop below navbar */
    min-height: 100vh; 
    background-color: transparent;
}
.form-container {
    max-width: 850px;
    margin: 0 auto;
    background: #ffffff; /* card stays white for readability */
    border-radius: 15px;
    padding: 50px 40px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
}

/* Headers & Labels */
.form-header { text-align: center; margin-bottom: 40px; }
.form-header h2 { font-weight: 600; font-size: 26px; color: #2c3e50; }
.form-header p { color: #7f8c8d; font-size: 15px; }

/* Inputs */
.formbold-form-label { color: #34495e; font-size: 15px; margin-bottom: 8px; display: block; }
.formbold-form-input, .formbold-form-select {
    width: 100%; padding: 12px 18px; border-radius: 8px;
    border: 1px solid #dde3ec; font-size: 15px; color: #07074d;
    outline: none; transition: 0.3s;
}
.formbold-form-input:focus, .formbold-form-select:focus {
    border-color: #e74c3c; box-shadow: 0px 3px 8px rgba(0,0,0,0.05);
}

/* Groups & spacing */
.formbold-input-group { margin-bottom: 25px; }

/* Buttons */
.formbold-btn {
    width: 100%; font-size: 17px; border-radius: 10px; padding: 14px 25px;
    border: none; font-weight: 500; background-color: #e74c3c; color: white;
    cursor: pointer; margin-top: 25px; transition: 0.3s;
}
.formbold-btn:hover { background-color: #c0392b; }

/* Radio buttons */
.formbold-radio-flex { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 8px; }
.formbold-radio-label {
    position: relative; padding-left: 28px; cursor: pointer;
    color: #34495e; font-size: 15px;
}
.formbold-input-radio { position: absolute; opacity: 0; }
.formbold-radio-checkmark {
    position: absolute; top: 0; left: 0; height: 18px; width: 18px;
    border: 1px solid #dde3ec; border-radius: 50%; background: #fff;
}
.formbold-radio-label .formbold-input-radio:checked ~ .formbold-radio-checkmark {
    background-color: #e74c3c;
}
.formbold-radio-checkmark:after {
    content: ''; position: absolute; display: none;
}
.formbold-radio-label .formbold-input-radio:checked ~ .formbold-radio-checkmark:after {
    display: block; top: 4px; left: 4px; width: 8px; height: 8px;
    border-radius: 50%; background: #fff;
}

/* Error text */
.error-text { color: red; font-size: 13px; margin-top: 3px; }

/* Responsive */
@media (max-width: 768px) {
    .form-container { padding: 30px 20px; max-width: 95%; }
}
</style>

<!-- Form Section -->
<div class="section form-section">
  <div class="container">
    <div class="form-container">
      <div class="form-header">
        <h2>Insurance Recommendation Form</h2>
        <p>Answer a few questions to receive a personalized insurance recommendation.</p>
      </div>

      <form method="POST" action="{{ route('recommendationform.submit') }}">
        @csrf

        <!-- ===== Personal Info ===== -->
        <div class="formbold-input-group">
          <label for="name" class="formbold-form-label">Full Name</label>
          <input type="text" name="name" id="name" class="formbold-form-input" value="{{ old('name') }}" required>
          @error('name') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="formbold-input-group">
          <label for="gender" class="formbold-form-label">Gender</label>
          <select class="formbold-form-select" name="gender" id="gender">
            <option value="">Prefer not to say</option>
            <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>Female</option>
          </select>
          @error('gender') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="formbold-input-group">
          <label for="age" class="formbold-form-label">Age</label>
          <input type="number" name="age" id="age" class="formbold-form-input" value="{{ old('age') }}" required>
          @error('age') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <!-- ===== Goals & Coverage ===== -->
        <div class="formbold-input-group">
          <label for="goal" class="formbold-form-label">Main insurance goal</label>
          <select class="formbold-form-select" name="goal" id="goal" required>
            <option value="">Select your goal</option>
            <option value="protection" {{ old('goal')=='protection' ? 'selected' : '' }}>Protection for family/loved ones</option>
            <option value="medical" {{ old('goal')=='medical' ? 'selected' : '' }}>Medical/hospital coverage</option>
            <option value="critical_illness" {{ old('goal')=='critical_illness' ? 'selected' : '' }}>Critical illness protection</option>
            <option value="savings" {{ old('goal')=='savings' ? 'selected' : '' }}>Savings & investment</option>
          </select>
          @error('goal') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="formbold-input-group">
          <label for="coverage" class="formbold-form-label">Coverage duration</label>
          <select class="formbold-form-select" name="coverage" id="coverage" required>
            <option value="">Select coverage duration</option>
            <option value="short_term" {{ old('coverage')=='short_term' ? 'selected' : '' }}>Short-term (5–20 yrs)</option>
            <option value="retirement" {{ old('coverage')=='retirement' ? 'selected' : '' }}>Until retirement</option>
            <option value="lifetime" {{ old('coverage')=='lifetime' ? 'selected' : '' }}>Lifetime protection</option>
          </select>
          @error('coverage') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <!-- ===== Lifestyle & Health ===== -->
        <div class="formbold-input-radio-wrapper">
          <label class="formbold-form-label">Concerned about medical/hospital bills?</label>
          <div class="formbold-radio-flex">
            <label class="formbold-radio-label">
              <input type="radio" name="medical_concern" value="Yes" class="formbold-input-radio" {{ old('medical_concern')=='Yes' ? 'checked' : '' }} required>
              Yes <span class="formbold-radio-checkmark"></span>
            </label>
            <label class="formbold-radio-label">
              <input type="radio" name="medical_concern" value="No" class="formbold-input-radio" {{ old('medical_concern')=='No' ? 'checked' : '' }}>
              No <span class="formbold-radio-checkmark"></span>
            </label>
          </div>
          @error('medical_concern') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="formbold-input-group">
          <label for="lifestyle" class="formbold-form-label">Lifestyle</label>
          <select class="formbold-form-select" name="lifestyle" id="lifestyle" required>
            <option value="">Select your lifestyle</option>
            <option value="active" {{ old('lifestyle')=='active' ? 'selected' : '' }}>Active & healthy</option>
            <option value="balanced" {{ old('lifestyle')=='balanced' ? 'selected' : '' }}>Balanced</option>
            <option value="sedentary" {{ old('lifestyle')=='sedentary' ? 'selected' : '' }}>High stress / sedentary</option>
          </select>
          @error('lifestyle') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="formbold-input-radio-wrapper">
          <label class="formbold-form-label">Current health status</label>
          <div class="formbold-radio-flex">
            <label class="formbold-radio-label">
              <input type="radio" name="health_status" value="good" class="formbold-input-radio" {{ old('health_status')=='good' ? 'checked' : '' }} required>
              Good <span class="formbold-radio-checkmark"></span>
            </label>
            <label class="formbold-radio-label">
              <input type="radio" name="health_status" value="average" class="formbold-input-radio" {{ old('health_status')=='average' ? 'checked' : '' }}>
              Average <span class="formbold-radio-checkmark"></span>
            </label>
            <label class="formbold-radio-label">
              <input type="radio" name="health_status" value="poor" class="formbold-input-radio" {{ old('health_status')=='poor' ? 'checked' : '' }}>
              Poor <span class="formbold-radio-checkmark"></span>
            </label>
          </div>
          @error('health_status') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <!-- ===== Financial Info ===== -->
        <div class="formbold-input-radio-wrapper">
          <label class="formbold-form-label">Dependents relying on your income?</label>
          <div class="formbold-radio-flex">
            <label class="formbold-radio-label">
              <input type="radio" name="dependents" value="Yes" class="formbold-input-radio" {{ old('dependents')=='Yes' ? 'checked' : '' }} required>
              Yes <span class="formbold-radio-checkmark"></span>
            </label>
            <label class="formbold-radio-label">
              <input type="radio" name="dependents" value="No" class="formbold-input-radio" {{ old('dependents')=='No' ? 'checked' : '' }}>
              No <span class="formbold-radio-checkmark"></span>
            </label>
          </div>
          @error('dependents') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="formbold-input-group">
          <label for="salary" class="formbold-form-label">Monthly salary (RM)</label>
          <input type="number" name="salary" id="salary" class="formbold-form-input" value="{{ old('salary') }}" required min="0">
          @error('salary') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="formbold-input-group">
          <label for="budget" class="formbold-form-label">Preferred monthly premium budget</label>
          <select class="formbold-form-select" name="budget" id="budget" required>
            <option value="">Select your budget</option>
            <option value="low" {{ old('budget')=='low' ? 'selected' : '' }}>Below RM200</option>
            <option value="medium" {{ old('budget')=='medium' ? 'selected' : '' }}>RM200 – RM500</option>
            <option value="high" {{ old('budget')=='high' ? 'selected' : '' }}>Above RM500</option>
          </select>
          @error('budget') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <!-- ===== Insurance Info ===== -->
        <div class="formbold-input-radio-wrapper">
          <label class="formbold-form-label">Currently have insurance?</label>
          <div class="formbold-radio-flex">
            <label class="formbold-radio-label">
              <input type="radio" name="insurance" value="Yes" class="formbold-input-radio" id="insurance_yes" {{ old('insurance')=='Yes' ? 'checked' : '' }} required>
              Yes <span class="formbold-radio-checkmark"></span>
            </label>
            <label class="formbold-radio-label">
              <input type="radio" name="insurance" value="No" class="formbold-input-radio" id="insurance_no" {{ old('insurance')=='No' ? 'checked' : '' }}>
              No <span class="formbold-radio-checkmark"></span>
            </label>
          </div>
          @error('insurance') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <div class="formbold-input-group" id="planField" style="display: none;">
          <label for="plan" class="formbold-form-label">If yes, describe your insurance plan</label>
          <input type="text" name="plan" id="plan" class="formbold-form-input" value="{{ old('plan') }}" placeholder="Company, type, coverage">
          @error('plan') <div class="error-text">{{ $message }}</div> @enderror
        </div>

        <button class="formbold-btn">Submit</button>
      </form>
    </div>
  </div>
</div>


<script>
function togglePlanField() {
    const yes = document.getElementById('insurance_yes').checked;
    document.getElementById('planField').style.display = yes ? 'block' : 'none';
}
document.getElementById('insurance_yes').addEventListener('change', togglePlanField);
document.getElementById('insurance_no').addEventListener('change', togglePlanField);
window.onload = togglePlanField;
</script>

@endsection
