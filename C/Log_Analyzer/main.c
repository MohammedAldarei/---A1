#include <stdio.h>
#include <string.h>

int main()
{
    FILE *f;
    char line[512];
    char name[100];
    char key[50];
    int count = 0;
    int num = 0;

    printf("Enter log file name: ");
    scanf("%99s", name);

    printf("Enter keyword: ");
    scanf("%49s", key);

    f = fopen(name, "r");

    if (f == NULL)
    {
        printf("Cannot open file\n");
        return 1;
    }

    while (fgets(line, sizeof(line), f) != NULL)
    {
        num++;

        if (strstr(line, key) != NULL)
        {
            count++;
            printf("Line %d: %s", num, line);
        }
    }

    printf("\nTotal alerts: %d\n", count);

    fclose(f);

    return 0;
}
