FROM python:latest

WORKDIR /app

RUN pip install pymongo pyodbc
RUN pip install pandas
RUN pip install numpy
RUN pip install requests
RUN pip install pymssql
RUN pip install pyodbc
RUN pip install fastapi uvicorn
RUN pip install pydantic
RUN pip install fastapi
RUN pip install fastapi-utils
RUN pip install pydantic

CMD ["python", "/app/sync.py"]
