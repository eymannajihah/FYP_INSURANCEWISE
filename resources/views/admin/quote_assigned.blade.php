@extends('layouts.app')

@section('content')

<style>
  /* Match quote assignment page UI */
  .quote-page-background {
    min-height: calc(100vh - 70px);
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

<div class="section section-gray quote-page-background">
  <div class="container" style="max-width:1200px;">
    <div class="quote-card">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-weight:600; color:#2c3e50;">
          Assigned Quote Requests
        </h2>

        <a href="{{ route('quote.assignment') }}"
           class="btn btn-secondary"
           style="padding:6px 14px;">
          ← Back to Pending Requests
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <table class="table table-bordered text-center">
        <thead>
          <tr>
            <th style="width:18%;">Name</th>
            <th style="width:22%;">Email</th>
            <th style="width:15%;">Phone</th>
            <th style="width:15%;">Assigned To</th>
            <th style="width:15%;">Action</th>
          </tr>
        </thead>

        <tbody>
          @forelse($assigned as $id => $req)
            <tr>
              <td>{{ $req['name'] ?? '' }}</td>
              <td>{{ $req['email'] ?? '' }}</td>
              <td>{{ $req['phone'] ?? '' }}</td>
              <td>{{ $req['assigned_to'] ?? '-' }}</td>
              <td>
                <form action="{{ route('quote.delete', $id) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this assigned request?');">
                  @csrf
                  @method('DELETE')

                  <button
  type="submit"
  class="btn btn-danger btn-sm"
  onclick="return confirm('Are you sure you want to archive this quote request? This action can be undone.')"
>
  Archive
</button>

                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6">
                No assigned requests found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

    </div>
  </div>
</div>

@endsection
