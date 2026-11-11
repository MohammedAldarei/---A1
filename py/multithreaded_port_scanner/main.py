import socket
import time
import ipaddress
from concurrent.futures import ThreadPoolExecutor, as_completed


def scan(ip, port):
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.settimeout(0.5)

    try:
        r = s.connect_ex((ip, port))

        if r == 0:
            return port

    except OSError:
        return None

    finally:
        s.close()

    return None


def main():
    ip = input("Enter IP: ").strip()

    try:
        ipaddress.IPv4Address(ip)
    except ValueError:
        print("Invalid IP address")
        return

    ports = range(1, 1025)
    opened = []

    start = time.time()

    print("\nScanning started...\n")

    with ThreadPoolExecutor(max_workers=50) as ex:
        tasks = [ex.submit(scan, ip, p) for p in ports]
 
        for task in as_completed(tasks):
            p = task.result()

            if p is not None:
                opened.append(p)
                print(f"Port {p} is OPEN")

    end = time.time()

    print("\nScan finished")
    print(f"Open ports: {len(opened)}")
    print(f"Time: {end - start:.2f} seconds")


if __name__ == "__main__":
    main()
