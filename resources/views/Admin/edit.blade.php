@extends('admin.sidebar')

@section('content')
<main>
    <div class="top-bar">
        <h1>Edit User</h1>
    </div>
    <br>
    <div class="register-container">
        <!-- <h1>Register New User</h1> -->

        <br>

        <form action="" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Role -->
            <!-- <div class="mb-3">
            <label class="form-label">Role:</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="role" id="roleAdmin" value="Admin" required>
                <label class="form-check-label" for="roleAdmin">
                    Admin
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="role" id="roleSupervisor" value="Supervisor"
                    required>
                <label class="form-check-label" for="roleSupervisor">
                    Supervisor
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="role" id="roleRO" value="RO" required>
                <label class="form-check-label" for="roleRO">
                    Relationship Officer
                </label>
            </div>
            </div> -->

            <div class="mb-3">
                <div class="form-check">
                    <div class="row">
                        <div class="radio">
                            <input class="radio__input" type="radio" name="role" id="roleAdmin" value="Admin" required>
                            <label class="radio__label" for="roleAdmin"> Admin </label>

                            <input class="radio__input" type="radio" name="role" id="roleSupervisor" value="Supervisor"
                                required>
                            <label class="radio__label" for="roleSupervisor"> Supervisor </label>

                            <input class="radio__input" type="radio" name="role" id="roleRO" value="RO" required>
                            <label class="radio__label" for="roleRO"> Relationship Officer </label>
                        </div>

                        <!-- <div class="checks"> -->
                        <!-- checkbox -->
                        <label class="checks" >
                            <input type="checkbox" name="" checked>
                            <span class="check"></span>
                            <span class="text on"> Active</span>
                            <span class="text off"> Inactive</span>
                        </label>
                        <!-- </div> -->

                    </div>
                </div>


            </div>

            <br><br>

            <div class="row">
                <!-- <div class="form-group col-12"> -->
                <!-- <div class="col-md-12"> -->
                <!-- Location ID (lid) -->
                <div class="form-group form-group-full-width">
                    <label for="lid">Laboratory Name</label>
                    <input type="text" id="lid" name="lid" class="form-control" required>
                </div>


                <!-- Default Route  -->
                <div class="form-group form-group-full-width">
                    <label for="defaultroute">Default Route</label>
                    <input type="text" id="defaultroute" name="defaultroute" class="form-control" required>
                </div>
            </div>
            <!-- </div> -->

            <div class="row">
                <!-- First Name (fname) -->
                <div class="form-group form-group-full-width">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" class="form-control" required>
                </div>

                <!-- Last Name (lname) -->
                <div class="form-group form-group-full-width">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <!-- Contact -->
                <div class="form-group form-group-full-width">
                    <label for="contact">Contact</label>
                    <input type="text" id="contact" name="contact" class="form-control" required>
                </div>

                <!-- EPF -->
                <div class="form-group form-group-full-width">
                    <label for="epf">EPF</label>
                    <input type="text" id="epf" name="epf" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <!-- Username -->
                <div class="form-group form-group-full-width">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <!-- Status -->
                <!-- <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            </div> -->

                <!-- <div class="form-group">
                <label for="status">Status</label><br>
                <input type="checkbox" id="active" name="status" value="active">
                <label for="active">Active</label>
                <input type="checkbox" id="inactive" name="status" value="inactive">
                <label for="inactive">Inactive</label>
            </div> -->

                <!-- Password -->
                <div class="form-group form-group-full-width">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>

             <!-- Image -->
            <div class="form-group form-group-full-width">
                    <label for="image">Profile Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>

            <br><br>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" class="new">Update</button>
        </form>
    </div>
</main>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

.content {
    margin-left: 90px;
}

.form-group-full-width {
    width: 48%;
    /* Adjust width to fit both elements side by side */
    display: inline-block;
    /* Ensure the elements align horizontally */
    margin-right: 2%;
    /* Adds space between the two divs */
}

.form-group-full-width:last-child {
    margin-right: 0;
    /* Remove margin for the last div */
}

.form-group input[type="file"] {
    width: 100%;
    max-width: 400px;
    padding: 10px;
    font-size: 14px;
    color: #555;
    border: 1px solid #ccc;
    border-radius: 25px;
    cursor: pointer;
    transition: border-color 0.3s ease;
    background-color: var(--color-background);

}

.form-group input[type="file"]:hover {
    border-color: #9fa0ff;
    /* Green border on hover */
}

.form-group input[type="file"]::-webkit-file-upload-button {
    padding: 8px 16px;
    color: #fff;
    background-color:#bbadff;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.form-group input[type="file"]::-webkit-file-upload-button:hover {
    background-color:#8e94f2;
}

.row {
    display: inline-flex;
}

/* +++++++++ */
.checks {
    position: relative;
    padding-left: 900px;
}

label input[type="checkbox"] {
    opacity: 0;
    display: none;
}

.check {
    display: block;
    width: 52px;
    height: 31px;
    border: 3px solid #677483;
    border-radius: 40px;
    transition: 0.5s;
    /* background: #f6f6f9; */
    background-color: var(--color-background);
    margin-right: 10px;
}

label input[type="checkbox"]:checked~.check {
    border: 3px solid #bbadff;
}

.check:before {
    content: '';
    position: absolute;
    width: 26px;
    height: 25px;
    border: 3px solid var(--color-background);
    box-sizing: border-box;
    border-radius: 50%;
    background: #677483;
    box-shadow: inset 0 0 0 1px #677483;
    transition: 0.5s;
}

label input[type="checkbox"]:checked~.check:before {
    box-shadow: inset 0 0 0 1px  #bbadff;
    background:  #bbadff;
    transform: translateX(20px);
}

.text {
    display: block;
    position: absolute;
    top: 0;
    right: -40px;
    width: 40px;
    font-size: 20px;
    transition: 0.5s;
    /* font-weight: bold; */
    color: #677483;
}

.text.on {
    transform: translateY(20px);
    opacity: 0;
    color:  #bbadff;
}

label input[type="checkbox"]:checked~.text.on {
    transform: translateY(0);
    opacity: 1;
    color:  #bbadff;
}

label input[type="checkbox"]:checked~.text.off {
    transform: translateY(-20px);
    opacity: 0;
}

/* +++++ */

/* <!-- Radio Button --> */
.radio {
    display: inline-flex;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.25);
}

.radio__input {
    display: none;
}

.radio__label {
    padding: 8px 14px;
    font-size: 12px;
    /* font-family:sans-serif; */
    color: #fff;
    background:  #8e94f2;
    cursor: pointer;
    transition: background 0.1s;
}

.radio__label:not(:last-of-type) {
    border-right: 3px solid #9fa0ff;
}

.radio__input:checked+.radio__label {
    background: #757bc8;
}


/* <!--  End Radio Button --> */

.primary {
    color: var(--color-primary);
}

.danger {
    color: var(--color-danger);
}

.success {
    color: var(--color-success);
}

.warning {
    color: var(--color-warning);
}

.container {
    display: grid;
    width: 96%;
    margin: 0 auto;
    gap: 1.8rem;
    grid-template-columns: 16rem auto 6rem;
}

main {
    margin-top: 1.4rem;
}

main .analyse {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.6rem;
}

main .analyse>div {
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    margin-top: 1rem;
    box-shadow: var(--box-shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

main .analyse>div:hover {
    box-shadow: none;
}

main .analyse>div .status {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

main .analyse h3 {
    margin-left: 0.6rem;
    font-size: 1rem;
}

main .analyse .progresss {
    position: relative;
    width: 92px;
    height: 92px;
    border-radius: 50%;
}



@media screen and (max-width: 1200px) {
    .container {
        width: 95%;
        grid-template-columns: 7rem auto 23rem;
    }

    main .analyse {
        grid-template-columns: 1fr;
        gap: 0;
    }

    main .new-users .user-list .user {
        flex-basis: 40%;
    }

    main .recent-orders {
        width: 94%;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        margin: 2rem 0 0 0.8rem;
    }

    main .recent-orders table {
        width: 83vw;
    }

    main table thead tr th:last-child,
    main table thead tr th:first-child {
        display: none;
    }

    main table tbody tr td:last-child,
    main table tbody tr td:first-child {
        display: none;
    }

}

@media screen and (max-width: 768px) {
    .container {
        width: 100%;
        grid-template-columns: 1fr;
        padding: 0 var(--padding-1);
    }

    @keyframes showMenu {
        to {
            left: 0;
        }
    }



    main {
        margin-top: 8rem;
        padding: 0 1rem;
    }

}


.glow-on-hover {
    background-color: #C9FFA0;
    /* Button color - green */
    color: white;
    /* Text color */
    border: none;
    /* Remove border */
    padding: 15px 32px;
    /* Padding */
    text-align: center;
    /* Center text */
    text-decoration: none;
    /* Remove underline */
    display: inline-block;
    /* Inline-block display */
    font-size: 16px;
    /* Font size */
    margin: 4px 2px;
    /* Margins */
    cursor: pointer;
    /* Pointer cursor */
    transition: background-color 0.3s, box-shadow 0.3s;
    /* Smooth transitions */
    border-radius: 30px;
    /* Rounded corners */
}

.glow-on-hover:hover {
    background-color: #C9FFA0;
    /* Hover color - darker green */
    box-shadow: 0 0 20px rgba(40, 167, 69, 1),
        /* Glowing effect - green */
        0 0 30px rgba(40, 167, 69, 1),
        0 0 40px rgba(40, 167, 69, 1);
}


/* Popup styles */
.popup {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5);
    /* Black background with opacity */
}

.popup-content {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.2);
    max-width: 500px;
    width: 100%;
    position: relative;
    animation: fadeIn 0.4s ease-in-out;

    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;


}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* .dark-mode-active .popup-content {
    background-color: #202528;
    /* Darker background for popup */
    /* color: #edeffd; */
    /* Light text color for popup */
/* }  */
.form-control {
    background-color:  var(--color-white);
    color:var( --color-dark-variant);
    /* border:var(--color-dark-variant); */
}
/* .dark-mode-active .form-control { */
    /* background-color:  var(--color-dark); */
    /* Darker input background */
    /* color: #edeffd;/ */
    /* Light input text */
    /* border: 1px solid #677483; */
    /* Optional: border for inputs */
/* } */

/* .dark-mode-active .form-control::placeholder {
    color: #a3bdcc; */
    /* Light placeholder text color */
/* } */

.form-check-input {
    background-color:  var(--color-white);
    
    /* Dark checkbox background */
    /* border: 1px solid #677483; */
    /* Optional: border for checkboxes */
}

/* Popup Styling */
/* .popup {
    display: none;
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
} */



@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    color: #333;
    cursor: pointer;
}

form {
    display: flex;
    flex-direction: column;
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
}

input:focus {
    border-color: #85BCCE;
    outline: none;
}

/* Button Styling */
button {
    padding: 12px 25px;
    border: none;
    border-radius: 50px;
    background-color: #8e94f2;
    color: white;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #757bc8;
}

button:active {
    background-color: #bbadff;
}

/* Radio Button and Label */
.form-check-input {
    width: 16px;
    height: 16px;
    margin-right: 8px;
}

.form-check-label {
    font-size: 0.9rem;
    color: #555;
}

/* Responsive Styling */
@media (max-width: 768px) {
    h1 {
        font-size: 1.5rem;
    }

    .popup-content {
        padding: 20px;
        max-width: 90%;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"] {
        font-size: 0.9rem;
        padding: 10px 15px;
    }

    button {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .popup-content {
        padding: 15px;
        max-width: 95%;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"] {
        font-size: 0.85rem;
        padding: 8px 12px;
    }

    button {
        padding: 8px 15px;
        font-size: 0.85rem;
    }
}

/* Centering Button for Mobile View */
.text-center {
    text-align: center;
    margin-bottom: 20px;
}


</style>
@endsection