import numpy as np
import json
from datetime import datetime

# Définir les listes de caractéristiques
all_tastes = ["Chocolat", "Nature", "Sucré", "Miel", "Caramel", "Speculoos", "Fraise"]
all_shapes = ["Boule", "Triangle", "Cube", "Petale", "Donut", "Etoile"]
all_categories = ["Bio", "Gourmand", "Sportif", "Mélange magique"]

def get_age(date_of_birth):
    birth_date = datetime.strptime(date_of_birth, "%Y-%m-%d")
    age = datetime.now().year - birth_date.year
    return age

# Fonction pour encoder les caractéristiques des produits
def encode_product_features(product, all_tastes, all_shapes, all_categories):
    taste_vector = [0] * len(all_tastes)
    shape_vector = [0] * len(all_shapes)
    category_vector = [0] * len(all_categories)

    for taste in product["caracteristiques_gouts"]:
        taste_vector[all_tastes.index(taste)] = 1
    for shape in product["caracteristiques_formes"]:
        shape_vector[all_shapes.index(shape)] = 1
    for category in product["categorie"]:
        category_vector[all_categories.index(category)] = 1

    return np.array(taste_vector + shape_vector + category_vector)

# Fonction pour encoder les caractéristiques des utilisateurs
def encode_user_features(user):
    age = get_age(user["date_de_naissance"])
    user_vector = [age, user["sexe"], {"sportif": 0, "gourmand": 1, "bio": 2}[user["type_consommation"]], 
                   {"chocolat": 0, "nature": 1, "sucree": 2, "miel": 3, "caramel": 4, "speculoos": 5, "fruits rouges": 6}[user["gout_prefere"]], 
                   {"boule": 0, "triangle": 1, "cube": 2, "petale": 3, "donut": 4, "etoile": 5}[user["forme_favorite"]]]
    return user_vector
