<?php
include('server.php');
include('notification.php');

if (!isset($_SESSION['userId'])) {
    header('Location: ./login.php');
}

$userid = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';

$query = "SELECT * FROM job_applied WHERE user_id = $userid";
$res = mysqli_query($conn, $query);


if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_query = "DELETE FROM job_applied WHERE job_id = $id AND user_id = $userid"; ;
    $delete_res = mysqli_query($conn, $delete_query);
    
    if ($delete_res) {
        echo ' withdraw job application.';
        $_SESSION['success'] = 'Job withdrawn successfully.';
        header('Location: ./user_appliedjobs.php');
        
    } else {
        $_SESSION['fail'] = 'Job withdrawn failed.';
        echo 'Failed to withdraw job application.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="./css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<button class="btn btn-primary" style="font-weight:500; width:60px; height:35px;" onclick="window.location.href='./user_dashboard.php'"> back </button>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Requirment</th>
                <th>Education</th>
                <th>Experiance</th>
                <th>Date</th>
                <th>Status</th>
                <th>Withdraw</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($fetch = mysqli_fetch_array($res)) {
                $job_id = $fetch['job_id'];

                $j_query = "SELECT * FROM jobs WHERE id = $job_id";
                $j_res = mysqli_query($conn, $j_query);
                
                while ($j_fetch = mysqli_fetch_array($j_res)) {
            ?>
                    <tr>
                        <td data-column="Title"><?php echo $j_fetch['title'] ?></td>
                        <td data-column="Description"><?php echo $j_fetch['description'] ?></td>
                        <td data-column="Requirment"><?php echo $j_fetch['requirment'] ?></td>
                        <td data-column="Education"><?php echo $j_fetch['education'] ?></td>
                        <td data-column="Experiance"><?php echo $j_fetch['experiance'] ?></td>
                        <td data-column="Valid Date"><?php echo $j_fetch['validdate'] ?></td>
                        <td data-column="Status"><?php echo $j_fetch['status'] ?></td>
                        <td data-column="withdraw"><a href="./user_appliedjobs.php?id=<?php echo $j_fetch['id']?>">Withdraw</a></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</body>

</html>