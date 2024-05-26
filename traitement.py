import sys
import pandas as pd
import mysql.connector
from mysql.connector import Error

def create_connection(host_name, user_name, user_password, db_name):
    """Create a database connection."""
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

def load_data(connection):
    """Load data from the database."""
    collaborateur = pd.read_sql('SELECT * FROM collaborateur', con=connection)
    production = pd.read_sql('SELECT * FROM production', con=connection)
    charges = pd.read_sql('SELECT * FROM charges', con=connection)
    annexe = pd.read_sql('SELECT * FROM annexe', con=connection)
    incharges = pd.read_sql('SELECT * FROM incharges', con=connection)
    return collaborateur, production, charges, annexe, incharges

def merge_tables(collaborateur, production, charges):
    """Merge the collaborateur, production, and charges tables."""
    return collaborateur.merge(production, on=['NOM_PRENOM', 'MOIS']).merge(charges, on=['NOM_PRENOM', 'MOIS'])

def replace_with_annexe(row, annexe):
    """Replace rows where 'INTERCO' is 'annexe' with corresponding rows from the annexe table."""
    if row['INTERCO'] == 'annexe':
        return annexe[(annexe['NOM_PRENOM'] == row['NOM_PRENOM']) & (annexe['MOIS'] == row['MOIS'])].iloc[0]
    return row

def add_analytique_columns(resultat, incharges):
    """Add columns for each unique 'ANALYTIQUE' value from the incharges table."""
    for analytique in incharges['ANALYTIQUE'].unique():
        resultat[analytique] = resultat.apply(lambda row: incharges[(incharges['NOM_PRENOM'] == row['NOM_PRENOM']) & (incharges['MOIS'] == row['MOIS']) & (incharges['ANALYTIQUE'] == analytique)]['SOLDE_PART'].sum(), axis=1)
    return resultat

def save_result(resultat, connection):
    """Save the final result to the database."""
    cursor = connection.cursor()
    cursor.execute("DROP TABLE IF EXISTS resultat")
    resultat.to_sql('resultat', con=connection, if_exists='replace', index=False)

def main():
    if len(sys.argv) != 7:
        print("Usage: python traitement.py <month> <host> <db_name> <username> <password>")
        return

    selected_month = sys.argv[1]
    host = sys.argv[2]
    db_name = sys.argv[3]
    username = sys.argv[4]
    password = sys.argv[5]

    connection = create_connection(host, username, password, db_name)
    if connection is None:
        return

    collaborateur, production, charges, annexe, incharges = load_data(connection)
    resultat = merge_tables(collaborateur, production, charges)
    resultat = resultat.apply(lambda row: replace_with_annexe(row, annexe), axis=1)
    resultat = add_analytique_columns(resultat, incharges)
    save_result(resultat, connection)

    connection.close()

if __name__ == "__main__":
    main()

