@extends('layouts.app')

@section('content')

<style>
.quote-page-background {
    min-height: calc(100vh - 70px);
    padding: 80px 0;
    background-image: url("/image/requestform.jpeg");
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
}
.quote-card {
    padding: 30px;
    border-radius: 15px;
    background-color: #fff;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    overflow-x: auto;
}
table { width: 100%; table-layout: fixed; }
th, td { text-align:center; vertical-align: middle; word-break: break-word; }
.assign-success { color: green; font-size:13px; display:none; margin-top:5px; }
.assign-error { color: red; font-size:13px; display:none; margin-top:5px; }
</style>

<div class="section quote-page-background">
  <div class="container" style="max-width:1200px;">
    <div class="quote-card">

      <div class="text-center mb-3">
        <h2 style="font-weight:600;">Manage Quote Requests</h2>
      </div>

      <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('quote.assigned') }}" class="btn btn-outline-primary">
          View Assigned Requests
        </a>
      </div>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Assign To</th>
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
                  <input type="text" name="assigned_to" placeholder="Staff name..." required style="padding:6px 8px; border-radius:5px; border:1px solid #dde3ec;">
                  <button type="submit" class="btn btn-primary">Assign</button>

                  <div class="assign-success" id="assign-success-{{ $id }}">
                    Assigned successfully!
                  </div>

                  <div class="assign-error" id="assign-error-{{ $id }}">
                    Failed to send email. Assigned anyway.
                  </div>
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
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.assign-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const id = this.dataset.id;
            const input = this.querySelector('input[name="assigned_to"]');
            const successDiv = document.getElementById(`assign-success-${id}`);
            const errorDiv = document.getElementById(`assign-error-${id}`);
            const csrfToken = this.querySelector('input[name="_token"]').value;

            successDiv.style.display = 'none';
            errorDiv.style.display = 'none';

            const formData = new FormData();
            formData.append('assigned_to', input.value);

            try {
                const response = await fetch(`/admin/quote-requests/${id}/assign`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById(`status-${id}`).textContent = 'assigned';
                    successDiv.style.display = 'block';
                    input.value = '';

                    // Remove row after 2s
                    setTimeout(() => {
                        const row = document.getElementById(`quote-row-${id}`);
                        if (row) row.remove();
                    }, 2000);

                } else {
                    alert(result.error || 'Failed to assign staff.');
                    errorDiv.style.display = 'block';
                }

            } catch (error) {
                console.error('Error submitting assignment:', error);
                alert('An error occurred. Please try again.');
                errorDiv.style.display = 'block';
            }
        });
    });
});
</script>

@endsection
