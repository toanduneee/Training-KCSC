FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
  dnsutils \
  net-tools \
  iputils-ping \
  curl \
  netcat-traditional \
  nmap \
  wget \
  git \
  bash \
  vim \
  procps \
  tcpdump \
  lsof \
  iproute2 \
  inetutils-traceroute \
  && apt-get clean

COPY . /var/www/html
WORKDIR /var/www/html

CMD ["php", "-S", "0.0.0.0:80"]
