# nginx/Dockerfile
FROM nginx:latest

# Удаляем дефолтный файл index.html от Nginx, чтобы избежать конфликтов
RUN rm /etc/nginx/html/index.html

# Копируем ваш nginx.conf
COPY nginx.conf /etc/nginx/nginx.conf

# Копируем ваш main.html и папку assets в стандартную директорию Nginx для статики
COPY main.html /usr/share/nginx/html/
COPY assets /usr/share/nginx/html/assets/

# Копируем и делаем исполняемым wait-for-it.sh
COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

# Nginx запускается по умолчанию
# Если у вас в docker-compose.yml есть 'command' для nginx,
# то это переопределит ENTRYPOINT из базового образа.
# Например:
# command: ["/usr/local/bin/wait-for-it.sh", "tasks_server:8000", "users_server:8001", "--", "nginx", "-g", "daemon off;"]