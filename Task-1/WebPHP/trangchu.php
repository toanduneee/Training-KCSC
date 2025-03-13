<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
</head>
<body>
    <h1>Trang chủ</h1>
    <h3>Xin chào <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
    <h3>Mã đáo thành công - Cung hỉ phát tài</h3>
    <h3>Tiền ra như nước sông đà, tiền vào nhỏ rọt như cà phê phin</h3>
    <a href="logout.php">
        <button type="submit" name="dangxuat">Logout</button>
    </a>
</body>
</html>
