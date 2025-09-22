<?php
include('DB.php');
session_start();

if (isset($_POST['register'])) {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $psw = isset($_POST['psw']) ? $_POST['psw'] : '';
    $hash = password_hash($psw, PASSWORD_DEFAULT);

    $image = $_FILES['profile-upload']['name'];
    $tmp_profile = $_FILES['profile-upload']['tmp_name'];

    $img_path = "uploads/" . $image;

    if (move_uploaded_file($tmp_profile, $img_path)) {
        $reg_query = "INSERT INTO `users` (`name`, `username`, `email`, `phone`, `password`, `profile`) 
                      VALUES ('$name', '$username', '$email', '$phone', '$hash', '$img_path')";
        $reg_res = mysqli_query($conn, $reg_query);

        if ($reg_res) {
            $_SESSION['success'] = "User registered successfully";
            header('Location: /login.php');
            exit();
        } else {
            $_SESSION['fail'] = "User not registered";
        }
    } else {
        $_SESSION['fail'] = "Please select a profile photo";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        max-width: 500px;
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
    .container input[type="password"],
    .container input[type="file"] {
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
        <a href="./index.php" style=" text-decoration:none ; color:white ">

            <div class="logo"><i class="fas fa-briefcase"></i> HireHunt</div>
        </a>
        <div class="actions">
            <button onclick="location.href='login.php'"><i class="fas fa-sign-in-alt"></i> Login</button>
        </div>
    </div>

    <div class="main">
        <?php include('notification.php'); ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="container">
                <h1><i class="fas fa-user-plus"></i> Register</h1>

                <label for="name"><b>Name</b></label>
                <input type="text" name="name" placeholder="Enter Name" required>

                <label for="username"><b>Username</b></label>
                <input type="text" name="username" placeholder="Enter username" required>

                <label for="email"><b>Email</b></label>
                <input type="text" name="email" placeholder="Enter email" required>

                <label for="phone"><b>Phone Number</b></label>
                <input type="text" name="phone" placeholder="Enter phone number" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" name="psw" placeholder="Enter password" required>

                <label for="image"><b>User Profile</b></label>
                <input type="file" name="profile-upload" required>

                <button type="submit" class="btn" name="register">
                    <i class="fas fa-user-plus"></i> Register
                </button>

                <div style="text-align: center; margin-top: 15px;">
                    <a href="./login.php" style="color: gray; text-decoration: none;">Already have an account? Login</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>