import json
import sys
import numpy as np
from tensorflow.keras.models import Model
from tensorflow.keras.layers import Input, Dense, Dropout, Concatenate
from sklearn.model_selection import train_test_split
from data_preprocessing import encode_user_features, encode_product_features

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
    # Passer les données depuis la ligne de commande
    users_data = json.loads(sys.argv[1])
    products_data = json.loads(sys.argv[2])
    dynamic_values = json.loads(sys.argv[3])
    print("je suis là")
    train_model(users_data, products_data)
