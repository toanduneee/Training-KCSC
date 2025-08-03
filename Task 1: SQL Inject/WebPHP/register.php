<?php

    session_start();
    if(isset($_SESSION['username'])){
        if($_SESSION['username'] == 'admin'){
            header('location: admin.php');
            exit();
        } else {
        header('location: trangchu.php');
        exit();
        }
    }
    include "connect.php";

    $thongbao = '';
    if(isset($_POST['dangky'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        if (empty($username) || empty($password) || empty($confirm)) {
            $thongbao = "Vui lòng điền đầy đủ thông tin";
        } elseif ($confirm != $password){
            $thongbao = 'Mat khau khong khop';
        } else {
            $sql_name = "SELECT username FROM acc WHERE username = '$username'";
            $resultname = mysqli_query($connect, $sql_name);
            if (mysqli_num_rows($resultname) > 0){
                $thongbao = "Username da duoc su dung";
            } else {
                $sql = "INSERT INTO `acc` (`username`, `password`) VALUES ('$username', '$password')";
                $result = mysqli_query($connect, $sql);
                $thongbao = "<script>alert('Đăng ký thành công!');</script>";
            }
        }
    }

    if(isset($_POST['return'])){
        header("location: login.php");
        exit();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
</head>
<body>
    <form action="register.php" method="POST">
        <label>username</label>
        <input type="text" name="username"> <br>

        <label>password</label>
        <input type="password" name="password"> <br>
        
        <label>confirm password</label>
        <input type="password" name="confirm"> <br>

        <button type="submit" name="dangky">Đăng ký</button>
        <button type="submit" name="return">Trở lại đăng nhập</button> <br>
        <?php echo $thongbao ?>
    </form>
</body>
</html>
