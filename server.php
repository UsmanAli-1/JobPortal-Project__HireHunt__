<?php
include('DB.php');
session_start();

$userid = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';
// logout code ==================

if (isset($_POST['logout_btn']) ) {
    session_unset();
    session_destroy();
    session_write_close();
    $url = "./index.php";
    header("Location: $url");
    exit;
}

// create job code ==================

if (isset($_POST['create'])) {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $requirment = isset($_POST['requirment']) ? $_POST['requirment'] : '';
    $education = isset($_POST['education']) ? $_POST['education'] : '';
    $experiance = isset($_POST['experiance']) ? $_POST['experiance'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    $query = "INSERT INTO `jobs` (`title` ,`description` ,`requirment`,`education`,`experiance` , `validdate` , `status`) VALUES ('$title' , '$description' , '$requirment' , '$education' , '$experiance' , '$date' , '$status') ";
    $res = mysqli_query($conn, $query);

    if ($res) {
        $_SESSION['success'] = "Job createtd successfully";
        header('Location: /admin_dashboard.php');
    } else {
        $_SESSION['fail'] = "job not created ";
    }
}

///////////////////////////////////////(admin dashboard)///////////////////////////////////////

// show job on admin dashboard===============

$admin_query = "SELECT * FROM `jobs` ";
$admin_res = mysqli_query($conn, $admin_query);

// admin edit profile code ==================


if ($userid != '') {

    $getquery = "SELECT * FROM users WHERE id = $userid";
    $getres = mysqli_query($conn, $getquery);
    $getfetch = mysqli_fetch_array($getres);
    
}
if (isset($_POST['adminupdate'])) {

    // on form click data get manipulate in DB ==================-======== 

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
        $que = $query = "UPDATE users 
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

    if ($u_res) {
        $_SESSION['success'] = "user update successfully";
        header('Location: /admin_dashboard.php');
    } else {
        $_SESSION['fail'] = "user not updated  ";
    }
}

// edit job code using ajax ==================

if (isset($_POST['e_job_id'])) {

    $jobId = isset($_POST['e_job_id']) ? base64_decode($_POST['e_job_id']) : '';

    $query = "SELECT * FROM jobs WHERE id = $jobId ";
    $res = mysqli_query($conn, $query);
    $fetch = mysqli_fetch_array($res);

    echo json_encode($fetch);
}

if (isset($_POST['save'])) {
    $id = isset($_POST['jobbid']) ? ($_POST['jobbid']) : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $requirment = isset($_POST['requirment']) ? $_POST['requirment'] : '';
    $education = isset($_POST['education']) ? $_POST['education'] : '';
    $experiance = isset($_POST['experiance']) ? $_POST['experiance'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    $query = "UPDATE `jobs` 
    SET `title` = '$title', 
        `description` = '$description',
        `requirment` = '$requirment',
        `education` = '$education',
        `experiance` = '$experiance',
        `validdate` = '$date',
        `status` = '$status' 
    WHERE id = $id";
    $res = mysqli_query($conn, $query);

    if ($res) {
        $_SESSION['success'] = "Job updated successfully";
        header('Location: /admin_dashboard.php');
    } else {
        $_SESSION['fail'] = "job not updated ";
    }
}
// edit job code using ajax ==================


// AJAX request to update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $job_id = isset($_POST['job_id']) ? $_POST['job_id'] : '';

    $s_query = "UPDATE job_applied SET `status` = '$status' WHERE  job_id = $job_id AND user_id = $user_id ";
    $s_res = mysqli_query($conn, $s_query);
    // $s_fetch = mysqli_fetch_array($s_res);

    

    if ($s_res) {
        $_SESSION['success'] = 'Candidate status updated successfully.';
        echo 'success';
    } else {
        echo 'error: ' . mysqli_error($conn);
    }
    exit;
}


// Get job data only if ID is present
if (isset($_POST['shortlid'])) {
    $job_id = base64_decode($_POST['shortlid']);

    $query = "SELECT * FROM jobs WHERE id = $job_id ";
    $res = mysqli_query($conn, $query);
    $fetch = mysqli_fetch_array($res);

    $j_query = "SELECT job_applied.id ,job_applied.job_id , job_applied.user_id ,job_applied.applied_date ,job_applied.status 
    AS job_status, users.*, user_details.*
        FROM job_applied
        INNER JOIN users ON job_applied.user_id = users.id
        INNER JOIN user_details ON users.id = user_details.user_id
        WHERE job_applied.job_id = $job_id";

    $j_res = mysqli_query($conn, $j_query);
    $j_fetch = mysqli_fetch_all($j_res, MYSQLI_ASSOC);


    $dd = [
        'job' => $fetch,
        'applied' => $j_fetch,
        'applied_count' => count($j_fetch),
    ];
    
    echo json_encode($dd, true);
} else {
    $job_id = null;
    $res = false;
    $j_res = false;
}


///////////////////////////////////////(user dashboard)///////////////////////////////////////

// show job on user dashboard=================

$user_query = "SELECT * FROM `jobs` WHERE status = 'open' ";
$u_res = mysqli_query($conn, $user_query);


// user edit profile code ==================

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

// user edit profile code ==================




// search job code ==================

if (isset($_POST['search_btn'])) {
    $search = isset($_POST['search']) ? $_POST['search'] : '';

    $u_query = "SELECT * FROM `jobs` 
    WHERE `title` LIKE '%$search%' OR `description` LIKE '%$search%' OR `requirment` LIKE '%$search%' OR `education` LIKE '%$search%' OR `experiance` LIKE '%$search%'";
    $u_res = mysqli_query($conn, $u_query);
}
