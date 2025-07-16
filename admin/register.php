<?php
include '../includes/db.php';
session_start();
if (isset($_POST['register'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'admin'; // Ensure admin role

    // Check if the email is already registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "<p style='color:red; text-align:center;'>Email already registered!</p>";
    } else {
        // Hash the password before inserting
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert admin into database
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        if ($stmt->execute([$email, $hashedPassword, $role])) {
            echo "<p style='color:green; text-align:center;'>Admin registered successfully!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Registration failed. Try again!</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }
        .register-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: blue;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Admin Registration</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Admin Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register Admin</button>
        </form>
    </div>
</body>
</html>
