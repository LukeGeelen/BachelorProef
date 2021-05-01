.DEFAULT_GOAL := all

CC=gcc

clean:
	rm -rf *.o
	rm -rf *.out

compile:
	$(CC) -c studentCode.c -o main.o

link:
	$(CC) -o main.out main.o

all: clean compile link

