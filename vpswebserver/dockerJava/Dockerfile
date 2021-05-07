FROM ubuntu:16.04
RUN apt-get update -y && apt-get install  -y software-properties-common
RUN add-apt-repository ppa:deadsnakes/ppa
RUN apt-get update -y && apt-get install -y gcc python3.9 curl python3.9-distutils default-jdk make iproute2
RUN curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py
RUN python3.9 get-pip.py
RUN python3.9 get-pip.py
RUN python3.9 -m pip install requests

ARG CACHEBUST=1
WORKDIR /usr/src/
COPY ./code/* ./code/
COPY ./getDockerIP.sh ./code/getDockerIP.sh
WORKDIR /usr/src/code
COPY main.py main.py
RUN make
WORKDIR /usr/src/code
CMD python3.9 -u main.py
