@extends('admin.sidebar')

@section('content')
<main>
    <div class="top-bar">
        <h1>Change Password</h1>
    </div>
    <br>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="container1">
        <form action="{{ route('admin.changepassword.update') }}" method="post" id="changePasswordForm"
            name="changePasswordForm">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="old_password"><b>Old Password</b></label>
                                <input type="password" name="old_password" id="old_password"
                                    class="form-control @error('old_password') is-invalid @enderror"
                                    placeholder="Old Password" required value="{{ old('old_password') }}">
                                @error('old_password')
                                <p class="error-message text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="new_password"><b>New Password</b></label>
                                <input type="password" name="new_password" id="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror"
                                    placeholder="New Password" required value="{{ old('new_password') }}">
                                <p id="password-strength" class="message"></p>
                                @error('new_password')
                                <p class="error-message text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="confirm_password"><b>Confirm Password</b></label>
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="form-control @error('confirm_password') is-invalid @enderror"
                                    placeholder="Confirm Password" required value="{{ old('confirm_password') }}">
                                <p id="password-match" class="message"></p>
                                @error('confirm_password')
                                <p class="error-message text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/admin/dashboard" class="btn1">Cancel</a>
            </div>
        </form>
    </div>
</main>

<!-- Enhanced Password Validation Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const oldPassword = document.getElementById("old_password");
    const newPassword = document.getElementById("new_password");
    const confirmPassword = document.getElementById("confirm_password");
    const passwordStrength = document.getElementById("password-strength");
    const passwordMatch = document.getElementById("password-match");
    const form = document.getElementById("changePasswordForm");

    // Function to check password strength
    function checkPasswordStrength(password) {
        if (password.length === 0) return {
            strength: "",
            color: ""
        };
        if (password.length < 8) return {
            strength: "Too short ❌",
            color: "#FF0060"
        };
        if (!/[A-Z]/.test(password)) return {
            strength: "Must include uppercase ❌",
            color: "#FF0060"
        };
        if (!/[a-z]/.test(password)) return {
            strength: "Must include lowercase ❌",
            color: "#FF0060"
        };
        if (!/\d/.test(password)) return {
            strength: "Must include number ❌",
            color: "#FF0060"
        };
        if (!/[\W]/.test(password)) return {
            strength: "Must include special char ❌",
            color: "#FF0060"
        };
        return {
            strength: "Strong ✅",
            color: "green"
        };
    }

    // Password strength validation
    newPassword.addEventListener("input", function() {
        const {
            strength,
            color
        } = checkPasswordStrength(newPassword.value);
        passwordStrength.textContent = strength;
        passwordStrength.style.color = color;
    });

    // Confirm password validation
    function validatePasswordMatch() {
        if (newPassword.value && confirmPassword.value) {
            if (confirmPassword.value === newPassword.value) {
                passwordMatch.textContent = "Passwords match ✅";
                passwordMatch.style.color = "green";
            } else {
                passwordMatch.textContent = "Passwords don't match ❌";
                passwordMatch.style.color = "#FF0060";
            }
        } else {
            passwordMatch.textContent = "";
        }
    }

    newPassword.addEventListener("input", validatePasswordMatch);
    confirmPassword.addEventListener("input", validatePasswordMatch);

    // Form submission validation
    form.addEventListener("submit", function(event) {
        let isValid = true;

        // Clear previous error highlights
        [oldPassword, newPassword, confirmPassword].forEach(field => {
            field.classList.remove('is-invalid');
        });

        // Validate old password
        if (!oldPassword.value.trim()) {
            oldPassword.classList.add('is-invalid');
            isValid = false;
        }

        // Validate new password strength
        const passwordValidation = checkPasswordStrength(newPassword.value);
        if (passwordValidation.strength.includes("❌")) {
            newPassword.classList.add('is-invalid');
            isValid = false;
        }

        // Validate password match
        if (newPassword.value !== confirmPassword.value) {
            confirmPassword.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
            // Focus on first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    });

    // Initialize validation messages if old values exist
    if (newPassword.value) {
        const {
            strength,
            color
        } = checkPasswordStrength(newPassword.value);
        passwordStrength.textContent = strength;
        passwordStrength.style.color = color;
    }
    validatePasswordMatch();
});
</script>

<style>
.is-invalid {
    border-color: #dc3545 !important;
}

.error-message {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
}

.message {
    font-size: 0.875em;
    margin-top: 0.25rem;
    min-height: 1.2em;
}

/* Input Field Styling */
input[type="text"],
input[type="password"],
input[type="email"] {
    border: 1px solid #ddd;
    border-radius: 50px;
    padding: 12px 20px;
    font-size: 1rem;
    margin-bottom: 15px;
    width: 100%;
    transition: border 0.3s ease;
    background-color: var(--color-white);
    color: var(--color-dark);
}


.content {
    margin-left: 90px;
}

.alert {
    padding: 15px;
    margin: 20px 0;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 500;
    position: relative;
    opacity: 0.95;
    transition: opacity 0.3s ease-in-out;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.alert ul li {
    list-style-type: disc;
}

/* Optional close button styles */
.alert::after {
    content: "×";
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    font-size: 20px;
    color: inherit;
}

.alert:hover {
    opacity: 1;
}

.alert-success:hover {
    border-color: #98c68a;
    background-color: #c3e6cb;
}

.alert-danger:hover {
    border-color: #f1a2a5;
    background-color: #f5c6cb;
}

.www {
    padding-left: 100px;
    display: inline-flex;
    gap: 10px;
}

.text {
    font-weight: 600;
    font-size: 14px;
}

.danger {
    color: var(--color-danger);
}

.success {
    color: var(--color-success);
}

.container {
    display: grid;
    width: 96%;
    margin: 0 auto;
    gap: 1.8rem;
    grid-template-columns: 16rem auto 6rem;
}

/* -----------------------------------------form style--------------------------- */
.container1 {
    max-width: 1000px;
    margin: 50px auto;
    padding: 30px;
    /* background-color: #f9f9f9; */
    background-color: var(--color-white);
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Card Style */
.card {
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: var(--color-white);
}

/* Card Body Style */
.card-body {
    padding: 20px;
}

/* Row Styling */
.row {
    display: flex;
    flex-direction: column;
}

/* Form Control Styling */
.form-control {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
}

/* Focus Effect */
.form-control:focus {
    border-color: #8e94f2;
    outline: none;
}

/* Error Message Styling */
.error-message {
    font-size: 12px;
    color: #e74c3c;
}

/* Button Container */
.button-container {
    padding-top: 20px;
}

/* Button Styling */
/*  */

/* pop upButton Styling */
.btn {
    padding: 12px 25px;
    /* border: none; */
    border-radius: 10px;
    background-color: #8e94f2;
    border: 2px solid #8e94f2;
    color: white;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-family: Arial, sans-serif;
}

button:hover {
    background-color: #365485;
}

button:active {
    background-color: rgb(46, 139, 201);
}

.btn1 {
    padding: 12px 25px;
    border: none;
    border-radius: 10px;
    background-color: var(--color-white);
    /* border-color:var(--color-dark); */
    border: 2px solid #8e94f2;
    color: #8e94f2;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-family: Arial, sans-serif;
}

button:hover {
    background: #8e94f2;
}

button:active {
    background-color: rgb(112, 123, 223);
}

.message {
    text-align: right;
    padding-right: 10px;
}
</style>

@endsection