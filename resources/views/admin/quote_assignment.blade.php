@extends('layouts.app')

@section('content')

<style>
  /* Full-page background for quote assignment */
  .quote-page-background {
    min-height: calc(100vh - 70px); /* full page minus navbar */
    padding: 80px 0;
    background-image: url("{{ asset('image/requestform.jpeg') }}");
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
  }

  .quote-card {
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    background-color: #ffffff;
    overflow-x: auto;
  }

  table {
    width: 100%;
    table-layout: fixed;
  }

  th, td {
    vertical-align: middle !important;
    text-align: center;
    word-break: break-word;
  }
</style>

<!-- Quote Assignment Section -->
<div class="section section-gray quote-page-background">
  <div class="container" style="max-width: 1200px;">
    <div class="quote-card">

      <div class="text-center" style="margin-bottom: 25px;">
        <h2 style="font-weight: 600; color: #2c3e50;">Manage Quote Requests</h2>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
  <div></div>

  <a href="{{ route('quote.assigned') }}"
     class="btn btn-outline-primary"
     style="font-size:14px; padding:6px 14px;">
    View Assigned Requests
  </a>
</div>


      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <table class="table table-bordered text-center">
        <thead>
          <tr>
            <th style="width: 18%;">Name</th>
            <th style="width: 22%;">Email</th>
            <th style="width: 15%;">Phone</th>
            <th style="width: 15%;">Status</th>
            <th style="width: 30%;">Assign To</th>
          </tr>
        </thead>

        <tbody>
          @forelse($requests as $id => $req)
            <tr>
              <td>{{ $req['name'] ?? '' }}</td>
              <td>{{ $req['email'] ?? '' }}</td>
              <td>{{ $req['phone'] ?? '' }}</td>
              <td>{{ $req['status'] ?? 'pending' }}</td>
              <td>
                <form action="{{ route('quote.assign', $id) }}" method="POST" style="display:flex; gap:8px; justify-content:center; flex-wrap: wrap;">
                  @csrf
                  <input type="text" name="assigned_to" placeholder="Staff name..." required style="border-radius:5px; padding:6px 8px; border:1px solid #dde3ec; font-size:14px; width:140px;">
                  <button type="submit" class="btn btn-danger" style="padding:6px 14px; font-size:14px;">Assign</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No quote requests found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

    </div>
  </div>
</div>

@endsection
