import sys
import pandas as pd
import openpyxl
import mysql.connector
from mysql.connector import Error
import re
import json
import csv
from datetime import datetime

def create_connection(host_name, user_name, user_password, db_name):
    connection = None
    try:
        connection = mysql.connector.connect(
            host=host_name,
            user=user_name,
            passwd=user_password,
            database=db_name
        )
        print("MySQL Database connection successful")
    except Error as e:
        print(f"The error '{e}' occurred")
    return connection

def sanitize_column_name(name):
    name = name.replace(" ", "_").upper()
    name = re.sub(r'[^A-Z0-9_]', '', name)
    return name

def read_columns(file_path):
    """Read column names from a file, supporting both CSV and XLSX."""
    if file_path.endswith('.csv'):
        with open(file_path, newline='') as csvfile:
            reader = csv.reader(csvfile)
            columns = next(reader)  # Read the first line to get column headers
    elif file_path.endswith('.xlsx'):
        workbook = openpyxl.load_workbook(file_path , data_only=True)
        sheet = workbook.active
        columns = [cell.value for cell in next(sheet.iter_rows(min_row=1, max_row=1))]
    else:
        raise ValueError("Unsupported file format")
    return columns

def create_table_from_file(connection, file_path, table_name):
    """Create a table in the database from a file."""
    columns = read_columns(file_path)
    sanitized_columns = [sanitize_column_name(col) for col in columns]
    column_definitions = [f"{col} TEXT" for col in sanitized_columns]

    cursor = connection.cursor()
    try:
        cursor.execute(f"SHOW TABLES LIKE '{table_name}';")
        if cursor.fetchone():
            cursor.execute(f"DROP TABLE {table_name};")
            print(f"Existing table {table_name} dropped.")
    
        create_table_query = f"""
        CREATE TABLE IF NOT EXISTS {table_name} (
            {', '.join(column_definitions)}
             );
            """
        cursor.execute(create_table_query)
        print(f"Table {table_name} created successfully")
    except Error as e:
        print(f"The error '{e}' occurred")

def insert_data_from_file(connection, file_path, table_name):
    """Insert data from a file into the database."""
    columns = read_columns(file_path)
    sanitized_columns = [sanitize_column_name(col) for col in columns]
    insert_sql = f"INSERT INTO {table_name} ({', '.join(sanitized_columns)}) VALUES ({', '.join(['%s'] * len(sanitized_columns))})"

    if file_path.endswith('.csv'):
        with open(file_path, newline='') as csvfile:
            reader = csv.reader(csvfile)
            next(reader)  # Skip the header row
            for row in reader:
                # row = format_date_in_row(row, columns, 'MOIS')
                insert_row(connection, insert_sql, row)
    elif file_path.endswith('.xlsx'):
        workbook = openpyxl.load_workbook(file_path ,data_only=True)
        sheet = workbook.active
        for row in sheet.iter_rows(min_row=2):  # Skip the header row
            cell_values = []
            for cell in row:
                if isinstance(cell.value, datetime):
                    cell_values.append(cell.value.strftime('%m/%d/%y'))  # Format for SQL DATE
                elif isinstance(cell.value, (int, float)):
                    cell_values.append(round(cell.value, 3))
                else:
                    cell_values.append(cell.value)
            # cell_values = format_date_in_row(cell_values, [cell.value for cell in next(sheet.iter_rows(min_row=1, max_row=1))], 'MOIS')
            insert_row(connection, insert_sql, cell_values)


def insert_row(connection, insert_sql, row):
    """Helper function to insert a row into the database."""
    cursor = connection.cursor()
    try:
        formatted_row = [str(cell).strip() if cell is not None else None for cell in row]
        cursor.execute(insert_sql, formatted_row)
        connection.commit()
    except Error as e:
        print(f"Failed to insert row {row}: {e}")
        
def convert_column_to_date(connection, table_name, column_name):
    """Convert a TEXT column to DATE type after formatting the date."""
    # First, update the column to format dates into 'YYYY-MM-DD'
    format_dates_query = f"""
    UPDATE {table_name}
    SET {column_name} = STR_TO_DATE({column_name}, '%m/%d/%y');
    """
    # Then, modify the column type to DATE
    convert_type_query = f"""
    ALTER TABLE {table_name}
    MODIFY {column_name} DATE;
    """
    cursor = connection.cursor()
    try:
        cursor.execute(format_dates_query)
        connection.commit()
        print(f"Dates in column {column_name} formatted successfully.")
        
        cursor.execute(convert_type_query)
        connection.commit()
        print(f"Column {column_name} converted to DATE successfully.")
    except Error as e:
        print(f"Failed to convert {column_name} to DATE: {e}")

def check_names(collaborators_path, production_path, charges_path, ref_file_path):
    collaborators = pd.read_excel(collaborators_path)
    production = pd.read_excel(production_path)
    charges = pd.read_excel(charges_path)
    ref = pd.read_excel(ref_file_path)
    unmatched_names = []
    for name in collaborators['Name'].unique():
        if name not in production['Name'].tolist() and name not in charges['Name'].tolist():
            alternative_name = ref.loc[ref['Original Name'] == name, 'Alternative Name'].values
            if alternative_name.size > 0 and (alternative_name[0] in production['Name'].tolist() or alternative_name[0] in charges['Name'].tolist()):
                continue
            unmatched_names.append(name)
    if unmatched_names:
        print(f"Unmatched names found: {', '.join(unmatched_names)}")
        return False
    return True

def main():
    if len(sys.argv) != 9:
        print("Usage: python process_general_files.py <collaboratorsFilePath> <productionFilePath> <chargesFilePath> <refFilePath> <host> <db_name> <username> <password>")
        return
    collaborators_path = sys.argv[1]
    production_path = sys.argv[2]
    charges_path = sys.argv[3]
    ref_file_path = sys.argv[4]
    host_name = sys.argv[5]
    db_name = sys.argv[6]
    user_name = sys.argv[7]
    user_password = sys.argv[8]
    connection = create_connection(host_name, user_name, user_password, db_name)
    if check_names(collaborators_path, production_path, charges_path, ref_file_path):
        file_paths = [collaborators_path, production_path, charges_path]
        for file_path in file_paths:
            table_name = file_path.split('/')[-1].split('.')[0].upper() + "_TABLE"
            create_table_from_file(connection, file_path, table_name)
            insert_data_from_file(connection, file_path, table_name)
            convert_column_to_date(connection, table_name, "MOIS")
        print(json.dumps({"success": True}))
    else:
        print(json.dumps({"success": False, "message": "Name verification failed"}))

if __name__ == "__main__":
    main()

