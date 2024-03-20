import pymysql
from pymysql.constants import CLIENT
import os
import time

def refreshDatabase():
    # Get RDS instance information from environment variables
    endpoint = os.environ['DB_ENDPOINT']
    db_name = os.environ['DB_NAME']
    username = os.environ['DB_USERNAME']
    password = os.environ['DB_PASSWORD']
    port = int(os.environ['DB_PORT']) or 3306
    # Connect to the RDS instance using PyMySQL
    conn = {
        "host":endpoint,
        "user":username,
        "port":port,
        "password":password,
        "cursorclass":pymysql.cursors.DictCursor,
        "client_flag": CLIENT.MULTI_STATEMENTS
    }

    try:
        # Read the MySQL file content
        with open('./database.sql', 'r') as file:
            mysql_file_content = file.read()

        # Execute SQL commands in the MySQL file
        with pymysql.connect(**conn).cursor() as cursor:
            cursor.execute(mysql_file_content)

        print("MySQL file data successfully imported into RDS instance.");
        return {
            'statusCode': 200,
            'body': 'MySQL file data successfully imported into RDS instance.'
        }

    except Exception as e:
        print(f'Error: {str(e)}');
        return {
            'statusCode': 500,
            'body': f'Error: {str(e)}'
        }

refreshDatabase();

while True:
    time.sleep(900);
    print("Refreshing database");
    refreshDatabase();