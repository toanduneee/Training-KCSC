# 1. SQL Injection
- Theo em hiểu thì nó là 1 dạng lỗ hổng mà người dùng có thể đưa vào một đoạn SQL độc từ 1 vùng nhập nào đấy để sửa đổi truy vấn SQL của trang web gửi đi, làm cho truy vấn đấy sai.

### 1.1. Blind SQL Injection
- Với loại này, khi người tấn công chèn SQL vào đâu đó để lấy dữ liệu thì sẽ không có thông tin nào về kết quả của truy vấn SQL được trả về trong respond, cũng như là không thông báo lỗi, lỗi cú pháp SQL hay lỗi không tìm thấy bảng gì hết.
- Trong này thì gồm có dạng Boolean với dạng Time:
  + Boolean: Sử dụng SQL để đặt câu hỏi ở dạng "Yes/No", xong trang web sẽ có sự thay đổi nhất định nào đó, mình dựa vào đấy thì có thể từ từ phán đoán ra được thông tin mình cần. Ví dụ như đợt tuyển thành viên vừa rồi thì có 1 bài ở dạng này, khi đúng tên trong database (hay flag) thì nó sẽ hiện thông báo là `Found` còn nếu sai thì là `Not Found`, rồi từ đấy dò từ từ ra các ký tự của flag.
  + Time: Với dạng này thì là kiểu các phần thông báo lỗi hay thay đổi giao diện đều được xử lý oke rồi, nên khi mình thử với 1 truy vấn nào đấy thì có thể hoàn toàn không thấy sự thay đổi gì. Sử dụng các hàm SLEEP(), pg_sleep(),... để làm chậm đi mấy cái request mà mình nghi ngờ, kiểu mình sẽ gửi đi 1 truy vấn SQL hỏi với cái điều kiện A thì có đúng không, nếu đúng thì gửi trả lại response sau 5 giây, không đúng thì phản hồi ngay lập tức. Từ đó ta có thể phán đoán được sự đúng sai như dạng `Boolean`.

### 1.2. UNION

### 1.3. Error


# 2. Phân biệt Client Side với Server Side





