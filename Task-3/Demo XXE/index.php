<?php
$items_array = [];
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: text/html; charset=UTF-8');

    libxml_use_internal_errors(true);

    $xml_content = null;

    if (isset($_SERVER['CONTENT_TYPE']) && strpos(strtolower($_SERVER['CONTENT_TYPE']), 'application/xml') !== false) {
        $xml_content = file_get_contents('php://input');
        if (empty($xml_content)) {
            $error_message = "Lỗi: Đã nhận Content-Type XML nhưng không có dữ liệu.";
        }
    }
    elseif (isset($_POST['category'])) {
        $category = $_POST['category'];
        $filename = "";

        if ($category === 'dodung') {
            $filename = 'dodung.xml';
        } elseif ($category === 'doan') {
            $filename = 'doan.xml';
        } else {
            $error_message = "Lỗi: Loại sản phẩm không hợp lệ.";
        }

        if ($filename && file_exists($filename)) {
            $xml_content = file_get_contents($filename);
            if ($xml_content === false) {
                $error_message = "Lỗi: Không thể đọc file dữ liệu '$filename'.";
                $xml_content = null;
            }
        } elseif ($filename && !$error_message) {
            $error_message = "Lỗi: File dữ liệu '$filename' không tồn tại.";
        }
    } else {
        $error_message = "Lỗi: Thiếu dữ liệu POST hợp lệ.";
    }

    if ($xml_content !== null && $error_message === null) {
        $a = simplexml_load_string($xml_content, 'SimpleXMLElement', LIBXML_NOENT);

        if ($a === false) {
            $error_message = "Lỗi khi phân tích cú pháp XML.";
            libxml_clear_errors();
        } else {
            foreach ($a->item as $item) {
                $items_array[] = [
                    'name' => (string)$item->name,
                    'price' => (string)$item->price,
                    'description' => (string)$item->description,
                ];
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>XXE Demo</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        form { margin-bottom: 15px; }
        button { padding: 10px; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        pre { background: #f4f4f4; padding: 10px; border: 1px solid #ccc; overflow-x: auto; }
        i { color: #555; }
    </style>
</head>
<body>
    <h1>Xem danh sách sản phẩm</h1>

    <form action="index.php" method="POST">
        <input type="hidden" name="category" value="dodung">
        <button type="submit">Xem Đồ Dùng</button>
    </form>

    <form action="index.php" method="POST">
        <input type="hidden" name="category" value="doan">
        <button type="submit">Xem Đồ Ăn</button>
    </form>

    <hr>

    <?php if ($error_message): ?>
        <p style="color:red;"><strong><?= htmlspecialchars($error_message) ?></strong></p>
    <?php elseif (!empty($items_array)): ?>
        <h2>Kết quả</h2>
        <table>
            <thead>
                <tr><th>Tên sản phẩm</th><th>Giá</th><th>Mô tả</th></tr>
            </thead>
            <tbody>
                <?php foreach ($items_array as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['price']) ?></td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
