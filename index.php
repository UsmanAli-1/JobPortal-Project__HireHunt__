<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HireHunt Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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

    @media (max-width: 600px) {
        .navbar {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .navbar .actions {
            flex-direction: column;
            align-self: stretch;
        }

        .navbar .actions button {
            width: 100%;
            justify-content: center;
        }
    }

    /* Wrapper */
    .main {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 30px 15px;
    }

    /* Home container */
    .home-container {
        background-color: white;
        max-width: 900px;
        width: 100%;
        padding: 40px 30px;
        text-align: center;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(51, 162, 182, 0.1);
        border: 1px solid #e2e8f0;
        animation: fadeIn 0.6s ease;
    }

    .home-container h1 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 28px;
    }

    .home-container p {
        margin-bottom: 35px;
        color: #444;
        font-size: 1rem;
    }

    /* Feature Cards */
    .features {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        justify-content: center;
    }

    .feature-box {
        background-color: #f8faff;
        padding: 25px;
        flex: 1 1 250px;
        border-radius: 12px;
        text-align: center;
        min-width: 250px;
        max-width: 280px;
        transition: all 0.3s ease;
        border: 1px solid #dbeafe;
        cursor: pointer;
    }

    .feature-box:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
        background-color: #eaf2fe;
    }

    .feature-box i {
        font-size: 36px;
        color: var(--primary-color);
        margin-bottom: 12px;
    }

    .feature-box h3 {
        color: var(--secondary-color);
        margin-bottom: 10px;
        font-size: 18px;
    }

    .feature-box p {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    @media (max-width: 600px) {
        .features {
            flex-direction: column;
            align-items: center;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="logo"><i class="fas fa-briefcase"></i> HireHunt</div>
        <div class="actions">
            <button onclick="location.href='login.php'"><i class="fas fa-sign-in-alt"></i> Login</button>
            <button onclick="location.href='register.php'"><i class="fas fa-user-plus"></i> Register</button>
        </div>
    </div>

    <div class="main">
        <div class="home-container">
            <h1>Welcome to HireHunt</h1>
            <p>Your gateway to find the best jobs or manage your job postings effortlessly.</p>

            <div class="features">
                <a href="./login.php">
                    <div class="feature-box">
                        <i class="fas fa-user-tie"></i>
                        <h3>Job Seekers</h3>
                        <p>Browse and apply for jobs, create your profile, drop and track applications.</p>
                    </div>
                </a>
                <a href="./login.php">
                    <div class="feature-box">
                        <i class="fas fa-building"></i>
                        <h3>Employers</h3>
                        <p>Post job listings, manage applicants, and connect with top talent.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

</body>

</html>