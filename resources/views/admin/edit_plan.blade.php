@extends('layouts.app')

@section('content')

<style>
  /* Full-page background for admin manage plans page */
  .admin-page-background {
    min-height: calc(100vh - 70px); /* full page minus navbar */
    padding: 80px 0;
    background-image: url("{{ asset('image/requestform.jpeg') }}");
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
  }

  /* Card style for forms and tables */
  .admin-card {
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    background-color: #ffffff;
    overflow-x: auto;
    margin-bottom: 40px;
  }

  /* Form input styling */
  .formbold-form-label { 
    color: #07074d; 
    font-size: 14px; 
    margin-bottom: 8px; 
    display: block; 
  }

  .formbold-form-input, .formbold-form-select, textarea {
    width: 100%;
    padding: 12px 20px;
    border-radius: 5px;
    border: 1px solid #dde3ec;
    font-size: 14px;
    color: #07074d;
    outline: none;
    transition: 0.3s;
  }

  .formbold-form-input:focus, .formbold-form-select:focus, textarea:focus {
    border-color: #e74c3c;
    box-shadow: 0px 3px 8px rgba(0,0,0,0.05);
  }

  .formbold-input-group { margin-bottom: 18px; }

  .formbold-btn {
    width: 100%;
    font-size: 15px;
    border-radius: 5px;
    padding: 12px 20px;
    border: none;
    font-weight: 500;
    background-color: #e74c3c;
    color: white;
    cursor: pointer;
    margin-top: 15px;
    transition: 0.3s;
  }

  .formbold-btn:hover { background-color: #c0392b; }

  .form-header { text-align: center; margin-bottom: 25px; }
  .form-header h2 { font-weight: 600; color: #2c3e50; }
  .form-header p { color: #7f8c8d; }

  /* Table styling consistent with quote assignment page */
  table {
    width: 100%;
    table-layout: fixed;
    text-align: center;
  }

  th, td {
    vertical-align: middle !important;
    text-align: center;
    word-break: break-word;
  }

  th {
    background: #f7f7f7;
    font-weight: 600;
  }

  table img {
    max-width: 100px;
    border-radius: 5px;
  }

  .action-btns a, .action-btns button { 
    margin: 2px; 
    padding: 6px 12px; 
    font-size: 12px; 
  }

</style>

<div class="section form-section">
    <div class="container">
        <div class="form-container">

            <div class="form-header">
                <h2>Edit Insurance Plan</h2>
                <p>Update the insurance plan details below.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.edit-plan', $id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- BASIC INFO -->
                <div class="formbold-input-group">
                    <label class="formbold-form-label">Plan Name</label>
                    <input type="text" name="name" class="formbold-form-input"
                           value="{{ old('name', $plan['name']) }}" required>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Category</label>
                    <select name="category" class="formbold-form-select" required>
                        <option value="">Choose Category</option>
                        <option value="Medical" {{ old('category', $plan['category']) == 'Medical' ? 'selected' : '' }}>Medical</option>
                        <option value="Critical" {{ old('category', $plan['category']) == 'Critical' ? 'selected' : '' }}>Critical</option>
                        <option value="Life" {{ old('category', $plan['category']) == 'Life' ? 'selected' : '' }}>Life</option>
                    </select>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Overview</label>
                    <textarea name="overview" rows="3" class="formbold-form-input" required>{{ old('overview', $plan['overview']) }}</textarea>
                </div>

                <!-- BULLET POINT FIELDS -->
                <div class="formbold-input-group">
                    <label class="formbold-form-label">
                        Features <small class="hint">(One point per line)</small>
                    </label>
                    <textarea name="features" rows="4" class="formbold-form-input" required>{{ old('features', $plan['features']) }}</textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">
                        Eligibility <small class="hint">(One point per line)</small>
                    </label>
                    <textarea name="eligibility" rows="3" class="formbold-form-input" required>{{ old('eligibility', $plan['eligibility']) }}</textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">
                        Fees & Charges <small class="hint">(One point per line)</small>
                    </label>
                    <textarea name="fees" rows="3" class="formbold-form-input" required>{{ old('fees', $plan['fees']) }}</textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">
                        Cash Rewards <small class="hint">(Optional • One point per line)</small>
                    </label>
                    <textarea name="cash_rewards" rows="3" class="formbold-form-input">{{ old('cash_rewards', $plan['cash_rewards'] ?? '') }}</textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">
                        Additional Benefits <small class="hint">(Optional • One point per line)</small>
                    </label>
                    <textarea name="additional_benefits" rows="3" class="formbold-form-input">{{ old('additional_benefits', $plan['additional_benefits'] ?? '') }}</textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">
                        Disclaimers <small class="hint">(Optional • One point per line)</small>
                    </label>
                    <textarea name="disclaimers" rows="3" class="formbold-form-input">{{ old('disclaimers', $plan['disclaimers'] ?? '') }}</textarea>
                </div>

                <!-- OPTIONAL FIELDS -->
                <div class="formbold-input-group">
                    <label class="formbold-form-label">PIDM</label>
                    <input type="text" name="pidm" class="formbold-form-input"
                           value="{{ old('pidm', $plan['pidm'] ?? '') }}">
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Highlight / Subtitle</label>
                    <input type="text" name="highlight" class="formbold-form-input"
                           value="{{ old('highlight', $plan['highlight'] ?? '') }}">
                </div>

                <hr>
<h4 style="margin-top:30px;">Plan Summary (For Comparison)</h4>

<div class="formbold-input-group">
    <label class="formbold-form-label">Annual Limit</label>
    <input type="text" name="annual_limit" class="formbold-form-input"
           value="{{ old('annual_limit', $plan['summary']['annual_limit'] ?? '') }}" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Lifetime Limit</label>
    <input type="text" name="lifetime_limit" class="formbold-form-input"
           value="{{ old('lifetime_limit', $plan['summary']['lifetime_limit'] ?? '') }}" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Hospitalisation Benefit</label>
    <input type="text" name="hospitalisation" class="formbold-form-input"
           value="{{ old('hospitalisation', $plan['summary']['hospitalisation'] ?? '') }}" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Benefits / Features</label>
    <textarea name="benefits" rows="2" class="formbold-form-input" required>{{ old('benefits', $plan['summary']['benefits'] ?? '') }}</textarea>
</div>

<h5>Eligibility</h5>

<div class="formbold-input-group">
    <label class="formbold-form-label">Entry Age</label>
    <input type="text" name="entry_age" class="formbold-form-input"
           value="{{ old('entry_age', $plan['summary']['eligibility']['entry_age'] ?? '') }}" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Nationality</label>
    <input type="text" name="nationality" class="formbold-form-input"
           value="{{ old('nationality', $plan['summary']['eligibility']['nationality'] ?? '') }}" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Coverage Age</label>
    <input type="text" name="coverage_age" class="formbold-form-input"
           value="{{ old('coverage_age', $plan['summary']['eligibility']['coverage_age'] ?? '') }}" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Coverage Term</label>
    <input type="text" name="coverage_term" class="formbold-form-input"
           value="{{ old('coverage_term', $plan['summary']['eligibility']['coverage_term'] ?? '') }}" required>
</div>


                <!-- FILES -->
                <div class="formbold-input-group">
    <label class="formbold-form-label">Banner Image</label>
    <input type="file" name="banner_image" accept="image/*" class="formbold-form-input">

    @if(!empty($plan['banner_image']) && file_exists(public_path('image/plans/'.$plan['banner_image'])))
        <img src="{{ asset('image/plans/'.$plan['banner_image']) }}" class="current-image" style="margin-top:10px; max-height:150px;">
        <div style="margin-top:5px;">
            <input type="checkbox" name="delete_banner" value="1"> Delete current banner image
        </div>
    @endif
</div>


              <div class="formbold-input-group">
    <label class="formbold-form-label">Brochure (PDF)</label>
    <input type="file" name="brochure" accept="application/pdf" class="formbold-form-input">

    @if(!empty($plan['brochure']))
        <p style="margin-top:5px;">
            Current brochure: <a href="{{ $plan['brochure'] }}" target="_blank">View PDF</a>
        </p>
        <div>
            <input type="checkbox" name="delete_brochure" value="1"> Delete current brochure
        </div>
    @endif
</div>


                <button type="submit" class="formbold-btn">Update Plan</button>
            </form>

        </div>
    </div>
</div>


@endsection
