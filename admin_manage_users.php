<?php
include('server.php');
include('notification.php');

if (!isset($_SESSION['userId'])) {
    header('Location: ./login.php');
}

$query = "SELECT * FROM `users` WHERE role = 'user'";
$res = mysqli_query($conn, $query);


if (isset($_GET['id'])) {
    $id = base64_decode($_GET['id']);

    $d_query = "DELETE FROM users WHERE id = $id";
    $d_res = mysqli_query($conn, $d_query);

    if ($d_res) {
        $_SESSION['success'] = 'User deleted successfully';
    } else {
        $_SESSION['fail'] = 'User not deleted';
    }
    header('Location: /admin_manage_users.php');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="./css/dashboard.css">

</head>

<body>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Username</th>
                <th>Role</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fetch = mysqli_fetch_array($res)) { ?>
                <tr>
                    <td data-column="First Name"><?php echo $fetch['name'] ?></td>
                    <td data-column="Email"><?php echo $fetch['email'] ?></td>
                    <td data-column="Contact"><?php echo $fetch['phone'] ?></td>
                    <td data-column="Username"><?php echo $fetch['username'] ?></td>
                    <td data-column="Role"><?php echo $fetch['role'] ?></td>
                   <td data-column="Delete"><a href="./admin_manage_users.php?id=<?php echo base64_encode($fetch['id']) ?> ">Delete</a></td>

                </tr>
            <?php } ?>

        </tbody>
    </table>
</body>

</html>