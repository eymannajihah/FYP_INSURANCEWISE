@extends('layouts.app')

@section('content')

<style>
  /* Full-page background for quote assignment */
  .quote-page-background {
    min-height: calc(100vh - 70px);
    padding: 80px 0;
    background-image: url("/image/requestform.jpeg"); /* relative path avoids HTTP/HTTPS issues */
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

  .assign-success {
    color: green;
    font-size: 13px;
    margin-top: 5px;
    display: none;
  }
</style>

<div class="section section-gray quote-page-background">
  <div class="container" style="max-width: 1200px;">
    <div class="quote-card">

      <div class="text-center mb-3">
        <h2 style="font-weight: 600; color: #2c3e50;">Manage Quote Requests</h2>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div></div>
        <a href="{{ route('quote.assigned') }}" class="btn btn-outline-primary"
           style="font-size:14px; padding:6px 14px;">View Assigned Requests</a>
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
            <tr id="quote-row-{{ $id }}">
              <td>{{ $req['name'] ?? '' }}</td>
              <td>{{ $req['email'] ?? '' }}</td>
              <td>{{ $req['phone'] ?? '' }}</td>
              <td id="status-{{ $id }}">{{ $req['status'] ?? 'pending' }}</td>
              <td>
                <form class="assign-form" data-id="{{ $id }}" style="display:flex; gap:8px; justify-content:center; flex-wrap: wrap;">
                  @csrf
                  <input type="text" name="assigned_to" placeholder="Staff name..." required
                         style="border-radius:5px; padding:6px 8px; border:1px solid #dde3ec; font-size:14px; width:140px;">
                  <button type="submit" class="btn btn-danger" style="padding:6px 14px; font-size:14px;">Assign</button>
                  <div class="assign-success" id="assign-success-{{ $id }}">Assigned successfully!</div>
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

<script>
document.querySelectorAll('.assign-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = this.dataset.id;
        const input = this.querySelector('input[name="assigned_to"]');
        const successDiv = document.getElementById(`assign-success-${id}`);
        const csrfToken = this.querySelector('input[name="_token"]').value;

        const formData = new FormData();
        formData.append('assigned_to', input.value);

        try {
            const response = await fetch(`/admin/quote-requests/${id}/assign`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const result = await response.json().catch(() => ({ success: false, error: 'Server error' }));

            if (result.success !== false) {
                // Show success message and update status
                successDiv.style.display = 'block';
                document.getElementById(`status-${id}`).textContent = 'assigned';
                input.value = '';
                setTimeout(() => { successDiv.style.display = 'none'; }, 3000);
            } else {
                alert(result.error || 'Failed to assign staff.');
            }
        } catch (error) {
            console.error('Error assigning staff:', error);
            alert('An error occurred. Please try again.');
        }
    });
});
</script>

@endsection
