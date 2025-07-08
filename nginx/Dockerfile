# nginx/Dockerfile
# nginx/Dockerfile
FROM nginx:latest
COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./wait-for-it.sh /usr/local/bin/wait-for-it.sh # Копируем скрипт
RUN chmod +x /usr/local/bin/wait-for-it.sh # Делаем его исполняемым
EXPOSE 80
EXPOSE 443
# Изменяем CMD, чтобы NGINX ждал бэкенды
CMD ["/usr/local/bin/wait-for-it.sh", "python-app-tasks:8000", "--", \
     "/usr/local/bin/wait-for-it.sh", "python-app-users:8001", "--", \
     "nginx", "-g", "daemon off;"]