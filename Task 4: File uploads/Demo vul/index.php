<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload</button>
</form>

<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    include("uploads/" . $page); 
} else {
    echo "Không có file nào được chọn.";
}
?>