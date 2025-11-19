    <?php
    session_start();

    $server = "localhost";
    $user = "root";
    $pass = "";
    $db_name = "restaurant_db";

    $conn = mysqli_connect($server, $user, $pass, $db_name);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_POST['login'])) {

        $log = $_POST['login_id'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM signup_tbl WHERE emailaddress = ? OR username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $log, $log);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $user = $result->fetch_assoc();

            // ROLE CHECK
            if ($user['role'] !== "Admin") {
                echo "<script>alert('This page is for ADMIN login only.');</script>";
            }

            // PASSWORD CHECK (NO HASH)
            else if ($password !== $user['password']) {
                echo "<script>alert('Incorrect password');</script>";
            }

            else {
                // LOGIN SUCCESS
                $_SESSION['user_id'] = $user['customer_id'];
                $_SESSION['username'] = $user['username'];

                header("Location: crud-admin.php");
                exit();
            }

        } else {
            echo "<script>alert('No user found with that username or email');</script>";
        }
    }








        $conn->close();
    ?>
    <!DOCTYPE html> 
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Restaurant Ordering Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #141414; /* Dark background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 380px;
            background-color: #1c1c1c; /* Dark box */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(255, 153, 0, 0.5); /* Orange glow */
            color: white;
            text-align: center; /* Center logo and heading */
        }

        /* Logo styling */
        .login-container img {
            width: 170px;
            height: 170px;
            margin-bottom: 15px;
            border-radius: 50%; /* Optional round logo */
        }

        .login-container h1 {
            font-size: 24px;
            margin-bottom: 25px;
            font-weight: bold;
            color: #ff9900; /* Bright orange header */
        }

        .login-container label {
            display: block;
            margin-top: 12px;
            font-size: 14px;
            color: #ccc;
            text-align: left; /* Keep labels left-aligned */
        }

        .login-container input,
        .login-container select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            outline: none;
            background-color: #333;
            color: white;
        }

        .login-container input::placeholder {
            color: #aaa;
        }

        .login-container input[type="submit"] {
            background-color: #ff9900;
            color: #000;
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
            font-size: 14px;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .sub-text {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
        }
    </style>
    </head>
    <body>

    <div class="login-container">
        <!-- Logo on top -->
        <img src="ChatGPT Image Sep 20, 2025, 02_28_43 AM.png" alt="Logo">

        <h1> Login As Admin </h1>
        <form method="POST" action="">
            <label>Username or Email</label>
            <input type="text" name="login_id" placeholder="Enter Username or Email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Password" required>

            <label>Account Type</label>
            <select name="choose" id="choose"  onchange="goToLogin()">
        <option value="">-- Select Account Type --</option>
        <option value="User">User</option>
        <option value="Admin">Admin</option>

    </select>

            <input type="submit" name="login" value="Login">

            <p class="sub-text">
                Don't have an account? <a href="sign-in.php">Register</a>
            </p>
        </form>
    </div>

    <script>
    function goToLogin() {
        let type = document.getElementById("choose").value;

        if (type === "User") {
            window.location.href = "login-user.php";     // user login page
        }

        if (type === "Admin") {
            window.location.href = "login-admin.php";    // admin login page
        }
    }
    </script>

    </body>
    </html>