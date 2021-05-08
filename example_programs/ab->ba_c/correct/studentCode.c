#include <stdio.h>
#include <sys/types.h>
#include <unistd.h>
#include <stdlib.h>
#include <strings.h>

#define MAX_INPUT 256

int main() {
    char *inputA = malloc(MAX_INPUT);
    char *inputB = malloc(MAX_INPUT);

    fgets(inputA, MAX_INPUT, stdin);
    fgets(inputB, MAX_INPUT, stdin);

    inputA[strcspn(inputA, "\n")] = 0;
    inputB[strcspn(inputB, "\n")] = 0;

    printf("%s ", inputA);
    printf("%s\n", inputB);
    printf("%s ", inputB);
    printf("%s\n", inputA);

    return 0;
}
