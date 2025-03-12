<?php

    session_start();
    if(isset($_SESSION['username'])){
        header('location: admin.php');
        exit();
    }
    include "connect.php";

    $thongbao = '';
    if(isset($_POST['dangky'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        if (empty($username) || empty($password) || empty($confirm)) {
            $thongbao = "Vui lòng điền đầy đủ thông tin";
        }        

        $sql_name = "SELECT username FROM acc WHERE username = '$username'";

        if ($confirm != $password){
            $thongbao = 'Mat khau khong khop';
        } else {
            if($username == $sql_name) {
                $thongbao = 'Ten nguoi dung kha dung';
            } else {
                $sql = "INSERT INTO `acc` (`username`, `password`) VALUES ('$username', '$password')";
                $result = mysqli_query($connect, $sql);
                $thongbao = 'Dang ky thanh cong';
            }
        }
    }

    if(isset($_POST['return'])){
        header("location: login.php");
        exit();
    }

?>


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