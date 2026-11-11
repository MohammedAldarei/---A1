import secrets
import string
import hashlib


def generate(length):
    chars = string.ascii_letters + string.digits + string.punctuation

    if length < 4:
        return None

    while True:
        password = ''.join(secrets.choice(chars) for _ in range(length))

        if (any(c.islower() for c in password)
                and any(c.isupper() for c in password)
                and any(c.isdigit() for c in password)
                and any(c in string.punctuation for c in password)):
            return password


def get_hash(text):
    return hashlib.sha256(text.encode()).hexdigest()


def main():
    print("PASSWORD GENERATOR & SHA-256 HASHER")
    print("1. Generate Password")
    print("2. Calculate SHA-256")

    choice = input("Choose an option: ").strip()

    if choice == "1":
        try:
            length = int(input("Enter password length: "))

            if length < 4:
                print("Password length must be at least 4.")
                return

            password = generate(length)

            print("\nGenerated Password:")
            print(password)

            print("\nSHA-256 Hash:")
            print(get_hash(password))

        except ValueError:
            print("Invalid length.")

    elif choice == "2":
        text = input("Enter text: ")

        print("\nSHA-256 Hash:")
        print(get_hash(text))

    else:
        print("Invalid choice.")


if __name__ == "__main__":
    main()
