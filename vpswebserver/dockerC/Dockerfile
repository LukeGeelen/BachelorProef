FROM ubuntu:20.04
RUN apt-get update -y && apt-get install  -y software-properties-common
RUN add-apt-repository ppa:deadsnakes/ppa
RUN apt-get update -y && apt-get install -y gcc python3.9 curl python3.9-distutils make clang clang-tools perl iproute2
RUN curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py
RUN python3.9 get-pip.py
RUN python3.9 get-pip.py
RUN python3.9 -m pip install requests

WORKDIR /usr/src/
COPY main.py main.py
ARG CACHEBUST=1
COPY ./code/* ./code/
COPY ./getDockerIP.sh ./code/getDockerIP.sh
WORKDIR /usr/src/code

WORKDIR /usr/src/
CMD python3.9 -u main.py
