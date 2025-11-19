<?php 

$message1 = "";
$message = "";

$server = "localhost";
$user = "root";
$pass = "";
$db_name = "restaurant_db";

$conn = mysqli_connect($server, $user, $pass, $db_name);

if (!$conn) {
    die("Database connection error");
}

if (isset($_POST['clicked'])) {

    $nusername = $_POST['username'];
    $raw_password = $_POST['password']; // <- raw password
    $eaddress = $_POST['email'];
    $cnumber = $_POST['contactnumber'];
   

    // ✔ Correct validation (check raw password)
    if (strlen($raw_password) < 8) {
        $message1 = "Password must be at least 8 characters.";
    } else {
        $message1 = "Password is valid.";
    }

    // ✔ Hash only AFTER validation
    $npassword = PASSWORD_HASH($raw_password, PASSWORD_BCRYPT);

    // Insert query
    $stmt = $conn->prepare("INSERT INTO signup_tbl (username, password, emailaddress, contactnumber) 
    VALUES (?, ?, ?, ?)");

    $stmt->bind_param("ssss", $nusername, $npassword, $eaddress, $cnumber);

    if ($stmt->execute()) {
        $message = "Account created successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account</title>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #141414;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .signup-container {
        width: 380px;
        background-color: #1c1c1c;
        padding: 40px;
        border-radius: 8px;
        color: white;
        box-shadow: 0 0 20px rgba(255, 153, 0, 0.5);
        text-align: center;
    }

    .signup-container img {
        width: 170px;
        height: 170px;
        margin-bottom: 15px;
        border-radius: 50%;
    }

    .signup-container h2 {
        margin-bottom: 25px;
        font-weight: bold;
        color: #ff9900;
    }

    .signup-container label {
        display: block;
        margin-top: 12px;
        font-size: 14px;
        color: #ccc;
        text-align: left;
    }

    .signup-container input,
    .signup-container select {
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

    .signup-container input[type="submit"] {
        background-color: #ff9900;
        color: black;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .signup-container input[type="submit"]:hover {
        background-color: #ff7700;
    }

    .signup-container a {
        color: #ff9900;
        text-decoration: none;
        font-size: 14px;
    }

    .signup-container a:hover {
        text-decoration: underline;
    }

    .sub-text {
        text-align: center;
        margin-top: 12px;
        font-size: 13px;
    }

    .msg {
        margin-top: 10px;
        color: #ff9900;
        font-size: 14px;
    }
</style>

</head>
<body>

<div class="signup-container">

    <img src="ChatGPT Image Sep 20, 2025, 02_28_43 AM.png" alt="Logo">

    <h2>Create Account</h2>

    <!-- Display messages -->
    <?php if (!empty($message1)) echo "<p class='msg'>$message1</p>"; ?>
    <?php if (!empty($message)) echo "<p class='msg'>$message</p>"; ?>

    <form method="POST" action="">

        <label>New Username</label>
        <input type="text" name="username" placeholder="Enter Username" required>

        <label>New Password</label>
        <input type="password" name="password" placeholder="Enter Password" required>

        <label>Email Address</label>
        <input type="email" name="email" placeholder="Enter Email" required>

        <label>Contact Number</label>
        <input type="text" name="contactnumber" placeholder="Enter Contact Number" required>

       

        <input type="submit" name="clicked" value="Create Account">

        <p class="sub-text">
            Already signed up? <a href="login-user.php">Go to Login</a>
        </p>
    </form>
</div>

</body>
</html>
