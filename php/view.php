<?php
// Inclure la connexion à la base de données
include('connection.php');

// Vérifier si un user_id est passé via l'URL
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id'];

    // Récupérer les données de l'utilisateur
    $query = "SELECT * FROM users WHERE id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        // Vérifier si l'utilisateur existe
        if ($user) {
            // Récupérer les expériences
            $experiences_query = "SELECT * FROM experiences WHERE user_id = ?";
            $stmt = $mysqli->prepare($experiences_query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $experiences_result = $stmt->get_result();

            // Récupérer l'éducation
            $education_query = "SELECT * FROM education WHERE user_id = ?";
            $stmt = $mysqli->prepare($education_query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $education_result = $stmt->get_result();

            // Récupérer les compétences
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
    
</head>
<body>
    <h1>User Resume</h1>

    <?php if ($user): ?>
        <h2>Profile</h2>
        <p>Name: <?= $user['first_name'] ?> <?= $user['last_name'] ?></p>
        <p>Email: <?= $user['email'] ?></p>
        <p>Phone: <?= isset($user['phone_number']) ? $user['phone_number'] : 'Not available' ?></p>
        <p>Photo: <img src="<?= isset($user['photo']) ? $user['photo'] : 'default.jpg' ?>" src="../images/image.jpg" alt="" /></p>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>

    <?php if ($experiences_result->num_rows > 0): ?>
        <h2>Work Experience</h2>
        <?php while ($experience = $experiences_result->fetch_assoc()): ?>
            <p>Title: <?= $experience['job_title'] ?></p>
            <p>Company: <?= $experience['company_name'] ?></p>
            <p>Start Date: <?= $experience['start_date'] ?></p>
            <p>End Date: <?= $experience['end_date'] ?></p>
            <p>Description: <?= $experience['description'] ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No work experience available.</p>
    <?php endif; ?>

    <?php if ($education_result->num_rows > 0): ?>
        <h2>Education</h2>
        <?php while ($education = $education_result->fetch_assoc()): ?>
            <p>Degree: <?= $education['degree'] ?></p>
            <p>Institution: <?= $education['school_name'] ?></p>
            <p>Start Date: <?= $education['start_date'] ?></p>
            <p>End Date: <?= $education['end_date'] ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No education details available.</p>
    <?php endif; ?>

    <?php if ($skills_result->num_rows > 0): ?>
        <h2>Skills</h2>
        <?php while ($skill = $skills_result->fetch_assoc()): ?>
            <p>Skill: <?= $skill['skill_name'] ?> - Level: <?= $skill['level'] ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No skills available.</p>
    <?php endif; ?>
</body>
</html>
