# Khai thác SQLi trong trang web
- **Mục tiêu: Đăng nhập vào user `admin`**
- Đầu tiên, thử đăng nhập tên `admin` với mật khẩu bất kỳ

![image](https://github.com/user-attachments/assets/53a67e53-59e9-4032-84b2-e53f7d9e3ea4)

- Từ đây, ta khai thác được là có bảng `acc`, cột `username` và cột `password` có trong truy vấn
- Thử với SQLi `' OR '1'='1' --`

username: `admin' OR '1'='1' --`

password: `aaaa`

![image](https://github.com/user-attachments/assets/6b591f87-8bd3-4642-a437-1c2d39c530d4)

- Có vẻ lỗi là do thừa 1 dấu ' ở trong phần password
- Để xử lí vấn đề này thì ta có thể thêm dấu cách vào cuối chuối username mình nhập, tức là thêm dấu cách sau `--` -> `-- `. Giải thích cho vấn đề này thì nó là 1 sự yêu cầu cơ bản của MariaDB (hay MySQL idk :>>>>) khi sau dấu `--` phải là 1 dấu cách, hơn nữa nếu nhìn cả câu truy vấn thì nó sẽ là `--'` thì MariaDB nó không hiểu đây là 1 comment mà là 1 phần của chuỗi, vậy nên cuối phần password nó tự thêm 1 dấu ' nữa để đóng
- Tức là nó coi đoạn `--' AND password = 'aaaa` là 1 chuỗi và bắt đầu 1 cái ' sau đấy thì phải có 1 ' nữa để kết thúc. Xảy ra vấn đề thừa dấu '.
- Cách 2: có thể thay `--` thành `#` thì nó sẽ hiểu phần sau là comment

![image](https://github.com/user-attachments/assets/e6df0a95-6684-4e40-8f02-bec21c19d881)

- Truy vấn: `SELECT * FROM acc WHERE username = 'admin' OR '1'='1' -- ' AND password = 'aaaa'`
- Nghĩa là điều kiện `username = 'admin' OR TRUE`. Thì tất nhiên là đúng, nhưng nó sẽ lấy tất cả các hàng có trong database thành 8 hàng, nên không đăng nhập được, vậy ta cần chỉ là 1 hàng duy nhất. Thêm điều kiện `LIMIT 1` vào là lấy được 1 hàng.

![image](https://github.com/user-attachments/assets/d88a8a57-1263-4609-8679-6007a57fc792)

- Đã đăng nhập được, những có vẻ username ko phải là `admin` mà nó là: `admin' OR '1'='1' LIMIT 1--`
- Thử xem trong BurpSuite

![image](https://github.com/user-attachments/assets/4debcf9d-18d2-4dfc-b553-f5f27a4c41b8)

- Xem thì thấy, khi đăng nhập được vào và có sự điều hướng đến `trangchu.php` thì trong reponse sẽ có 1 phần thông báo lại cái truy vấn SQL và số hàng.
- Vậy là có 2 điều kiện cần tuân thủ là: **username** là `admin`, và **số hàng** là `1`
- Để xử lý vấn đề này ta có 2 cách:
  + 1 là tiếp tục sửa truy vấn
  + 2 là dò tìm mật khẩu của `admin`
 
------------------------------------------------------------------------

# Cách 1:
- Vừa rồi ta vừa thử với payload là:
  + username: `admin' OR '1'='1' LIMIT 1 -- `
  + password: `bất kỳ`
- Kết quả: Đăng nhập với username `admin' OR '1' = '1' LIMIT 1 --`
- Vậy để đăng nhập được username là `admin` thì chắc chắn chỉ được nhập `admin` 

=> Phải sửa truy vấn từ mật khẩu
- `SELECT * FROM acc WHERE username = 'admin' AND password = 'aaaa'`
- Ở đây ta hay biến để điều kiện thành `username = 'admin' AND TRUE` vậy thì như username nhập lúc nãy, ta dùng `' OR '1'='1' LIMIT 1 -- `
=> `SELECT * FROM acc WHERE username = 'admin' AND password = 'aaaa' OR '1'='1' LIMIT 1 -- '`

![image](https://github.com/user-attachments/assets/8b5d0420-f375-46cc-acc7-037d1190fdcb)

- AND Yesssss, ta thành công vào đc user `admin` và được chuyển hướng đến `admin.php` 

---------------------------------------------------------------------------
# Cách 2: 





