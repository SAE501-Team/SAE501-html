#!/usr/bin/env python3

import sys
import json

# Vérifiez si suffisamment d'arguments sont passés
if len(sys.argv) < 3:
    print("Usage: python test_variables.py '<users_data>' '<products_data>'")
    sys.exit(1)

# Récupérez et chargez les arguments JSON
try:
    users_data = json.loads(sys.argv[1])
    products_data = json.loads(sys.argv[2])

    # Affichez les données récupérées
    print("Users Data:")
    print(json.dumps(users_data, indent=4))

    print("\nProducts Data:")
    print(json.dumps(products_data, indent=4))

except json.JSONDecodeError as e:
    print("Erreur lors du chargement des données JSON :", e)
    sys.exit(1)
