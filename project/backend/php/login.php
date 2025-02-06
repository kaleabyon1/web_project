<?php
$error = ""; // Initialize an error variable
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connect.php';

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start(); 
            $_SESSION['username'] = $user['username']; // Start a session to keep the user logged in
                header("Location: /project/frontend/index.html"); 
                exit();

            echo "<br><a href='http://localhost/project/frontend/index.html'>Go to Home</a>";
            exit; // Prevent further processing once logged in
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found. Please check your email and try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project/frontend/css/login.css">
    <title>Login</title>
</head>
<body>
    <a href="http://localhost/project/frontend/index.html" class="back-home">Back to Home</a>
    <h1>Login</h1>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
