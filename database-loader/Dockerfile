FROM python:3.10-alpine
WORKDIR /restorer

ENV PYTHONUNBUFFERED=1
ENV DB_ENDPOINT=mysql
ENV DB_NAME=books
ENV DB_USERNAME=root
ENV DB_PASSWORD=
ENV DB_PORT=3306

COPY . .

CMD ["python", "-u", "main.py"];