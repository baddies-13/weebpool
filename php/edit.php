<?php
include('connection.php');

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if (!$user_id) {
    echo "User ID is required!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $photo = $_POST['photo'];

        $stmt = $mysqli->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone_number=?, photo=? WHERE id=?");
        $stmt->bind_param('sssssi', $first_name, $last_name, $email, $phone_number, $photo, $user_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['add_experience'])) {
        $job_title = $_POST['job_title'];
        $company_name = $_POST['company_name'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $description = $_POST['description'];

        $stmt = $mysqli->prepare("INSERT INTO experiences (user_id, job_title, company_name, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssss', $user_id, $job_title, $company_name, $start_date, $end_date, $description);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_experience'])) {
        $exp_id = (int)$_POST['exp_id'];
        $stmt = $mysqli->prepare("DELETE FROM experiences WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $exp_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['add_education'])) {
        $degree = $_POST['degree'];
        $school_name = $_POST['school_name'];
        $start_date = $_POST['edu_start_date'];
        $end_date = $_POST['edu_end_date'];

        $stmt = $mysqli->prepare("INSERT INTO educations (user_id, degree, school_name, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('issss', $user_id, $degree, $school_name, $start_date, $end_date);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_education'])) {
        $edu_id = (int)$_POST['edu_id'];
        $stmt = $mysqli->prepare("DELETE FROM educations WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $edu_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['add_skill'])) {
        $skill_name = $_POST['skill_name'];
        $level = $_POST['level'];

        $stmt = $mysqli->prepare("INSERT INTO skills (user_id, skill_name, level) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $user_id, $skill_name, $level);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['delete_skill'])) {
        $skill_id = (int)$_POST['skill_id'];
        $stmt = $mysqli->prepare("DELETE FROM skills WHERE id=? AND user_id=?");
        $stmt->bind_param('ii', $skill_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

$user = $mysqli->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
$experiences = $mysqli->query("SELECT * FROM experiences WHERE user_id = $user_id");
$educations = $mysqli->query("SELECT * FROM educations WHERE user_id = $user_id");
$skills = $mysqli->query("SELECT * FROM skills WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 40px;
        }

        .section {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .section h2 {
            font-size: 2rem;
            color: #ff4da6;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 2px solid #ff4da6;
            padding-bottom: 5px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input, textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            padding: 10px 20px;
            background-color: #ff4da6;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e63946;
        }

        .item {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item form {
            margin: 0;
            display: inline;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #f4f6f9;
            padding: 10px;
            text-align: center;
            z-index: 1000;
        }

        .navbar a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .navbar a:hover {
            color: #ff4da6;
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s forwards;
        }

        .fade-in:nth-child(1) { animation-delay: 0.2s; }
        .fade-in:nth-child(2) { animation-delay: 0.4s; }
        .fade-in:nth-child(3) { animation-delay: 0.6s; }
        .fade-in:nth-child(4) { animation-delay: 0.8s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 2rem;
            }

            .section {
                padding: 15px;
            }

            .item {
                flex-direction: column;
                align-items: flex-start;
            }

            .item form {
                margin-top: 10px;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 10px;
            }

            .navbar a {
                display: block;
                margin: 5px 0;
            }

            h1 {
                font-size: 1.8rem;
            }

            .section h2 {
                font-size: 1.5rem;
            }

            input, textarea {
                font-size: 0.9rem;
            }

            button {
                font-size: 0.9rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="view.php?user_id=<?= $user_id ?>">View Resume</a>
        <a href="#profile">Profile</a>
        <a href="#experience">Experience</a>
        <a href="#education">Education</a>
        <a href="#skills">Skills</a>
    </nav>

    <div class="container">
        <h1>Edit Resume</h1>

        <div id="profile" class="section fade-in">
            <h2>Profile</h2>
            <form method="post">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= $user['first_name'] ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?= $user['last_name'] ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?= $user['phone_number'] ?>">

                <label for="photo">Photo URL:</label>
                <input type="text" id="photo" name="photo" value="<?= $user['photo'] ?>">

                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </div>

        <div id="experience" class="section fade-in">
            <h2>Experience</h2>
            <?php while ($exp = $experiences->fetch_assoc()): ?>
                <div class="item">
                    <div>
                        <strong><?= $exp['job_title'] ?> at <?= $exp['company_name'] ?></strong><br>
                        <?= $exp['start_date'] ?> - <?= $exp['end_date'] ?><br>
                        <?= $exp['description'] ?>
                    </div>
                    <form method="post">
                        <input type="hidden" name="exp_id" value="<?= $exp['id'] ?>">
                        <button type="submit" name="delete_experience" class="delete-btn">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>

            <h3>Add New Experience</h3>
            <form method="post">
                <label for="job_title">Job Title:</label>
                <input type="text" id="job_title" name="job_title" required>

                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" required>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date">

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"></textarea>

                <button type="submit" name="add_experience">Add Experience</button>
            </form>
        </div>

        <div id="education" class="section fade-in">
            <h2>Education</h2>
            <?php while ($edu = $educations->fetch_assoc()): ?>
                <div class="item">
                    <div>
                        <strong><?= $edu['degree'] ?> at <?= $edu['school_name'] ?></strong><br>
                        <?= $edu['start_date'] ?> - <?= $edu['end_date'] ?>
                    </div>
                    <form method="post">
                        <input type="hidden" name="edu_id" value="<?= $edu['id'] ?>">
                        <button type="submit" name="delete_education" class="delete-btn">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>

            <h3>Add New Education</h3>
            <form method="post">
                <label for="degree">Degree:</label>
                <input type="text" id="degree" name="degree" required>

                <label for="school_name">School Name:</label>
                <input type="text" id="school_name" name="school_name" required>

                <label for="edu_start_date">Start Date:</label>
                <input type="date" id="edu_start_date" name="edu_start_date" required>

                <label for="edu_end_date">End Date:</label>
                <input type="date" id="edu_end_date" name="edu_end_date">

                <button type="submit" name="add_education">Add Education</button>
            </form>
        </div>

        <div id="skills" class="section fade-in">
            <h2>Skills</h2>
            <?php while ($skill = $skills->fetch_assoc()): ?>
                <div class="item">
                    <div>
                        <strong><?= $skill['skill_name'] ?> - <?= $skill['level'] ?></strong>
                    </div>
                    <form method="post">
                        <input type="hidden" name="skill_id" value="<?= $skill['id'] ?>">
                        <button type="submit" name="delete_skill" class="delete-btn">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>

            <h3>Add New Skill</h3>
            <form method="post">
                <label for="skill_name">Skill Name:</label>
                <input type="text" id="skill_name" name="skill_name" required>

                <label for="level">Level:</label>
                <input type="text" id="level" name="level" required>

                <button type="submit" name="add_skill">Add Skill</button>
            </form>
        </div>
    </div>

</body>
</html>
