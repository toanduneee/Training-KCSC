<?php
    session_start();
    include "connect.php";

    if(isset($_SESSION['username'])){
        header('location: admin.php');
    }

    $echoo = "";
    if(isset($_POST['dangnhap'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM acc WHERE username = '$username' AND password = '$password'";
        // Với `admin' OR '1'='1' -- ` thì sau dấu "--" phải có dấu cách mới hoạt động được, hoặc có thể dùng "#" để thay thế và ko cần có dấu cách nữa =((((

        $result = mysqli_query($connect, $sql);
        if (!$result) {
            die("Loi: " . mysqli_error($connect));
        }

        if(mysqli_num_rows($result) == 1){
            $_SESSION['username'] = $username;
            header('location:admin.php');
        } else {
            $echoo = "Tai khoan mat khau khong chinh xac";
        }
    }

    if(isset($_POST['reg'])){
        header("location: register.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
</head>
<body>
    <form action="login.php" method="POST">

        <label>Username</label>
        <input type="text" name="username"> <br>
        
        <label>Password</label>
        <input type="password" name="password">
        <button type="submit" name="dangnhap">Login</button>
        <button type="submit" name="reg">Register</button> <br>
        <?php echo $echoo ?>
    </form>
</body>
</html>
