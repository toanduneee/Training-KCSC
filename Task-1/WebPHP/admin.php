<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("location: login.php");
    }
?>

<h1>Đăng nhập thành công</h1>
<a href="logout.php">
    <button type="submit" name="dangxuat">Logout</button>
</a>