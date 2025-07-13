FROM python:3.10-slim-buster
WORKDIR /app
COPY library.txt .
RUN pip install --no-cache-dir -r library.txt
RUN python -m spacy download ru_core_news_md
COPY . .
EXPOSE 8003
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8003"]