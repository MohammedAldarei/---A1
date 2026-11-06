#include <stdio.h>

int main()
{
    FILE *f;
    unsigned char buffer[16];
    char name[100];
    int n;

    printf("Enter file name: ");
    scanf("%99s", name);

    f = fopen(name, "rb");

    if (f == NULL)
    {
        printf("Cannot open file\n");
        return 1;
    }

    while ((n = fread(buffer, 1, 16, f)) > 0)
    {
        for (int i = 0; i < n; i++)
        {
            printf("%02X ", buffer[i]);
        }

        for (int i = n; i < 16; i++)
        {
            printf("   ");
        }

        printf(" ");

        for (int i = 0; i < n; i++)
        {
            if (buffer[i] >= 32 && buffer[i] <= 126)
                printf("%c", buffer[i]);
            else
                printf(".");
        }

        printf("\n");
    }

    fclose(f);

    return 0;
}
