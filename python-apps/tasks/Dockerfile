# tasks/Dockerfile или python-app-tasks/Dockerfile
FROM python:3.9-slim-buster
WORKDIR /app
COPY library.txt .
RUN pip install --no-cache-dir -r library.txt
COPY . .
EXPOSE 8000 # Убедитесь, что порт выставлен, если его нет
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000"]