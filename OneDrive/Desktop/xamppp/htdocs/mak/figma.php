<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<style>

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f3f4f6;
        display: flex;
        height: 100vh;
        overflow: hidden;
    }

    /* Sidebar */
    .sidebar {
        width: 260px;
        background: #1f2937;
        color: white;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        transition: transform 0.3s ease;
    }

    .sidebar.hidden {
        transform: translateX(-260px);
    }

    .sidebar h2 {
        color: #f97316;
        text-align: center;
        margin-bottom: 20px;
        font-size: 22px;
    }

    .menu button {
        background: none;
        border: none;
        color: #e5e7eb;
        text-align: left;
        padding: 12px;
        font-size: 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.2s;
    }

    .menu button:hover,
    .menu button.active {
        background: #374151;
    }

    .logout {
        margin-top: auto;
        background: #dc2626 !important;
        color: white;
    }

    /* Main Section */
    .main {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    /* Topbar */
    .topbar {
        background: white;
        padding: 15px 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #ddd;
    }

    /* Hamburger for mobile */
    .hamburger {
        width: 28px;
        cursor: pointer;
        display: none;
    }
    .hamburger div {
        height: 3px;
        background: #111;
        margin: 6px;
    }

    /* Dashboard Content */
    .content {
        padding: 25px;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .card h3 {
        font-size: 18px;
        color: #6b7280;
    }

    .card .value {
        font-size: 32px;
        font-weight: bold;
        color: #111827;
        margin-top: 10px;
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    th {
        background: #f97316;
        color: white;
        text-align: left;
    }

    tr:hover {
        background: #f3f4f6;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .hamburger {
            display: block;
        }
    }

</style>

</head>
<body>

<!-- Sidebar -->
<div id="sidebar" class="sidebar hidden">
    <h2>Admin Panel</h2>

    <div class="menu">
        <button class="active">Dashboard</button>
        <button>Menu</button>
        <button>Orders</button>
        <button>Transactions</button>
        <button>Users</button>
    </div>

    <button class="logout" onclick="alert('Logging out...')">Logout</button>
</div>

<!-- Main Content -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div class="hamburger" onclick="toggleSidebar()">
            <div></div><div></div><div></div>
        </div>
        <h3 style="color:#f97316;">Restaurant Admin</h3>
        <span>👤 Admin</span>
    </div>

    <!-- Dashboard Content -->
    <div class="content">

        <!-- Cards -->
        <div class="cards">
            <div class="card">
                <h3>Total Sales</h3>
                <div class="value">₱ 12,850</div>
            </div>

            <div class="card">
                <h3>Total Orders</h3>
                <div class="value">324</div>
            </div>

            <div class="card">
                <h3>New Customers</h3>
                <div class="value">58</div>
            </div>

            <div class="card">
                <h3>Transactions Today</h3>
                <div class="value">41</div>
            </div>
        </div>

        <!-- Table -->
        <h2 style="margin-bottom: 10px;">Recent Transactions</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>#0001</td>
                <td>John Reyes</td>
                <td>₱ 245</td>
                <td>Completed</td>
            </tr>
            <tr>
                <td>#0002</td>
                <td>Mary Cruz</td>
                <td>₱ 520</td>
                <td>Pending</td>
            </tr>
            <tr>
                <td>#0003</td>
                <td>Carlos D.</td>
                <td>₱ 180</td>
                <td>Completed</td>
            </tr>
        </table>

    </div>
</div>


<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("hidden");
}
</script>

</body>
</html>
