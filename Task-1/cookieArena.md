# Mục Lục
- [Simple Blind SQL Injection](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/cookieArena.md#1-simple-blind-sql-injection)
- [Baby SQL Injection to RCE](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/cookieArena.md#2-baby-sql-injection-to-rce)
- [Blind Logger Middleware](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/cookieArena.md#3-blind-logger-middleware)
------------
# 1. Simple Blind SQL Injection

## Đầu bài cho biết
- Lấy mật khẩu của `admin`, sau đấy vào `/login` để lấy flag
- Trường mật khẩu gồm từ a-z, 0-9 và _
- cột `upw`

## Giải

![image](https://github.com/user-attachments/assets/e1d35a2f-51e1-4150-a4c8-f48f13411677)

- Đầu tiên với giao diện có 1 trường nhập UID và 1 truy vấn viết phía bên trên là `SELECT * FROM users WHERE uid='';`
- Thử nhập `admin` vào

![image](https://github.com/user-attachments/assets/5da2f3d6-39cd-47e1-b14d-d2edd088a1f8)

- Thử với uid khác `haha`

![image](https://github.com/user-attachments/assets/144f8979-b72c-4b32-b0f5-3b08d53942db)

- Có thể thấy chức năng này là kiểm tra uid nhập vào là có tồn tại trong database hay không.
- Thử với `'` và `''` thì `''` cho kết quả `not found` còn `'` báo lỗi như này:

![image](https://github.com/user-attachments/assets/a7dc3ce9-4a4d-48e5-8cb3-92347cdd0cfd)

- Chú ý vào `nrows = 1 if query_db("SELECT * FROM users WHERE uid ='%s'" % uid, one=True) else 0`, thì có thể hiểu là nó sẽ lấy thông tin được vào ô `UID` và chèn trực tiếp vào truy vấn SQL, nó bị lỗi vì thừa dấu `'` trong truy vấn làm hỏng cú pháp
- Từ việc chèn trực tiếp đó cùng với thông tin thông báo `exists`, `not found` có thể chắc chắn đấy là Boolean SQLi

- Đầu tiên, thử nhập vào với: `admin' AND 1=1 --`

![image](https://github.com/user-attachments/assets/2069767c-2547-4028-9f61-d2d3eca14e98)

- Thử số cột có trong bảng `users`: `admin' ORDER BY 1 --`, khi thử đến 4 thì có lỗi xảy ra

![image](https://github.com/user-attachments/assets/f57a18cb-b9bb-4229-996f-52872e72fb9e)

- Vậy bảng `users` này có 3 cột
- Biết: 
    + Bảng: users
    + Column: uid, upw, NULL
- Bây giờ cần đi tìm cột này để xác định xem có phải là cột password hay không?
- Với sqlite thì ta có thể thử dùng `name` với `PRAGME_TABLE_INFO(users)` để lấy tên các cột trong bảng (Cái này em hỏi GPT chứ không biết kiếm ở đâu 🤡)
- `admin' AND (SELECT name FROM PRAGMA_TABLE_INFO('users') LIMIT 1) = 'uid' --`

![image](https://github.com/user-attachments/assets/274bb17c-cf12-4e49-8221-f13ee8dbd711)

- Cột đầu tiên không phải là `uid`, ta đổi qua cột khác bằng cách thêm `OFFSET 1`

![image](https://github.com/user-attachments/assets/890ccf2b-d540-45a3-82e5-ec5228c27d48)

![image](https://github.com/user-attachments/assets/a91d28c0-54f6-42ad-bcf3-debca4ac1a7e)

- Vậy cột đầu là cột cần tìm
- Xem độ dài tên cột này rồi Brute Force ra thui:
- Thử độ dài: `admin' AND (SELECT LENGTH(name) FROM PRAGMA_TABLE_INFO('users') LIMIT 1 OFFSET 0) = 3 --`

![image](https://github.com/user-attachments/assets/92138a38-5eb8-4a35-bec9-4367a4c16750)

- Dùng SUBSTRING() để Brute Force ra: `admin' AND (SELECT SUBSTRING(name, 1, 1) FROM PRAGMA_TABLE_INFO('users') LIMIT 1 OFFSET 0) = 'a' --`

![image](https://github.com/user-attachments/assets/43e68417-cea6-4da2-b6a2-25f9b96c675c)

- Ta được tên cột là `idx`, trong 3 cột thì cái có khả năng là mật khẩu nhất là `upw`
- Ta thử lấy thông tin của `upw` với `uid = admin`: `admin' AND (SELECT SUBSTRING(upw, 1, 1) FROM users WHERE uid = 'admin') = 'a' --`
- Rồi lại đưa lên Intruder để Brute Force nó

![image](https://github.com/user-attachments/assets/4b65567c-b760-4e75-b342-5e17ab96d7eb)

- Chuyển qua `/login` và đăng nhập với username = `admin` và pass = `y0u_4r3_4dm1n`

![image](https://github.com/user-attachments/assets/541b7fba-906d-4bc4-b231-9bed994354f8)

# 2. Baby SQL Injection to RCE

# 3. Blind Logger Middleware
