# Đầu bài cho biết:
- Ứng dụng có lỗ hổng Blind SQL injection trong cookie theo dõi.
- Ứng dụng thực hiện truy vấn SQL chứa giá trị cookie.
- Kết quả truy vấn SQL không được trả về, và ứng dụng không phản hồi khác nhau dựa trên việc truy vấn trả về hàng hay gây ra lỗi.
- Truy vấn được thực hiện đồng bộ, có thể kích hoạt độ trễ thời gian có điều kiện để suy ra thông tin.

# Yêu cầu:
- Khai thác lỗ hổng SQL injection để gây ra độ trễ 10 giây.

![image](https://github.com/user-attachments/assets/d1c16ff2-538d-466e-b88f-d45d807ecd56)

# Solution
- Check sheet cheat, ta có:

![image](https://github.com/user-attachments/assets/605bf761-e603-4f33-9d14-57edbf931b64)

- Thử với MySQL: ` SLEEP(10)--`

![image](https://github.com/user-attachments/assets/0d871020-867d-4060-8d39-abc47349162e)

- Thử với PostgreSQL: `|| pg_sleep(10--)`

![image](https://github.com/user-attachments/assets/0c46bd2e-e4ec-41b8-bcb7-2ee86c176164)

- Với pgSQL thì nó trả về chậm hơn và đã hoàn thành lab.
