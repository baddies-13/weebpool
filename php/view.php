<?php
include('connection.php');

if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];

    $query = "SELECT * FROM users WHERE id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $experiences_query = "SELECT * FROM experiences WHERE user_id = ?";
            $stmt = $mysqli->prepare($experiences_query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $experiences_result = $stmt->get_result();

            $education_query = "SELECT * FROM educations WHERE user_id = ?";
            $stmt = $mysqli->prepare($education_query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $education_result = $stmt->get_result();

            $skills_query = "SELECT * FROM skills WHERE user_id = ?";
            $stmt = $mysqli->prepare($skills_query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $skills_result = $stmt->get_result();
        } else {
            echo "User not found!";
        }
    } else {
        echo "Error preparing query!";
    }
} else {
    echo "User ID is required!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Resume</title>
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
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 40px;
        }

        h2 {
            font-size: 2rem;
            color: #ff4da6;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 2px solid #ff4da6;
            padding-bottom: 5px;
        }

        .profile-section {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .profile-section p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        

        .profile-section img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-right: 20px;
            margin-top: 10px;
        }

        section {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        section p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .no-data {
            font-size: 1.2rem;
            color: #888;
            text-align: center;
            font-style: italic;
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

        .home-section {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .home-section h2 {
            color: #ff4da6;
            margin-bottom: 15px;
        }

        .edit-link {
            background-color: #ff4da6;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
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
        .fade-in:nth-child(5) { animation-delay: 1.0s; }
        .fade-in:nth-child(6) { animation-delay: 1.2s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1024px) {
            .navbar {
                position: static;
                text-align: center;
                padding: 10px 0;
            }

            .navbar a {
                display: inline-block;
                margin: 5px 10px;
            }

            .container {
                padding: 15px;
            }

            .profile-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-section img {
                margin-right: 0;
                margin-bottom: 15px;
            }

            h1 {
                font-size: 2.5rem;
            }

            h2 {
                font-size: 2rem;
            }

            section {
                padding: 20px;
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
                font-size: 1.4rem;
                margin: 5px;
            }

            .profile-section img {
                width: 120px;
                height: 120px;
            }

            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            section {
                padding: 15px;
            }

            ul li {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="#profile">Profile</a>
        <a href="#experience">Experience</a>
        <a href="#education">Education</a>
        <a href="#skills">Skills</a>
        <a href="edit.php?user_id=<?= $user_id ?>" class="edit-link">Edit Resume</a>
    </nav>

    <div class="container">
        <h1>User Resume</h1>

        <?php if ($user): ?>
            <div id="profile" class="profile-section fade-in">
                <img src="<?= isset($user['photo']) && $user['photo'] ? $user['photo'] : '../images/image.jpg' ?>" alt="Profile Photo" />
                <div>
                    <p><strong>Name:</strong> <?= $user['first_name'] ?> <?= $user['last_name'] ?></p>
                    <p><strong>Email:</strong> <a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a></p>
                    <p><strong>Phone:</strong> <?= isset($user['phone_number']) ? $user['phone_number'] : 'Not available' ?></p>
                </div>
            </div>
        <?php else: ?>
            <p class="no-data">User not found.</p>
        <?php endif; ?>

        <?php if ($experiences_result->num_rows > 0): ?>
            <section id="experience" class="fade-in">
                <h2>Experience</h2>
                <ul>
                    <?php while ($experience = $experiences_result->fetch_assoc()): ?>
                        <li>
                            <strong>Title:</strong> <?= $experience['job_title'] ?><br>
                            <strong>Company:</strong> <?= $experience['company_name'] ?><br>
                            <strong>Start Date:</strong> <?= $experience['start_date'] ?><br>
                            <strong>End Date:</strong> <?= $experience['end_date'] ?><br>
                            <strong>Description:</strong> <?= $experience['description'] ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        <?php else: ?>
            <section id="experience" class="fade-in">
                <h2>Experience</h2>
                <p class="no-data">No work experience available.</p>
            </section>
        <?php endif; ?>

        <?php if ($education_result->num_rows > 0): ?>
            <section id="education" class="fade-in">
                <h2>Education</h2>
                <ul>
                    <?php while ($education = $education_result->fetch_assoc()): ?>
                        <li>
                            <strong>Degree:</strong> <?= $education['degree'] ?><br>
                            <strong>Institution:</strong> <?= $education['school_name'] ?><br>
                            <strong>Start Date:</strong> <?= $education['start_date'] ?><br>
                            <strong>End Date:</strong> <?= $education['end_date'] ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        <?php else: ?>
            <section id="education" class="fade-in">
                <h2>Education</h2>
                <p class="no-data">No education details available.</p>
            </section>
        <?php endif; ?>

        <?php if ($skills_result->num_rows > 0): ?>
            <section id="skills" class="fade-in">
                <h2>Skills</h2>
                <ul>
                    <?php while ($skill = $skills_result->fetch_assoc()): ?>
                        <li>
                            <strong>Skill:</strong> <?= $skill['skill_name'] ?> - <strong>Level:</strong> <?= $skill['level'] ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        <?php else: ?>
            <section id="skills" class="fade-in">
                <h2>Skills</h2>
                <p class="no-data">No skills available.</p>
            </section>
        <?php endif; ?>
    </div>

</body>
</html>
