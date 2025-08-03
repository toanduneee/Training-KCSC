<?php
    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin'){
        header("location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
</head>
<body>
    <h1>ADMIN DASHBOARD</h1>
    <h3>Xin chào <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
    <a href="logout.php">
        <button type="submit" name="dangxuat">Logout</button>
    </a>
</body>
</html>
