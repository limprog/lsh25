FROM python:3.9-slim-buster
WORKDIR /app
COPY library.txt .
RUN pip install --no-cache-dir -r library.txt
COPY . .
EXPOSE 8000
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8001"]