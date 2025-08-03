# Yêu cầu:
## - Login với tài khoản administrator

![image](https://github.com/user-attachments/assets/c7bbb796-ee19-4af2-b9a1-4c523436e004)

# Giải:
- Đầu tiên, vào với trang đăng nhập, em thử đăng nhập vào với 1 tài khoản bất kỳ

![image](https://github.com/user-attachments/assets/17b20718-5624-4705-89c5-c4903db5ef1e)

- Thử với `'`

![image](https://github.com/user-attachments/assets/3c13503f-9227-490d-b92d-3bb639e74ea3)
![image](https://github.com/user-attachments/assets/1d0ee259-729d-406a-9967-b55e30457c78)

- Với `'` ta có thể biết được là ở đây có lỗ hổng với SQL injection
- Vậy đặt với 1 câu truy vấn SQL thì có khả năng nó sẽ là:

`SELECT ... FROM ... WHERE username = '' AND password = ''`

- Để có thể vào được tài khoản `administrator` mà không có pass, thì ta cần biến phần password của câu truy vấn SQL kia thành 1 comment
- Dùng `'--` trong phần username:

`SELECT ... FROM ... WHERE username = 'administrator'--' AND password = ''`

![image](https://github.com/user-attachments/assets/2566871c-ccdd-446d-8465-52927aae3ee3)


- Khi đó, `username` sẽ là `administrator` và biến biến phần `' AND password = ''` thành comment
- Đăng nhập thành công

![image](https://github.com/user-attachments/assets/6ca45c25-5918-4e4b-b6f5-47013cf9b632)
