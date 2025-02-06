<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connect.php'; 

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'user')";

    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['username'] = $username;//wll login the user
        header("Location: /project/frontend/index.html");
            exit();
        echo "<br><a href='http://localhost/project/frontend/index.html'>Go to Home</a>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
    $conn->close();
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" href="/project/frontend/css/register.css">
    </head>
    <body>
        <h1>Register</h1>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            
            <button type="submit">Register</button>
        </form>
        <br>
        <a href="http://localhost/project/frontend/index.html">Back to Home</a>
    </body>
    </html>
    <?php
}
?>
