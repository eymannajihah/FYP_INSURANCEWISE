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

        <!-- Add Plan Card -->
        <div class="form-container">
            <div class="form-header">
                <h2>Add New Insurance Plan</h2>
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

            <form action="{{ route('admin.add-plan') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Plan Name</label>
                    <input type="text" name="name" class="formbold-form-input" required>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Category</label>
                    <select name="category" class="formbold-form-select" required>
                        <option value="">Choose Category</option>
                        <option value="Medical">Medical</option>
                        <option value="Critical">Critical</option>
                        <option value="Life">Life</option>
                    </select>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Overview</label>
                    <textarea name="overview" class="formbold-form-input" rows="3" required></textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Features</label>
                    <textarea name="features" class="formbold-form-input" rows="3" required></textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Eligibility</label>
                    <textarea name="eligibility" class="formbold-form-input" rows="2" required></textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Fees & Charges</label>
                    <textarea name="fees" class="formbold-form-input" rows="2" required></textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">PIDM (Optional)</label>
                    <textarea name="pidm" class="formbold-form-input" rows="2"></textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Cash Rewards (Optional)</label>
                    <textarea name="cash_rewards" class="formbold-form-input" rows="2"></textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Additional Benefits (Optional)</label>
                    <textarea name="additional_benefits" class="formbold-form-input" rows="2"></textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Disclaimers (Optional)</label>
                    <textarea name="disclaimers" class="formbold-form-input" rows="2"></textarea>
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Highlight / Subtitle (Optional)</label>
                    <input type="text" name="highlight" class="formbold-form-input">
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Banner Image (Optional)</label>
                    <input type="file" name="banner_image" accept="image/*" class="formbold-form-input">
                </div>

                <div class="formbold-input-group">
                    <label class="formbold-form-label">Brochure (PDF)</label>
                    <input type="file" name="brochure" accept="application/pdf" class="formbold-form-input">
                </div>
            <hr>
<h4 style="margin-top:30px;">Plan Summary (For Comparison)</h4>

<div class="formbold-input-group">
    <label class="formbold-form-label">Annual Limit</label>
    <input type="text" name="annual_limit" class="formbold-form-input" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Lifetime Limit</label>
    <input type="text" name="lifetime_limit" class="formbold-form-input" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Hospitalisation Benefit</label>
    <input type="text" name="hospitalisation" class="formbold-form-input" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Benefits / Features</label>
    <textarea name="benefits" class="formbold-form-input" rows="2" required></textarea>
</div>

<h5>Eligibility</h5>

<div class="formbold-input-group">
    <label class="formbold-form-label">Entry Age</label>
    <input type="text" name="entry_age" class="formbold-form-input" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Nationality</label>
    <input type="text" name="nationality" class="formbold-form-input" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Coverage Age</label>
    <input type="text" name="coverage_age" class="formbold-form-input" required>
</div>

<div class="formbold-input-group">
    <label class="formbold-form-label">Coverage Term</label>
    <input type="text" name="coverage_term" class="formbold-form-input" required>
</div>

                <button type="submit" class="formbold-btn">Add Plan</button>
            </form>

        </div>

     <!-- EXISTING PLANS CARD -->
    <div class="admin-card">
      <div class="form-header">
        <h2>Existing Plans</h2>
      </div>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Highlight</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($plans as $id => $plan)
            <tr>
              <td>
                @if(!empty($plan['banner_image']))
                <img src="{{ asset('storage/' . $plan['banner_image']) }}" alt="Banner" class="img-fluid">
                @else
                  <span>No image</span>
                @endif
              </td>
              <td>{{ $plan['name'] }}</td>
              <td>{{ $plan['category'] }}</td>
              <td>{{ $plan['highlight'] ?? '-' }}</td>
              <td class="action-btns">
                <a href="{{ route('admin.edit-plan', $id) }}" class="formbold-btn" style="background-color:#f1c40f; color:#000;">Edit</a>
                <form action="{{ route('admin.delete-plan', $id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button class="formbold-btn" style="background-color:#e74c3c;" onclick="return confirm('Delete this plan?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No plans found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>

@endsection
