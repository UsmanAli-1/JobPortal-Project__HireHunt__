<?php
include('server.php');
include('notification.php');

if (!isset($_SESSION['userId'])) {
    header('Location: ./index.php');
}



$userid = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';

$app_job = "SELECT job_id FROM `job_applied` WHERE user_id = $userid";
$ap_job_q = mysqli_query($conn, $app_job);
$my_jobs = mysqli_fetch_all($ap_job_q);

$my_job_arr = array_column($my_jobs, 0);


if (isset($_GET['id'])) {

    $c_query = "SELECT * FROM user_details WHERE user_id = $userid";
    $c_res = mysqli_query($conn, $c_query);

    if (mysqli_num_rows($c_res) > 0) {

        $jobid = base64_decode($_GET['id']);
        $date = date("Y/m/d");


        $query = "INSERT INTO `job_applied` (`job_id` ,`user_id`,`applied_date`)
     VALUES ('$jobid' , '$userid' , '$date' ) ";
        $res = mysqli_query($conn, $query);

        if ($res) {
            $_SESSION['success'] = "Job applied successfully";
            header('Location: /user_dashboard.php');
        } else {
            $_SESSION['fail'] = "job not applied ";
        }
    } else {
        $_SESSION['fail'] = "User Details Missing ";
        header('Location: /user_dashboard.php');
        exit;
    };
}

$user_n = "SELECT * FROM `users` WHERE id = $userid";
$n_res = mysqli_query($conn, $user_n);
$n_fetch = mysqli_fetch_array($n_res);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <header>
        <h1>Welcome , <?php echo $n_fetch['name'] ?></h1>
        <div class="actions">
            <form action="" method="post" class="search-bar">
                <input type="text" name="search" placeholder="Search Jobs">
                <button type="submit" name="search_btn" class="btn" >Search</button>
            </form>
            <form action="server.php" method="post" >
                <input type="hidden" name="logout_user" value="logout">
                <a href="./user_appliedjobs.php" class="btn" style="padding-top:10px ;">Manage Applied Jobs</a>
                <a data-toggle="modal" data-target="#usereditprofile" class="btn">Edit Profile</a>
                <button type="submit" name="logout_btn" class="btn" style="padding-top: 10px;">Logout</button>
            </form>
        </div>
    </header>


    <?php
    // u_res is comming from server.php 

    while ($fetch = mysqli_fetch_array($u_res)) { ?>
        <section class="job-card">
            <h2 class="job-title"><?php echo $fetch['title'] ?></h2>
            <p class="job-description"><?php echo $fetch['description'] ?></p>
            <ul class="job-details">
                <li><strong>Requirement:</strong> <?php echo $fetch['requirment'] ?></li>
                <li><strong>Education:</strong> <?php echo $fetch['education'] ?></li>
                <li><strong>Experience:</strong> <?php echo $fetch['experiance'] ?></li>
                <li><strong>Valid Until:</strong> <?php echo $fetch['validdate'] ?></li>
                <li><strong>Status:</strong> <?php echo $fetch['status'] ?></li>
            </ul>
            <?php if (in_array($fetch['id'], $my_job_arr)) { ?>

                <div class="apply-button">
                    <a href="javascript:;" class="btn applied" disabled>Applied</a>

                </div>

            <?php } else { ?>

                <div class="apply-button">
                    <a href="user_dashboard.php?id=<?php echo base64_encode($fetch['id']); ?>" class="btn">Apply Job</a>

                </div>


            <?php } ?>
        </section>
    <?php } ?>

    <!-- Modal Form for Edit user profile  -->
    <div class="modal fade" id="usereditprofile" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel" style="color: #3c7fd6;">Edit Profile</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="horizontal-form" enctype="multipart/form-data">
                        <div class="container">

                            <div class="form-group">
                                <label for="name"><b>Name</b></label>
                                <input type="text" name="name" placeholder="Enter Name" value="<?php echo $getfetch['name'] ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="username"><b>Username</b></label>
                                <input type="text" name="username" placeholder="Enter Username"
                                    value="<?php echo $getfetch['username'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email"><b>Email</b></label>
                                <input type="text" name="email" placeholder="Enter Email" value="<?php echo $getfetch['email'] ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="phone"><b>Phone Number</b></label>
                                <input type="text" name="phone" placeholder="Enter Phone Number"
                                    value="<?php echo $getfetch['phone'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="psw"><b>Password</b></label>
                                <input type="password" name="psw" placeholder="Enter Password" value="<?php echo $getfetch['password'] ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="profile-upload"><b>Update Profile</b></label>
                                <input type="file" name="profileupload">
                            </div>

                            <div class="form-group">
                                <label for="title"><b>Job Title</b></label>
                                <input type="text" name="title" placeholder="Enter Job Title"
                                    value="<?php echo isset($detailfetch['job_title']) ? $detailfetch['job_title'] : '' ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="experiance"><b>Experience</b></label>
                                <input type="text" name="experiance" placeholder="Enter Experience"
                                    value="<?php echo isset($detailfetch['experiance']) ? $detailfetch['experiance'] : '' ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="education"><b>Education</b></label>
                                <input type="text" name="education" placeholder="Enter Education"
                                    value="<?php echo isset($detailfetch['education']) ? $detailfetch['education'] : '' ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="address"><b>Address</b></label>
                                <input type="text" name="address" placeholder="Enter Address"
                                    value="<?php echo isset($detailfetch['address']) ? $detailfetch['address'] : '' ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="job"><b>Current Job</b></label>
                                <input type="text" name="job" placeholder="Enter Current Job"
                                    value="<?php echo isset($detailfetch['current_job']) ? $detailfetch['current_job'] : '' ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="salary"><b>Current Salary</b></label>
                                <input type="text" name="salary" placeholder="Enter Current Salary"
                                    value="<?php echo isset($detailfetch['current_salary']) ? $detailfetch['current_salary'] : '' ?>"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="profile-upload"><b>Update Resume</b></label>
                                <?php if (empty($detailfetch['resume'])) { ?>
                                    <input type="file" name="resumeupload" required>
                                <?php } else { ?>
                                    <p>
                                        <input type="file" name="resumeupload" value="<?php echo $detailfetch['resume']; ?>">
                                        <br/>Resume:
                                        <?php echo $detailfetch['resume']; ?>
                                    </p>
                                <?php } ?>
                            </div>

                            <button type="submit" class="btn" name="userupdate">
                                <i class="fas fa-sign-in-alt"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Form for Edit user profile  -->

      <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/popper.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

</body>

</html>