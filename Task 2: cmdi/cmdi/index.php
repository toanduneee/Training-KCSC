<?php

$info = '';
$tesst = '';

if(isset($_GET['info'])){
    $info = $_GET['info'];
    $blackList = [";", "&", "`", "$", "(", ")", " ", "cat", "ls", "sh", "curl"];
    $a = false;
    foreach ($blackList as $char) {
        if (strpos($info, $char) !== false) {
            $a = true;
            break;
        }
    }

    if ($a) {
        die("Hacker detected!");
    } else {
        $tesst = shell_exec("ping -c 3 $info");
    }
}


?>

<form action="index.php" method="get">
    <label for="ip">Ping:</label>
    <input type="text" name="info">
    <button type="submit">Submit</button> <br>
    <?php
    if(strlen($info) > 0){
        echo "Bạn vừa nhập: " . $info . "<br>";
        die($tesst);}
    else {echo "Nhập dữ liệu vào ô trên";} 
    ?>
</form>