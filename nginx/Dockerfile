FROM nginx:latest
COPY nginx.conf /etc/nginx/nginx.conf
COPY main.html /usr/share/nginx/html/
COPY assets /usr/share/nginx/html/assets/
COPY css /usr/share/nginx/html/css
COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh