@extends('incharge.navbar')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
/* Create Button Style */
.create-btn {
    display: inline-block;
    margin: 75px 125px;
    padding: 10px 20px;
    background-color: #fff;
    width: 190px;
    height: 40px;
    color: black;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s;
    box-shadow: 0 5px 30px rgba(132, 139, 200, 0.18);
    font-size: 16px;
}

.create-btn:hover {
    background-color: #e0e0e0;
}

/* Gradient Icon */
.gradient-icon {
    background: linear-gradient(to bottom right, #E21C34, #500B28);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Solid Text */
.solid-text {
    color: #900d09;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    transition: opacity 0.3s ease;
    z-index: 9999;
}

.modal-content {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 400px;
    position: relative;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Media Query for small screens (up to 600px) */
@media only screen and (max-width: 600px) {
    .create-btn {
        margin: 20px auto;
        width: 90%;
        font-size: 14px;
        height: 40px;
    }

    .modal-content {
        width: 90%;
    }
}

/* Media Query for medium screens (600px - 900px) */
@media only screen and (min-width: 600px) and (max-width: 900px) {
    .create-btn {
        margin: 50px 50px;
        width: 150px;
        height: 40px;
        font-size: 14px;
    }

    .modal-content {
        width: 80%;
        max-width: 350px;
    }
}

/* Media Query for large screens (900px and above) */
@media only screen and (min-width: 900px) {
    .create-btn {
        margin: 75px 125px;
        width: 190px;
        height: 40px;
        font-size: 16px;
    }

    .modal-content {
        width: 90%;
        max-width: 400px;
    }
}
</style>

<div class="content">
    <!-- Create Button -->
    <button class="create-btn" onclick="openModal()">
        <i class="fas fa-route gradient-icon"></i>&nbsp;
        <b class="solid-text">Assign Routes</b>
    </button>

    <!-- Modal Popup -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h3>Create New Entry</h3>
            <form action="/assign" method="POST">
                @csrf
                <div class="form-group">
                    <label for="route">RAID:</label>
                    <input type="text" id="route" name="route" required>
                </div>
                <div class="form-group">
                    <label for="description">RID:</label>
                    <input type="text" id="description" name="description" required>
                </div>
                <div class="form-group">
                    <label for="description">UID:</label>
                    <input type="text" id="description" name="description" required>
                </div>
                <div class="form-group">
                    <label for="description">UID-RO:</label>
                    <input type="text" id="description" name="description" required>
                </div>
                <button type="submit" class="create-btn">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
// Open modal function
function openModal() {
    document.getElementById('createModal').style.display = 'flex';
}

// Close modal function
function closeModal() {
    document.getElementById('createModal').style.display = 'none';
}

// Close the modal if clicked outside
window.onclick = function(event) {
    var modal = document.getElementById('createModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>

@endsection
