import socket
import ipaddress


def get_banner(ip, port):
    try:
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
            s.settimeout(3)
            s.connect((ip, port))

            if port in [80, 8080, 8000]:
                msg = f"HEAD / HTTP/1.1\r\nHost: {ip}\r\nConnection: close\r\n\r\n"
                s.sendall(msg.encode())

            data = s.recv(1024)

            if data:
                text = data.decode("utf-8", errors="ignore").strip()

                if text:
                    return text.splitlines()[0]

    except (socket.timeout, OSError):
        return None

    return None


def main():
    print("BANNER GRABBING TOOL")

    ip = input("Enter IP: ").strip()

    try:
        ipaddress.IPv4Address(ip)
    except ValueError:
        print("Invalid IP address")
        return

    ports = input("Enter ports (example: 22,21,80,443): ")

    try:
        ports = [int(p.strip()) for p in ports.split(",")]

        if not ports or any(p < 1 or p > 65535 for p in ports):
            raise ValueError

    except ValueError:
        print("Invalid ports")
        return

    for p in ports:
        print(f"\nChecking port {p}...")

        result = get_banner(ip, p)

        if result:
            print(f"Banner: {result}")
        else:
            print("No banner received")


if __name__ == "__main__":
    main()
