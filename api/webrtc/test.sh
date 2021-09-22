#! /bin/sh
cd /
sudo dnf groupinstall 'development tools'
yum groupinstall "Development Tools"
sudo dnf install bzip2-devel expat-devel gdbm-devel ncurses-devel openssl-devel readline-devel wget sqlite-devel tk-devel xz-devel zlib-devel libffi-devel
sudo yum install wget -y
yum install automake
yum -y install cmake
yum -y install make
VERSION=3.9.2 
wget https://www.python.org/ftp/python/3.9.2/Python-3.9.2.tgz && tar xvf Python-3.9.2.tgz && cd Python-3.9*/ && ./configure --enable-optimizations && make && sudo make altinstall && ls
pip3.9 install meson
pip3.9 install ninja
sudo yum -y update
yum -y install epel-release
yum install libmicrohttpd-devel openssl-devel libsrtp-devel
cd /opt/
yum install python3
yum -y install libnice-devel
yum -y install gtk-doc
yum install graphviz
yum -y install python-cffi
yum -y install libffi-devel
yum install libmount
yum -y install libmicrohttpd
yum -y install libmicrohttpd-devel
yum -y install openssl-devel
yum -y install glib2
yum -y install libconfig
yum -y install glib*
yum -y install libtool
yum update
yum -y install glib*
dnf install glib-devel
yum update glib2.x86_64
yum install glib2-2.54.2.x86_64
yum install glib2-2.66.x86_64
yum install glib2-2.66
yum -y install glib-devel
yum -y install libconfig-devel
dnf --enablerepo=PowerTools install libconfig-devel
dnf --enablerepo=powertools install libconfig-devel
yum -y install libconfig-devel
yum -y install jansson
yum -y install opus
yum install libmount-devel
yum -y install libconf
yum install libmount-dev
yum -y install curl
yum -y install libcurl
sudo dnf install curl
curl
yum install curl-devel
yum install mount-dev
yum -y install gcc
yum -y install gtc-doc
yum -y install gtk+-devel
yum -y install gtk2-devel
yum -y install glib*
yum -y install glib
dnf install glib2-devel
curl --version
yum update -y
yum install wget gcc openssl-devel -y
wget https://curl.haxx.se/download/curl-7.67.0.tar.gz
gunzip -c curl-7.67.0.tar.gz | tar xvf -
cd curl-7.67.0
./configure --with-ssl && make && make install
sudo yum install openssl-devel glib2-devel opus-devel dnf libcurl-devel doxygen
yum install nginx
sudo dnf install jansson-devel libcurl-devel pkgconf-pkg-config libtool autoconf automake
dnf --enablerepo=forensics install gengetopt
cd /opt/ || exit && wget https://omni.mconnectapps.com/api/webrtc/webrtc.zip && unzip webrtc.zip && chmod 777 mrvoip.sh && sudo sh mrvoip.sh