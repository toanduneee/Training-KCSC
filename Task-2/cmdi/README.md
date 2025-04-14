# Biết
- Filter: ```";", "&", "`", "$", "(", ")", " ", "cat", "ls", "sh", "curl"```

# Giải
- Web có chức năng ping đến 1 địa chỉ được nhập
![image](https://hackmd.io/_uploads/SkHMR8ZR1e.png)

- Thử ping đến `8.8.8.8`
![image](https://hackmd.io/_uploads/ByaQyD-Ayx.png)

- Ta thấy list filter thì có `|` là không bị chặn, ta có thể sử dụng nó để khai thác
- `8.8.8.8|ls`, nhưng vì `ls` nằm trong list filter, ta có thể dùng ngoặc đơn, ngoặc kép để bypass qua đó: `8.8.8.8|l''s`

![image](https://hackmd.io/_uploads/r1nf-P-Akg.png)
- Vì dấu `|` là lấy output của vế trái sang làm input của vế phải mà lệnh `ls` lại ko cần dùng đến input đó, nên input đó biến mất và chỉ xuất hiện output của riêng `ls`

- Ta cần mở file `test.txt`, có thể dùng `cat` tương tự với cách bypass của `ls` trên. Dấu ` ` có thể dùng `<` để bypass:

![image](https://hackmd.io/_uploads/HJbUzDb0kg.png)
