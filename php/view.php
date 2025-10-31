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

            $education_query = "SELECT * FROM education WHERE user_id = ?";
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
            color: #2575fc;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 2px solid #2575fc;
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

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 10px;
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
                font-size: 2rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            section {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>User Resume</h1>

        <?php if ($user): ?>
            <div class="profile-section">
                <img src="<?= isset($user['photo']) ? $user['photo'] : 'default.jpg' ?>" src="../images/image.jpg" alt="" />
                <div>
                    <p><strong>Name:</strong> <?= $user['first_name'] ?> <?= $user['last_name'] ?></p>
                    <p><strong>Email:</strong> <?= $user['email'] ?></p>
                    <p><strong>Phone:</strong> <?= isset($user['phone_number']) ? $user['phone_number'] : 'Not available' ?></p>
                </div>
            </div>
        <?php else: ?>
            <p class="no-data">User not found.</p>
        <?php endif; ?>

        <?php if ($experiences_result->num_rows > 0): ?>
            <section>
                <h2>Work Experience</h2>
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
            <p class="no-data">No work experience available.</p>
        <?php endif; ?>

        <?php if ($education_result->num_rows > 0): ?>
            <section>
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
            <p class="no-data">No education details available.</p>
        <?php endif; ?>

        <?php if ($skills_result->num_rows > 0): ?>
            <section>
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
            <p class="no-data">No skills available.</p>
        <?php endif; ?>
    </div>

</body>
</html>
