<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link rel="stylesheet" href="../css/sidebar.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />


    <title>CollectX</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
    </style>

<script type="text/javascript" src="../js/sidebar.js"></script>


</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <h2>Collect<span class="danger">X</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>

            <div class="sidebar">
                <a href="/incharge/dashboard" class="{{ request()->is('incharge/dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">grid_view</span>
                    <h3>Dashboard</h3>
                </a>
                <a href="/incharge/assignedlabs" class="{{ request()->is('incharge/assignedlabs') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">medical_services</span>
                    <h3>Assigned Labs</h3>
                </a>
                <a href="/incharge/rassign" class="{{ request()->is('incharge/rassign') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">add_road</span>
                    <h3>Route Assign</h3>
                </a>
                <a href="/incharge/transaction" class="{{ request()->is('incharge/transaction') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">attach_money</span>
                    <h3>Transactions</h3>
                </a>
                <a href="/incharge/shortage-excess" class="{{ request()->is('incharge/shortage-excess') ? 'active' : '' }}">
                    <span class="material-symbols-sharp">currency_exchange</span>
                    <h3>Shortage & Excess</h3>
                </a>
                <a href="/">
                    <span class="material-symbols-sharp">logout</span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>

        <!-- End of Sidebar Section -->


        <div class="content">
            <div class="top-bar">
                <h1></h1>
                <div class="right-section">
                    <div class="nav">
                        <button id="menu-btn">
                            <span class="material-icons-sharp">
                                menu
                            </span>
                        </button>
                        <div class="dark-mode">
                            <span class="material-icons-sharp active">
                                light_mode
                            </span>
                            <span class="material-icons-sharp">
                                dark_mode
                            </span>
                        </div>

                        <div class="profile" id="profileDropdown">
                            <div class="info">
                                <p>Hey, <b>{{ session('fname', 'Guest') }}</b></p>
                                <small class="text-muted">{{ session('role', 'Unknown Role') }}</small>
                            </div>
                            <!-- <div class="profile-photo">
                                <img src="{{ asset('storage/' . Session::get('image')) }}" alt="Profile"
                                    onerror="this.style.display='none'; this.parentNode.innerHTML += '<i class=\'bx bxs-user-circle\' style=\'font-size: 40px; color: rgb(156,161,221);\'></i>';">
                            </div> -->

                            <div class="profile-photo">
                                @if(Session::has('image') && Session::get('image'))
                                <img src="{{ asset('storage/' . Session::get('image')) }}" alt="Profile"
                                    onerror="this.onerror=null; this.src='{{ asset('image/default-profile.png') }}';">
                                @else
                                <img src="{{ asset('image/default-profile.png') }}" alt="Default Profile">
                                @endif
                            </div>

                            <!-- Dropdown Menu -->
                            <div class="dropdown-menu">
                                <h4><strong>{{ session('role', 'Unknown Role') }}</strong></h4>
                                <div>{{ session('username', 'Guest') }}</div>
                                <!-- <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class='bx bx-cog'></i> Settings
                                </a> -->
                                <div class="dropdown-divider"></div>
                                <a href="/incharge/changepassword" class="dropdown-item">
                                    <i class='bx bxs-lock-alt'></i> Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item text-danger">
                                    <i class='bx bx-log-out'></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>


    </div>

    <script>
    const sideMenu = document.querySelector('aside');
    const menuBtn = document.getElementById('menu-btn');
    const closeBtn = document.getElementById('close-btn');
    const darkMode = document.querySelector('.dark-mode');

    // Toggle dark mode and save preference to local storage
    darkMode.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode-variables');
        const isDarkMode = document.body.classList.contains('dark-mode-variables');

        // Save the current mode in local storage
        localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');

        // Toggle active states on the dark mode icons
        darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
        darkMode.querySelector('span:nth-child(2)').classList.toggle('active');
    });

    // Function to apply dark mode based on saved preference
    function applyDarkModePreference() {
        const darkModePreference = localStorage.getItem('darkMode');
        if (darkModePreference === 'enabled') {
            document.body.classList.add('dark-mode-variables');
            darkMode.querySelector('span:nth-child(1)').classList.remove('active');
            darkMode.querySelector('span:nth-child(2)').classList.add('active');
        } else {
            document.body.classList.remove('dark-mode-variables');
            darkMode.querySelector('span:nth-child(1)').classList.add('active');
            darkMode.querySelector('span:nth-child(2)').classList.remove('active');
        }
    }

    function applyDarkModePreference() {
        const darkModePreference = localStorage.getItem('darkMode');
        if (darkModePreference === 'enabled') {
            document.body.classList.add('dark-mode-variables');
            darkMode.querySelector('span:nth-child(1)').classList.remove('active');
            darkMode.querySelector('span:nth-child(2)').classList.add('active');
        } else {
            document.body.classList.remove('dark-mode-variables');
            darkMode.querySelector('span:nth-child(1)').classList.add('active');
            darkMode.querySelector('span:nth-child(2)').classList.remove('active');
        }
    }

    // Apply dark mode preference on page load
    window.addEventListener('load', applyDarkModePreference);

    menuBtn.addEventListener('click', () => {
        sideMenu.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => {
        sideMenu.style.display = 'none';
    });
    </script>

<script>
    function checkSession() {
        fetch("{{ url('/check-session') }}")
            .then(response => response.json())
            .then(data => {
                if (data.status === "session_expired") {
                    alert(data.message);
                    window.location.href = "{{ route('logout') }}"; // Redirect to logout
                }
            });
    }

    setInterval(checkSession, 1 * 60 * 1000); // Check session every 5 minutes
</script>


</body>


</html>