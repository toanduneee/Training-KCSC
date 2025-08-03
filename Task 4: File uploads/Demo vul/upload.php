<?php
if (isset($_FILES['file'])) {
    $upload_dir = 'uploads/';
    $filename = basename($_FILES['file']['name']);
    $target = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        echo "Upload thành công: <a href='index.php?page=" . $filename . "'>Xem file</a>";
    } else {
        echo "Upload thất bại.";
    }
}
?>
