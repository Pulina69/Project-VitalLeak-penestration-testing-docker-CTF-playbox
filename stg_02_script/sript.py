import subprocess
import sys

img_file ="profile.jpg"
word_list= "rockyou.txt"

try :
    with open (word_list,"r", encoding="utf-8", errors="ignore") as file:
        passwords=file.readlines()

        for password in passwords:
            password = password.strip()
            print(f"Trying password: {password}")

            command = f"steghide extract -sf {img_file} -p {password} -f"
            result = subprocess.run(command, shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)

            if result.returncode == 0:
                print(f"\nSUCCESS! The passphrase is: {password}")
                print("[+] The hidden credentials have been extracted to the current directory.")
                sys.exit(0)

        print("\n[-] Password not found in wordlist.")
except FileNotFoundError:
    print("[-] Error: Image or wordlist file not found.")