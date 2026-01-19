@extends('layouts.app')

@section('content')

<style>
.quote-page-background {
    min-height: calc(100vh - 70px);
    padding: 80px 0;
    background-image: url("{{ asset('image/requestform.jpeg') }}");
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
}
.quote-card {
    padding:30px; border-radius:15px; background:#fff; box-shadow:0 6px 20px rgba(0,0,0,0.08); overflow-x:auto;
}
table { width:100%; table-layout: fixed; }
th, td { text-align:center; vertical-align:middle; word-break:break-word; }
</style>

<div class="section quote-page-background">
  <div class="container" style="max-width:1200px;">
    <div class="quote-card">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Assigned Quote Requests</h2>
        <a href="{{ route('quote.assignment') }}" class="btn btn-secondary">← Back to Pending Requests</a>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Assigned To</th>
            <th>Action</th>
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
                <form action="{{ route('quote.delete', $id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to archive this quote request? This action can be undone.')">
                    Archive
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">No assigned requests found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

    </div>
  </div>
</div>

@endsection
