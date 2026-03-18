<?php
session_start();
include "../config/db_connect.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // check if email already exists
    $check = $conn->prepare("SELECT user_id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        $error = "Email already registered";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $insert->bind_param("sss", $name, $email, $hashed_password);

        if($insert->execute()){
            header("Location: ../pages/login.php");
            exit();
        } else {
            $error = "Something went wrong, try again";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusVibe Sign Up</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">

    <div class="left-section">
        <img src="../assets/images/logo.png" class="logo" alt="CampusVibe Logo">
    </div>

    <div class="signup-card">
        <h2>Create your account to get started!</h2>

        <?php if(isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form class="signup-form" method="POST" action="">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">SIGN UP</button>
        </form>
    </div>

</div>
</body>
</html>