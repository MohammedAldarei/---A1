import re
from collections import Counter


def analyze(file):
    patterns = {
        "SQL Injection": re.compile(
            r"union\s+select|select\s+.*from|or\s+1\s*=\s*1",
            re.IGNORECASE
        ),

        "Path Traversal": re.compile(
            r"\.\./|%2e%2e%2f|/etc/passwd",
            re.IGNORECASE
        ),

        "XSS": re.compile(
            r"<script|javascript:|onerror\s*=",
            re.IGNORECASE
        ),

        "Command Injection": re.compile(
            r";\s*(cat|whoami|id|ls)|\|\s*(cat|whoami|id)",
            re.IGNORECASE
        )
    }

    result = Counter()
    total = 0

    try:
        with open(file, "r", encoding="utf-8", errors="ignore") as f:

            for num, line in enumerate(f, 1):

                for name, pattern in patterns.items():

                    if pattern.search(line):

                        ip = re.match(r"(\S+)", line)

                        if ip:
                            ip = ip.group(1)
                        else:
                            ip = "Unknown"

                        print(
                            f"[ALERT] {name} | "
                            f"Line: {num} | IP: {ip}"
                        )

                        result[name] += 1
                        total += 1
                        break

    except FileNotFoundError:
        print("Error: File not found.")
        return

    except OSError:
        print("Error: Cannot read the file.")
        return

    print("\n========== SCAN SUMMARY ==========")
    print(f"Total attacks detected: {total}")

    if result:
        for name, count in result.items():
            print(f"{name}: {count}")
    else:
        print("No suspicious activity found.")

    print("==================================")


def main():
    print("AUTOMATED LOG ANALYZER")

    file = input("Enter log file path: ").strip()

    analyze(file)


if __name__ == "__main__":
    main()
