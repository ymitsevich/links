FROM debian:jessie

RUN apt-get update && apt-get install -y nginx

ADD nginx.conf /etc/nginx/
ADD links.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/links.conf /etc/nginx/sites-enabled/links
RUN rm /etc/nginx/sites-enabled/default

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

#RUN usermod -u 1001 www-data
#USER www-data
RUN mkdir /links
#RUN chown -R www-data:www-data /links
#RUN chmod -R 777 /links/storage

CMD ["nginx"]

EXPOSE 80
