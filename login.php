<?php
include('DB.php');
session_start();
if (isset($_POST['login'])) {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $psw = isset($_POST['psw']) ? $_POST['psw'] : '';

    $log_query = "SELECT * FROM `users` WHERE username = '$username' OR email = '$username'";
    $log_res = mysqli_query($conn, $log_query);

    if ($log_res && mysqli_num_rows($log_res) > 0) {
        $log_fetch = mysqli_fetch_array($log_res);

        if (password_verify($psw, $log_fetch['password']) && $log_fetch['role'] == 'admin') {
            $_SESSION['success'] = 'login successfull';
            $_SESSION['userId'] = $log_fetch['id'];
            header('Location: /admin_dashboard.php');
            exit;
        } else if (password_verify($psw, $log_fetch['password']) && $log_fetch['role'] == 'user') {
            $_SESSION['success'] = 'login successfull';
            $_SESSION['userId'] = $log_fetch['id'];
            header('Location: /user_dashboard.php');
            exit;
        } else {
            $_SESSION['fail'] = 'Wronge credentials';
        }
    } else {
        $_SESSION['fail'] = 'User Not Found';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    :root {
        --primary-color: #5c9ded;
        --secondary-color: #326ab7;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5faff;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Navbar */
    .navbar {
        background-color: var(--primary-color);
        color: white;
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        width: 100%;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar .logo {
        font-size: 24px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .navbar .actions {
        display: flex;
        gap: 12px;
    }

    .navbar .actions button {
        background-color: white;
        color: var(--primary-color);
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .navbar .actions button:hover {
        background-color: var(--secondary-color);
        color: white;
    }

    .navbar .actions button i {
        margin-right: 6px;
    }

    /* Center form */
    .main {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px 15px;
    }

    .container {
        max-width: 450px;
        margin: auto;
        background: white;
        padding: 30px 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .container h1 {
        text-align: center;
        color: #5c9ded;
        margin-bottom: 20px;
        font-size: 24px;
    }

    .container label {
        font-weight: 600;
        color: #2d3e50;
        display: block;
        margin-top: 15px;
    }

    .container input[type="text"],
    .container input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-top: 6px;
        background-color: #fdfdfd;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .container input:focus {
        outline: none;
        border-color: #5c9ded;
    }

    .container .btn {
        background-color: #5c9ded;
        color: white !important;
        border: none;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        margin-top: 25px;
        cursor: pointer;
        width: 100%;
        display: inline-block;
        transition: background 0.3s ease;
    }

    .container .btn:hover {
        background-color: #3c7fd6;
        color: white !important;
    }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="./index.php" style="text-decoration:none; color:white;">
            <div class="logo"><i class="fas fa-briefcase"></i> HireHunt</div>
        </a>
        <div class="actions">
            <button onclick="location.href='register.php'"><i class="fas fa-user-plus"></i> Register</button>
        </div>
    </div>

    <div class="main">
        <?php include('notification.php'); ?>
        <form method="POST">
            <div class="container">
                <h1><i class="fas fa-user-circle"></i> Login</h1>

                <label for="name"><b>Username / Email</b></label>
                <input type="text" name="username" placeholder="Enter username or email" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" name="psw" placeholder="Enter password" required>

                <button type="submit" class="btn" name="login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

            </div>
        </form>
    </div>
</body>

</html>