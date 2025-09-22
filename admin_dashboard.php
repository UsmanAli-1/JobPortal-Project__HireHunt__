<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="./css/form.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <?php
    include('server.php');
    include('notification.php');
    if (!isset($_SESSION['userId'])) {
        header('Location: ./index.php');
    }

    $userid = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = base64_decode($_GET['id']);
        $del_job_query = "DELETE FROM jobs WHERE id = $id";
        $del_job_res = mysqli_query($conn, $del_job_query);

        if ($del_job_res) {
            $_SESSION['success'] = 'Job deleted successfully';
            header('Location: /admin_dashboard.php');
            exit;
        } else {
            $_SESSION['fail'] = 'Job not deleted';
        }
    }

    if (isset($_SESSION['userId'])) {
        $id = $userid;
        $l_query = "SELECT * FROM `users` WHERE id = '$id'";
        $l_res = mysqli_query($conn, $l_query);
        $l_fetch = mysqli_fetch_array($l_res);

        if (isset($l_fetch['role']) && $l_fetch['role'] == 'admin') {
            $admin_query = "SELECT * FROM jobs";
            $admin_res = mysqli_query($conn, $admin_query);
    ?>

    <header>
        <h1>Welcome , <?php echo $l_fetch['name'] ?></h1>
        <div class="actions">
            <form method="post">
                <a data-toggle="modal" data-target="#editProfile" class="btn">Edit Profile</a>
            </form>
            <a href="./admin_manage_users.php" class="btn">Manage Users</a>
            <a data-toggle="modal" data-target="#createJob" class="btn">Create Job</a>
            <form action="server.php" method="post">
                <button type="submit" name="logout_btn" class="btn" style="border: none;">Logout</button>
            </form>
        </div>
    </header>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Requirement</th>
                <th>Education</th>
                <th>Experience</th>
                <th>Date</th>
                <th>Status</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Short List</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($admin_fetch = mysqli_fetch_array($admin_res)) { ?>
            <tr>
                <td data-column="Title"><?php echo $admin_fetch['title']; ?></td>
                <td data-column="Description"><?php echo $admin_fetch['description']; ?></td>
                <td data-column="Requirement"><?php echo $admin_fetch['requirment']; ?></td>
                <td data-column="Education"><?php echo $admin_fetch['education']; ?></td>
                <td data-column="Experience"><?php echo $admin_fetch['experiance']; ?></td>
                <td data-column="Date"><?php echo $admin_fetch['validdate']; ?></td>
                <td data-column="Status"><?php echo $admin_fetch['status']; ?></td>
                <td data-column="Edit"><a data-toggle="modal" data-target="#editJob" class="edit-job"
                        data-id="<?php echo base64_encode($admin_fetch['id']); ?>">Edit</a></td>
                <td data-column="Delete"><a
                        href="./admin_dashboard.php?id=<?php echo base64_encode($admin_fetch['id']); ?>">Delete</a></td>
                <td data-column="Select"><a data-toggle="modal" data-target="#shortlist" class="short-list"
                        data-jobslid="<?php echo base64_encode($admin_fetch['id']); ?>">Short List</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php }
    } else {
        header('Location: ./index.php');
    } ?>

    <!-- Modal Form for Edit admin Profile  -->
    <div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
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
                                <input type="text" name="name" placeholder="Enter Name"
                                    value="<?php echo $getfetch['name'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="username"><b>Username</b></label>
                                <input type="text" name="username" placeholder="Enter Username"
                                    value="<?php echo $getfetch['username'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email"><b>Email</b></label>
                                <input type="text" name="email" placeholder="Enter Email"
                                    value="<?php echo $getfetch['email'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="phone"><b>Phone Number</b></label>
                                <input type="text" name="phone" placeholder="Enter Phone Number"
                                    value="<?php echo $getfetch['phone'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="psw"><b>Password</b></label>
                                <input type="password" name="psw" placeholder="Enter Password"
                                    value="<?php echo $getfetch['password'] ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="profile-upload"><b>User Profile</b></label>
                                <input type="file" name="profileupload">
                            </div>

                            <button type="submit" class="btn" name="adminupdate">
                                <i class="fas fa-sign-in-alt"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    <!-- Modal Form for Edit admin Profile  -->

    <!-- Modal Form  for create job button-->
    <div class="modal fade" id="createJob" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #3c7fd6;">Create Job</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="container">
                            <label><b>Job Title</b></label>
                            <input type="text" name="title" placeholder="Enter Job Title" required>

                            <label><b>Job Description</b></label>
                            <input type="text" name="description" placeholder="Enter Job Description" required>

                            <label><b>Job Requirement</b></label>
                            <input type="text" name="requirment" placeholder="Enter Job Requirement" required>

                            <label><b>Education</b></label>
                            <input type="text" name="education" placeholder="Enter Education" required>

                            <label><b>Experience</b></label>
                            <input type="text" name="experiance" placeholder="Enter Experience" required>

                            <label><b>Valid Date</b></label>
                            <input type="date" name="date" required>

                            <label for="status"><b>Status</b></label>
                            <select id="status" name="status" required>
                                <option value="open">Open</option>
                                <option value="close">Close</option>
                            </select>

                            <button type="submit" class="btn" name="create">Create</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    <!-- Model Form for create job button -->

    <!-- Modal Form for Edit Job  -->
    <div class="modal fade" id="editJob" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel" style="color: #3c7fd6;">Edit Job</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="container">

                            <input type="hidden" id="jobbid" name="jobbid" />
                            <label for="title"><b>Job Title</b></label>
                            <input type="text" name="title" placeholder="Enter Job Title" required>

                            <label for="description"><b>Job Description</b></label>
                            <input type="text" name="description" placeholder="Enter Job Description" required>

                            <label for="requirments"><b>Job Requirments</b></label>
                            <input type="text" name="requirment" placeholder="Enter Job Requirment" required>

                            <label for="education"><b>Education</b></label>
                            <input type="text" name="education" placeholder="Enter Education" required>

                            <label for="experiance"><b>Experiance</b></label>
                            <input type="text" name="experiance" placeholder="Enter Experiance" required>

                            <label for="date"><b>Valid Date</b></label>
                            <input type="date" name="date" placeholder="Enter Valid Date" required>

                            <label for="status">Choose a status:</label>
                            <select id="edit_status" name="status">
                            </select>

                            <button type="submit" class="btn" name="save">
                                <i class="fas fa-sign-in-alt"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    <!-- Modal Form for Edit Job  -->

    <!--  Modal Form for shortlist -->
    <div class="modal fade" id="shortlist" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Short List Candidates</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                            </tr>
                        </thead>
                        <tbody id="job_data">

                        </tbody>
                    </table>

                    <table>
                        <thead>
                            <tr>
                                <th>Candidate Name</th>
                                <th>Job Title</th>
                                <th>Experiance</th>
                                <th>Education</th>
                                <th>Address</th>
                                <th>Current_job</th>
                                <th>Current_salary</th>
                                <th>Resume</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="candidate_data">


                        </tbody>
                    </table>

                    <!-- Toast Container -->
                    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- Modal Form for shortlist  -->



    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/popper.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

    <script>
    // edit job code using ajax ==================
    $(document).ready(function() {
        $('.edit-job').on('click', function() {
            var jobId = $(this).data('id');
            $.ajax({
                url: 'server.php',
                type: 'POST',
                data: {
                    e_job_id: jobId
                },
                success: function(response) {
                    var jobData = JSON.parse(response);
                    $('input[name="title"]').val(jobData.title);
                    $('input[name="description"]').val(jobData.description);
                    $('input[name="requirment"]').val(jobData.requirment);
                    $('input[name="education"]').val(jobData.education);
                    $('input[name="experiance"]').val(jobData.experiance);
                    $('input[name="date"]').val(jobData.validdate);
                    $status = '<option value="open" ' + (jobData.status === ' open' ?
                            'selected' : '') + '>Open</option><option value="close" ' + (
                            jobData.status === 'close' ? 'selected' : '') +
                        '>Close</option>';
                    $('#edit_status').html($status);
                    $('#jobbid').val(jobData.id);
                }
            });
        });
    });
    // edit job code using ajax ==================

    // Shortlist candidates using ajax ==================
    $(document).ready(function() {
        $('.short-list').on('click', function() {
            var sl_id = $(this).data('jobslid');
            $.ajax({
                url: 'server.php',
                type: 'POST',
                data: {
                    shortlid: sl_id
                },
                success: function(response) {
                    var res = JSON.parse(response)

                    $('#job_data').empty(); // Clear previous data
                    $('#job_data').append(`
                            <tr>
                                <td data-column="Title">${res.job.title}</td>
                                <td data-column="Description">${res.job.description}</td>
                                <td data-column="Requirement">${res.job.requirment}</td>
                                <td data-column="Education">${res.job.education}</td>
                                <td data-column="Experience">${res.job.experiance}</td>
                                <td data-column="Date">${res.job.validdate}</td>
                                <td data-column="Status">${res.job.status}</td>
                            </tr>
                        `);
                        data_count = res.applied_count;
                        
                        
                        $('#candidate_data').empty();
                    for (let index = 0; index < data_count; index++) {
                        $('#candidate_data').append(`
                            <tr>
                                <td>${res.applied[index].name}</td>
                                <td>${res.applied[index].job_title}</td>
                                <td>${res.applied[index].experiance}</td>
                                <td>${res.applied[index].education}</td>
                                <td>${res.applied[index].address}</td>
                                <td>${res.applied[index].current_job}</td>
                                <td>${res.applied[index].current_salary}</td>
                                <td><a href="${res.applied[index].resume }" target="_blank">View</a></td>
                                <td>
                                    <select onChange="update_status(this.value , ${res.job.id},${res.applied[index].user_id});" name="status" class="job-status" data-user-id="${res.applied[index].user_id}" data-job-id="${res.job.id}">
                                        <option value="pending" ${res.applied[index].job_status === 'pending' ? 'selected' : ''}>pending</option>
                                        <option value="select" ${res.applied[index].job_status === 'select' ? 'selected' : ''}>select</option>
                                        <option value="notselect" ${res.applied[index].job_status === 'notselect' ? 'selected' : ''}>not select</option>
                                    </select>
                                </td>
                            </tr>
                        `);
                    }
                }
            });
        });

    });
    // Shortlist candidates using ajax ==================

    // display or select candidate status using ajax ==================
    function update_status(status , job_id, user_id) {
            $.ajax({
                url: 'server.php',
                type: 'POST',
                data: {
                    ajax: '1',
                    status: status,
                    user_id: user_id,
                    job_id: job_id
                },
                success: function(response) {

                    if (response.trim() === 'success') {
                        showToast('Candidate selection saved successfully!');
                    } else {
                        showToast('Failed to update: ' + response, 'danger');
                    }
                },
                error: function() {
                    alert('AJAX error occurred.');
                }
            });
        }

    // toast msg using bootstrap =============================

    function showToast(message, type = 'success') {
        const toastId = 'toast-' + Date.now();

        const toastHtml = `
    <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>`;

        $('#toast-container').append(toastHtml);

        const toastEl = document.getElementById(toastId);
        const bsToast = new bootstrap.Toast(toastEl, {
            delay: 3000
        });
        bsToast.show();

        // Optional: auto-remove toast after hidden
        toastEl.addEventListener('hidden.bs.toast', () => {
            toastEl.remove();
        });
    }
    // display or select candidate status using ajax ==================
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>