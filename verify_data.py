import sys
import pandas as pd
import json

def verify_names(collaborators_file, production_file, charges_file, ref_file):
    try:
        collaborators_df = pd.read_excel(collaborators_file)
        production_df = pd.read_excel(production_file)
        charges_df = pd.read_excel(charges_file)
        ref_df = pd.read_excel(ref_file)
        
        problematic_names = []

        # Check names in production and charges files
        for name in collaborators_df['Name']:  # Assuming 'Name' is the column with names
            if name not in production_df['Name'].values or name not in charges_df['Name'].values:
                # Check in reference file
                if name not in ref_df['Name'].values:
                    problematic_names.append(name)

        if problematic_names:
            return {'success': False, 'message': f'Names not found: {", ".join(problematic_names)}'}
        return {'success': True, 'message': 'All names verified successfully.'}
    except Exception as e:
        return {'success': False, 'message': str(e)}

if __name__ == '__main__':
    collaborators_file_path = sys.argv[1]
    production_file_path = sys.argv[2]
    charges_file_path = sys.argv[3]
    ref_file_path = sys.argv[4]
    result = verify_names(collaborators_file_path, production_file_path, charges_file_path, ref_file_path)
    print(json.dumps(result))

