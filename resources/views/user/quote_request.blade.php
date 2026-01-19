@extends('layouts.app')

@section('content')



<style>
/* ===== Page Background ===== */
body {
    font-family: 'Poppins', sans-serif;
     background-image: url("{{ asset('image/requestform.jpeg') }}");
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
    margin: 0;
    padding-top: 80px; /* push content below navbar */
}

/* ===== Form Section ===== */
.contact-section {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 60px 20px;
    min-height: calc(100vh - 80px); /* full height minus navbar */
}

.contact-container {
    background: rgba(255, 255, 255, 0.95);
    padding: 50px 30px;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 500px;
}

/* ===== Headers ===== */
.contact-container h2 {
    text-align: center;
    color: #d32f2f;
    margin-bottom: 30px;
}

/* ===== Form Inputs ===== */
label {
    font-weight: 600;
    color: #555;
    margin-top: 15px;
    display: block;
}
input {
    margin-top: 5px;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 100%;
    font-size: 14px;
    transition: 0.3s;
}
input:focus {
    border-color: #d32f2f;
}

/* ===== Submit Button ===== */
.btn-submit {
    background-color: #d32f2f;
    color: white;
    border: none;
    padding: 14px;
    width: 100%;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    margin-top: 25px;
    transition: background 0.3s;
}
.btn-submit:hover {
    background-color: #b71c1c;
}

/* ===== Success Message ===== */
.success-message {
    margin-top: 15px;
    color: green;
    text-align: center;
    display: none;
}

/* ===== Responsive ===== */
@media (max-width: 576px) {
    .contact-container {
        padding: 30px 20px;
    }
}
</style>


<!-- Form Section -->
<div class="contact-section">
    <div class="contact-container">
        <h2>Get Your Quote</h2>
        <form id="contactForm">
            @csrf
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Your name..." required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Your email..." required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" placeholder="Your phone number..." required>

            <button type="submit" class="btn-submit">Send Request</button>
            <p class="success-message" id="successMessage">Request sent successfully!</p>
        </form>
    </div>
</div>


<script>
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const response = await fetch("{{ route('quote.submit') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('successMessage').style.display = 'block';
            this.reset();
        } else {
            alert(result.message || 'Something went wrong!');
        }
    } catch (error) {
        console.error('Error submitting form:', error);
        alert('An error occurred. Please try again.');
    }
});
</script>

@endsection
