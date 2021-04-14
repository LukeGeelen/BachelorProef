FROM ubuntu:16.04
RUN apt-get update -y && apt-get install  -y software-properties-common
RUN add-apt-repository ppa:deadsnakes/ppa
RUN apt-get update -y && apt-get install -y gcc python3.9
WORKDIR /usr/src/
COPY main.py main.py
COPY checks.json checks.json
COPY studentCode.c studentCode.c
RUN gcc -o studentProgram studentCode.c
CMD python3.9 main.py