FROM alpine
RUN apk update && apk upgrade
RUN apk add php7 php7-phar php7-ctype php7-mbstring
COPY proophgen.phar /usr/local/bin/proophgen
RUN chmod +x /usr/local/bin/proophgen
ENTRYPOINT ["proophgen"]
WORKDIR "/var/www"
CMD ["list"]