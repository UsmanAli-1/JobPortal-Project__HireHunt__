<?php
include('server.php');

// user id who is currently login  =============

$userid = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';    


// for get data to show in fileds ==============================

$getquery = "SELECT * FROM users WHERE id = $userid";
$getres = mysqli_query($conn, $getquery);
$getfetch = mysqli_fetch_array($getres);


$detailquery = "SELECT * FROM user_details WHERE user_id = $userid";
$detailres = mysqli_query($conn, $detailquery);
$detailfetch = mysqli_fetch_array($detailres);

// on form click data get manipulate in DB ==================-======== 

if (isset($_POST['userupdate'])) {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $psw = isset($_POST['psw']) ? $_POST['psw'] : '';

    // for image upload or update AND also basic info  ============================

    $image = $_FILES['profileupload']['name'];
    $temp = $_FILES['profileupload']['tmp_name'];

    $img_path = 'uploads/' . $image;
    if (move_uploaded_file($temp, $img_path)) {
        $que = "UPDATE users 
        SET profile = '$img_path' 
        WHERE id = '$userid'";
        $re = mysqli_query($conn, $que);
    };

    $u_query = "UPDATE users 
          SET 
              name = '$name', 
              username = '$username', 
              email = '$email', 
              phone = '$phone', 
              password = '$psw' 
          WHERE id = '$userid'";
    $u_res = mysqli_query($conn, $u_query);


    //  for user details , insert and update for both =============================

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $experiance = isset($_POST['experiance']) ? $_POST['experiance'] : '';
    $education = isset($_POST['education']) ? $_POST['education'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $job = isset($_POST['job']) ? $_POST['job'] : '';
    $salary = isset($_POST['salary']) ? $_POST['salary'] : '';

    // condition whether to insert or update data ==============================

    if (mysqli_num_rows($detailres) > 0) {

        if ($_FILES['resumeupload']['name'] == '') {
            $verified_path = $detailfetch['resume'];
        } else {
            $resume = $_FILES['resumeupload']['name'];
            $temp_resume = $_FILES['resumeupload']['tmp_name'];

            $cv_path = "resume/" . basename($resume);
            $cv_ext = strtolower(pathinfo($cv_path, PATHINFO_EXTENSION));

            if ($cv_ext == 'pdf' || $cv_ext == 'doc' || $cv_ext == 'docx') {
                if (move_uploaded_file($temp_resume, $cv_path)) {
                    $verified_path = $cv_path;
                }
            } else {
                $_SESSION['fail'] = 'Wronge file extension';
            }
        }



        $query = "UPDATE user_details 
          SET 
              job_title = '$title', 
              experiance = '$experiance', 
              education = '$education', 
              address = '$address', 
              current_job = '$job', 
              current_salary = '$salary',
              resume = '$verified_path'
          WHERE user_id = '$userid'";
        $res = mysqli_query($conn, $query);
    } else {

        $resume = $_FILES['resumeupload']['name'];
        $temp_resume = $_FILES['resumeupload']['tmp_name'];

        $cv_path = "resume/" . basename($resume);
        $cv_ext = strtolower(pathinfo($cv_path, PATHINFO_EXTENSION));

        if ($cv_ext == 'pdf' || $cv_ext == 'doc' || $cv_ext == 'docx') {
            if (move_uploaded_file($temp_resume, $cv_path)) {
                $verified_path = $cv_path;
            };
        } else {
            $_SESSION['fail'] = 'Wronge file extension';
            exit;
        };

        $query = "INSERT INTO user_details (job_title, experiance, education, address, current_job, current_salary , user_id , resume) 
                VALUES ('$title', '$experiance', '$education', '$address', '$job', '$salary' , '$userid' , '$verified_path')";
        $res = mysqli_query($conn, $query);
    }


    if ($res || $u_res) {
        $_SESSION['success'] = "user update successfully";
        header('Location: /user_dashboard.php');
        exit;
    } else {
        $_SESSION['fail'] = "user not updated  ";
    }
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job</title>
    <link rel="stylesheet" href="./css/form.css">

</head>

<body>

    <form method="POST" class="horizontal-form" enctype="multipart/form-data">
        <div class="container">
            <h1><i class="fas fa-user-circle"></i> User Details</h1>

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
                        Resume:
                        <?php echo $detailfetch['resume']; ?>
                    </p>
                <?php } ?>
            </div>

            <button type="submit" class="btn" name="userupdate">
                <i class="fas fa-sign-in-alt"></i> Save
            </button>
        </div>
    </form>

</body>

</html>