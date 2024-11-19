@extends('incharge.navbar')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 

<!-- Add a heading for the dashboard -->
<div class="dashboard-heading">
    <h1>Dashboard</h1>
</div>

<div class="analyse">
    <div class="card-container">
        <div class="card">
            <div class="status">
                <!-- Add Icon for Total Transactions -->
                <div class="icon gradient-icon-transaction">
                    <i class="fas fa-dollar-sign"></i> <!-- Font Awesome Dollar Sign Icon -->
                </div>
                <div class="info">
                    <h3>Total Transactions</h3>
                    <h1>$65,024</h1>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="status">
                <!-- Add Icon for Assign Routes -->
                <div class="icon gradient-icon-route">
                    <i class="fas fa-route"></i> <!-- Font Awesome Route Icon -->
                </div>
                <div class="info">
                    <h3>Assigned Routes</h3>
                    <h1>$65,024</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-heading {
        text-align: left;
        margin-top: 10px;
    }

    .dashboard-heading h1 {
        font-size: 36px;
        font-weight: bold;
        color: #333;
    }

    .analyse {
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 190px);
    }

    .card-container {
        display: flex;
        gap: 20px;
    }

    .card {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 2rem 3rem rgba(132, 139, 200, 0.18);
        padding: 20px;
        width: 500px;
        height: 300px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: transform 0.3s ease;
    }

    .icon {
        font-size: 80px;
        margin-bottom: 10px;
    }

    .gradient-icon-transaction {
        background: linear-gradient(#3DD0AE, #4BBaC2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .gradient-icon-route {
        background: linear-gradient(to bottom right, #E21C34, #500B28);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .info h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .info h1 {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .card:hover {
        transform: translateY(-10px);
    }

    /* Media Queries for Responsive Design */

    /* Large screens (desktops) */
    @media (min-width: 1024px) {
        .card {
            width: 500px;
            height: 300px;
        }
    }

    /* Medium screens (tablets, landscape phones) */
    @media (max-width: 1023px) {
        .dashboard-heading h1 {
            font-size: 28px;
        }

        .analyse {
            flex-direction: column;
            justify-content: flex-start;
            height: auto;
        }

        .card-container {
            flex-direction: column;
            gap: 15px;
        }

        .card {
            width: 90%;
            height: auto;
        }

        .icon {
            font-size: 60px;
        }

        .info h1 {
            font-size: 28px;
        }

        .info h3 {
            font-size: 18px;
        }
    }

    /* Small screens (mobile devices) */
    @media (max-width: 768px) {
        .dashboard-heading h1 {
            font-size: 24px;
        }

        .analyse {
            height: auto;
            padding: 10px;
        }

        .card {
            width: 100%;
            height: auto;
            padding: 15px;
        }

        .icon {
            font-size: 50px;
        }

        .info h1 {
            font-size: 24px;
        }

        .info h3 {
            font-size: 16px;
        }
    }

    /* Extra small screens (smaller mobile devices) */
    @media (max-width: 480px) {
        .dashboard-heading h1 {
            font-size: 20px;
        }

        .card {
            width: 100%;
            padding: 10px;
        }

        .icon {
            font-size: 40px;
        }

        .info h1 {
            font-size: 20px;
        }

        .info h3 {
            font-size: 14px;
        }
    }
</style>

@endsection
