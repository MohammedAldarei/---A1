#include <stdio.h>
#include <string.h>
#include <ctype.h>

void check(char pass[])
{
    int len = strlen(pass);
    int up = 0, low = 0, num = 0, sym = 0;
    int score = 0;

    for (int i = 0; i < len; i++)
    {
        if (isupper((unsigned char)pass[i]))
            up = 1;
        else if (islower((unsigned char)pass[i]))
            low = 1;
        else if (isdigit((unsigned char)pass[i]))
            num = 1;
        else
            sym = 1;
    }

    printf("\nLength: %d\n", len);
    printf("Uppercase: %s\n", up ? "Yes" : "No");
    printf("Lowercase: %s\n", low ? "Yes" : "No");
    printf("Numbers: %s\n", num ? "Yes" : "No");
    printf("Symbols: %s\n", sym ? "Yes" : "No");

    score = up + low + num + sym;

    if (len >= 8)
        score++;

    printf("Score: %d/5\n", score);

    if (len < 8 || score <= 2)
        printf("Password is Weak\n");
    else if (score <= 4)
        printf("Password is Acceptable\n");
    else
        printf("Password is Strong\n");
}

int main()
{
    char pass[100];

    printf("Enter password: ");
    scanf("%99s", pass);

    check(pass);

    return 0;
}
