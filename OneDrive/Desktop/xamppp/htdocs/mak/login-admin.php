   <?php
session_start();
include("connection_db.php");

if (isset($_POST['login'])) {

    $log = trim($_POST['login_id']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM signup_tbl WHERE emailaddress = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $log, $log);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('No account found');</script>";
    } else {
        $user = $result->fetch_assoc();

        // MUST MATCH EXACTLY "Admin"
        if (strtolower($user['role']) !== "admin") {
            echo "<script>alert('This page is for ADMIN ONLY.');</script>";
        } else {

            if ($password === $user['password'] || password_verify($password, $user['password'])) {

                // FIXED SESSION VALUES
                $_SESSION['user_id'] = $user['customer_id'];  
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = "Admin"; // ALWAYS CORRECT CASE

                header("Location: crud-admin.php");
                exit();

            } else {
                echo "<script>alert('Incorrect password');</script>";
            }
        }
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-image: url("uploads/brazilian-food-frame-with-copy-space.jpg");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    .login-container {
        width: 380px;
        background-color: #1c1c1c;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(255, 153, 0, 0.5);
        text-align: center;
        color: white;
    }

    .login-container img {
        width: 170px;
        height: 170px;
        margin-bottom: 15px;
        border-radius: 50%;
    }

    .login-container h1 {
        color: #ff9900;
        margin-bottom: 20px;
        font-size: 22px;
    }

    .login-container label {
        display: block;
        text-align: left;
        margin-top: 12px;
        color: #ccc;
    }

    .login-container input,
    .login-container select {
        width: 100%;
        padding: 12px;
        margin: 8px 0 15px 0;
        border: none;
        border-radius: 4px;
        background-color: #333;
        color: white;
    }

    .login-container input[type="submit"] {
        background-color: #ff9900;
        color: black;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .login-container input[type="submit"]:hover {
        background-color: #ff7700;
    }

    .login-container a {
        color: #ff9900;
        text-decoration: none;
    }

    .sub-text {
        margin-top: 15px;
        font-size: 13px;
    }
</style>
</head>
<body>

<div class="login-container">

    <img src="ChatGPT Image Sep 20, 2025, 02_28_43 AM.png" alt="Logo">

    <h1>Login as Admin</h1>

    <form method="POST">

        <label>Username or Email</label>
        <input type="text" name="login_id" placeholder="Enter Username or Email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter Password" required>

        <label>Account Type</label>
        <select id="choose" onchange="goToLogin()">
            <option value="">-- Select Account Type --</option>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select>

        <input type="submit" name="login" value="Login">

        <p class="sub-text">
            Don’t have an account? <a href="sign-in.php">Register</a>
        </p>

    </form>
</div>

<script>
function goToLogin() {
    let type = document.getElementById("choose").value;

    if (type === "User") window.location.href = "login-user.php";
    if (type === "Admin") window.location.href = "login-admin.php";
}
</script>

</body>
</html>
