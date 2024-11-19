<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Sign In Page</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body,
    html {
        height: 100%;
        font-family: 'Arial', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        /* background: linear-gradient(135deg, #97CFCF, #4f73ff); */
        /* background: linear-gradient(135deg, #000, #000); */
    }

    .container {
        width: 1000px;
        height: 500px;
        display: flex;
        background: linear-gradient(135deg, #97CFCF, #4f73ff);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .left-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 90px;
        position: relative;
        /* background: #fff; */
        background: rgba(255, 255, 255, 0.1);
        border-radius: 0 50% 50% 0;
    }

    .left-panel img {
        width: 350px;
        height: auto;
        border-radius: 50% 50% 50% 50%;
    }

    .left-panel h5 {
        color: white;
        font-size: 24px;
        margin-top: 20px;
        text-align: center;
    }

    .right-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        color: white;
    }

    .right-panel h2 {
        margin-bottom: 20px;
        font-size: 28px;
    }

    .form-group {
        position: relative;
        width: 100%;
        margin-bottom: 20px;
    }

    .form-group input {
        width: 100%;
        padding: 15px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 16px;
    }

    .form-group input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .btn {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 10px;
        background-color: #5361ff;
        color: white;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #4053ff;
    }

    .forgot-password {
        text-align: center;
        margin-top: 10px;
        color: white;
        font-size: 14px;
    }

    .forgot-password a {
        color: white;
        text-decoration: none;
    }

    .role-icons {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    .role-icons i {
        background-color: white;
        width: 43px;
        height: 43px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        margin: 0 23px;
        color: #3b8dff;
        font-size: 20px;
        transition: transform 0.3s ease;
    }

    .role-icons i:hover {
        transform: scale(1.1);
    }

    /* Responsive for smaller screens */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            height: auto;
        }

        .left-panel {
            border-radius: 0;
        }
    }

    .role-icons {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    .role-container {
        position: relative;
        overflow: hidden;
        margin: 0 0px;
    }

    .role-btn {
        background-color: transparent;
        border: none;
        cursor: pointer;
        color: white;
        display: flex;
        align-items: center;
        font-size: 14px;
        transition: transform 0.3s ease;
        position: relative;
        padding: 25px;
        /* Adjust padding for better hover effect */
    }

    .role-btn:hover {
        transform: translateX(-30px);
        /* Slide left */
    }

    .role-label {
        margin-left: 60px;
        /* Space between icon and label */
        opacity: 0;
        /* Hide label initially */
        transition: opacity 0.3s ease, transform 0.3s ease;
        /* Animate opacity and position */
        position: absolute;
        /* Position absolutely within the container */
        left: 50%;
        /* Center the label */
        transform: translateX(-50%) translateY(-10px);
        /* Adjust label position */
    }

    .role-btn:hover .role-label {
        opacity: 1;
        /* Show label on hover */
        transform: translateX(-50%) translateY(0);
        /* Move label into position */
    }
    </style>
</head>

<body>

    <div class="container">
        <!-- Left Panel (Illustration) -->
        <div class="left-panel">
            <img src="./image/login.gif" alt="Rocket Illustration">
            <h5>C o l l e c t <b>X</b></h5>
        </div>

        <!-- Right Panel (Sign In Form) -->
        <div class="right-panel">
            <h2>Sign In</h2><br>
            <form action="{{ route('signin') }}" method="POST">
                @csrf
                <!-- <h2>Sign In</h2> -->

                <!-- Display error message if available -->
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <div class="form-group">
                    <input type="text" name="username" id="username" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" placeholder="Password" required />
                </div>
                <button class="btn" type="submit">Sign In</button>
            </form>

            <div class="role-icons">
                <div class="role-container">
                    <button class="role-btn" data-role="Admin">
                        <i class='bx bx-cog'></i>
                        <span class="role-label">Admin</span>
                    </button>
                </div>
                <div class="role-container">
                    <button class="role-btn" data-role="Supervisor">
                        <i class='bx bxs-user'></i>
                        <span class="role-label"> Supervisor</span>
                    </button>
                </div>
            </div>
            <input type="hidden" id="selectedRole" name="role" value="" />

        </div>
    </div>

    <!-- Font Awesome for social icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <script>
    // Add this script before the closing </body> tag
    document.querySelectorAll('.role-btn').forEach(button => {
        button.addEventListener('click', function() {
            const selectedRole = this.getAttribute('data-role');
            document.getElementById('selectedRole').value = selectedRole;

            // Optionally, add visual feedback for the selected role
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.style.color = 'white'; // Reset color
            });
            this.style.color = '#000'; // Highlight selected role
        });
    });
    </script>


</body>

</html>