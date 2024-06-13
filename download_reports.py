import sys
import pandas as pd
from sqlalchemy import create_engine
import xlsxwriter

def fetch_data(month, engine):
    # SQL query to fetch data for the specified month from the 'resultat' table
    query = f"SELECT * FROM resultat WHERE MOIS = '{month}'"
    df = pd.read_sql(query, engine)
    return df

def save_to_excel(df, month):
    # Define file path
    file_path = f'downloads/monthly_data_{month}.xlsx'
    
    # Create a Pandas Excel writer using XlsxWriter as the engine
    writer = pd.ExcelWriter(file_path, engine='xlsxwriter')
    
    # Convert the dataframe to an XlsxWriter Excel object
    df.to_excel(writer, sheet_name='Sheet1', index=False)
    
    # Close the Pandas Excel writer and output the Excel file
    writer.save()
    return file_path

def main():
    if len(sys.argv) != 6:
        print("Usage: python download_reports.py <month> <host> <db_name> <username> <password>")
        sys.exit(1)

    month, host, db_name, username, password = sys.argv[1:6]
    # Create a connection to the database using SQLAlchemy
    engine = create_engine(f'mysql+pymysql://{username}:{password}@{host}/{db_name}')
    data = fetch_data(month, engine)
    if not data.empty:
        file_path = save_to_excel(data, month)
        print(file_path)  # Output the file path for the calling PHP script to capture
    else:
        print("No data available for download.")

if __name__ == "__main__":
    main()
