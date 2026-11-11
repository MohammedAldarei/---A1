import hashlib
import time
import os


def get_hash(file):
    h = hashlib.sha256()

    try:
        with open(file, "rb") as f:
            while True:
                data = f.read(4096)

                if not data:
                    break

                h.update(data)

        return h.hexdigest()

    except OSError:
        return None


def main():
    print("FILE INTEGRITY MONITORING")

    file = input("Enter file path: ").strip()

    if not os.path.isfile(file):
        print("File not found.")
        return

    old = get_hash(file)

    if old is None:
        print("Cannot read file.")
        return

    print("\nMonitoring started...")
    print(f"File: {file}")
    print("Checking every 3 seconds...")
    print("Press CTRL+C to stop.")

    try:
        while True:
            time.sleep(3)

            new = get_hash(file)

            if new is None:
                print("\n[CRITICAL ALERT] File is missing or cannot be read!")
                break

            if new != old:
                print("\n[CRITICAL ALERT] File has been modified!")
                print(f"Old Hash: {old}")
                print(f"New Hash: {new}")
                break

    except KeyboardInterrupt:
        print("\nMonitoring stopped.")


if __name__ == "__main__":
    main()
