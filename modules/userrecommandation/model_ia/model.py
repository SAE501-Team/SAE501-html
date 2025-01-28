import json
import sys
import numpy as np
import re
from tensorflow.keras.models import Model
from tensorflow.keras.layers import Input, Dense, Dropout, Concatenate
from sklearn.model_selection import train_test_split
from data_preprocessing import encode_user_features, encode_product_features

def nettoyer_argument(arg):
    try:
        # 1. Remplacer les guillemets successifs doubles par un seul
        arg = re.sub(r'""+', '"', arg)

        # 2. Ajouter des guillemets autour des clés (clé sans guillemets)
        arg = re.sub(r'(?<!")([a-zA-Z0-9_]+)(?=\s*:)', r'"\1"', arg)

        # 3. Ajouter des guillemets autour des valeurs texte non numériques
        arg = re.sub(r':\s*([a-zA-Z_]+)(?=[,\}])', r': "\1"', arg)

        # 4. Remplacer true/false/null entre guillemets par leurs valeurs JSON valides
        arg = re.sub(r'"(true|false|null)"', r'\1', arg)

        # 5. Encadrer les dates non entourées de guillemets
        arg = re.sub(r'(?<!")(\d{4}-\d{2}-\d{2})(?!")', r'"\1"', arg)

        # 6. Corriger les listes avec des chaînes manquantes de guillemets
        arg = re.sub(r'\[([a-zA-Z0-9_,\s]+)\]', lambda m: 
                     '[' + ','.join(f'"{v.strip()}"' for v in m.group(1).split(',')) + ']', arg)

        # 7. Supprimer les espaces inutiles
        arg = re.sub(r'\s+', ' ', arg)

        # 8. Vérifier si le JSON est bien formé
        json_object = json.loads(arg)
        return arg

    except json.JSONDecodeError as e:
        print(f"Erreur JSON après nettoyage : {e}")
        print("Problème au niveau de la chaîne corrigée :")
        print(arg)
        raise ValueError("Le JSON reste mal formé après correction.")



def train_model(users_data, products_data, dynamic_values):
    # Préparer les données d'entraînement
    user_ids = []
    product_ids = []
    ratings = []
    # Récupération des valeurs dynamiques
    all_tastes = dynamic_values["tastes"]
    all_shapes = dynamic_values["shapes"]
    all_categories = dynamic_values["categories"]
    print("ça va jusqu'ici")

    for user_idx, user in enumerate(users_data["users"]):
        for rating_entry in user.get("rating", []):
            user_ids.append(user_idx)
            product_ids.append(rating_entry["id_produit"])
            ratings.append(rating_entry["rating"])

    # Convertir les données en numpy arrays
    encoded_user_ids = np.array(user_ids)
    encoded_product_ids = np.array(product_ids)
    ratings = np.array(ratings)
    print("encore en vie")
    # Encodage des caractéristiques des utilisateurs et des produits
    X_user = np.array([encode_user_features(users_data["users"][user_id]) for user_id in encoded_user_ids])
    X_product = np.array([encode_product_features(products_data["products"][product_id]) for product_id in encoded_product_ids],all_tastes,all_shapes,all_categories)

    # Diviser les données en ensembles d'entraînement et de validation
    X_user_train, X_user_val, X_product_train, X_product_val, y_train, y_val = train_test_split(
        X_user, X_product, ratings, test_size=0.2, random_state=42
    )
    print("toujours là")
    # Définir les paramètres du modèle
    embedding_size = 50  # Taille des embeddings
    input_user_shape = X_user.shape[1]
    input_product_shape = X_product.shape[1]

    # Définir les entrées du modèle
    user_input = Input(shape=(input_user_shape,), name="user_input")
    product_input = Input(shape=(input_product_shape,), name="product_input")
    print("avant dernier")
    # Embedding dense pour les utilisateurs et les produits
    user_embedding = Dense(embedding_size, activation="relu")(user_input)
    product_embedding = Dense(embedding_size, activation="relu")(product_input)

    # Combiner les embeddings
    combined = Concatenate()([user_embedding, product_embedding])

    # Réseau dense
    dense1 = Dense(128, activation="relu")(combined)
    dropout1 = Dropout(0.3)(dense1)
    dense2 = Dense(64, activation="relu")(dropout1)
    dropout2 = Dropout(0.3)(dense2)
    print("dernier")
    # Sortie du modèle
    output = Dense(1, activation="sigmoid")(dropout2)

    # Définir et compiler le modèle
    model = Model(inputs=[user_input, product_input], outputs=output)
    model.compile(optimizer="adam", loss="binary_crossentropy", metrics=["accuracy"])

    # Résumé du modèle
    model.summary()

    # Entraîner le modèle
    model.fit(
        [X_user_train, X_product_train], y_train,
        validation_data=([X_user_val, X_product_val], y_val),
        epochs=10,
        batch_size=32
    )

    # Sauvegarder le modèle
    model.save("recommender_model.h5")

    print("Modèle entraîné et sauvegardé.")

# Vérifier que des arguments sont passés
if __name__ == "__main__":
    try:
        #print(f"Argument brut 1 : {sys.argv[1]}")
        cleaned_json_1 = nettoyer_argument(sys.argv[1])
        cleaned_json_2 = nettoyer_argument(sys.argv[2])
        cleaned_json_3 = nettoyer_argument(sys.argv[3])

        print(f"Argument clean : {cleaned_json_1}")
        # Charger les données JSON des arguments nettoyés
        users_data = json.loads(cleaned_json_1)
        products_data = json.loads(cleaned_json_2)
        dynamic_values = json.loads(cleaned_json_3)
        print(f"Argument jsoner : {users_data}")
    
    except IndexError:
        print("Erreur : Vous devez passer 3 arguments à ce script.")
        sys.exit(1)

    except json.JSONDecodeError as e:
        print(f"Erreur de décodeur JSON : {e}")
        sys.exit(1)

    # Afficher les données nettoyées (optionnel)
    print("Données chargées et nettoyées avec succès.")

    # Appeler ta fonction de formation du modèle
    train_model(users_data, products_data)
    print(json.dumps({"status": "success", "message": "Modèle entraîné avec succès"}))
