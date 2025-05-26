# generate_testdata.py

import pyodbc
import random
from datetime import datetime, timedelta

# Verbindung herstellen
conn = pyodbc.connect(
    'DRIVER={ODBC Driver 17 for SQL Server};'
    'SERVER=localhost;'
    'DATABASE=Shop;'
    'UID=sa;'
    'PWD=dein_passwort;'  # Ändere dies zu deinem Passwort
)

cursor = conn.cursor()

# Hilfsfunktion: Zufälliges Datum in letzter Zeit
def random_date():
    return datetime.now() - timedelta(days=random.randint(1, 365))

# Gruppen laden
cursor.execute("SELECT ID, Name FROM Gruppen")
gruppen = {name: id for (id, name) in cursor.fetchall()}

# Produkte laden
cursor.execute("SELECT ID, Name FROM Produkte")
produkte = {name: id for (id, name) in cursor.fetchall()}

# Kunden laden
cursor.execute("SELECT ID, GruppeID FROM Kunden")
kunden = {id: gruppe for (id, gruppe) in cursor.fetchall()}

# Group_Product_Rules löschen und neu befüllen
cursor.execute("DELETE FROM Group_Product_Rules")

affinitaeten = {
    'Oma': {
        'Mehl': 0.95,
        'Nähset': 0.88,
        'Mugler Alien': 0.12,
        'I Phone 16 Pro Max': 0.23,
        'Controller': 0.28,
        'Tik Tok Mystery Box': 0.44,
        'Pokemon Karten': 0.45
    },
    'Gamer': {
        'Holy Energy': 0.99,
        'Controller': 0.76,
        'Pokemon Karten': 0.45,
        'Pennergranate': 0.12,
        'Vodka': 0.31,
        'Sangria': 0.11,
        'I Phone 16 Pro Max': 0.33
    },
    'Kind': {
        'Tik Tok Mystery Box': 0.87,
        'Pokemon Karten': 0.95,
        'Controller': 0.43
    },
    'Alkoholiker': {
        'Pennergranate': 1,
        'Vodka': 0.92,
        'Sangria': 0.94
    },
    'Rich Kid': {
        'I Phone 16 Pro Max': 0.91,
        'Mugler Alien': 0.77,
        'Tik Tok Mystery Box': 0.44,
        'Nähset': 0.187
    }
}

for gruppen_name, prod_dict in affinitaeten.items():
    gruppe_id = gruppen[gruppen_name]
    for produkt_name, conf in prod_dict.items():
        if produkt_name in produkte:
            cursor.execute(
                "INSERT INTO Group_Product_Rules (GruppeID, ProduktID, Confidence) VALUES (?, ?, ?)",
                (gruppe_id, produkte[produkt_name], conf)
            )

# Warenkörbe und Bestellungen generieren
cursor.execute("DELETE FROM Bestellungen")
cursor.execute("DELETE FROM Warenkorb")

for _ in range(300):  # Anzahl der zu generierenden Warenkörbe
    timestamp = random_date()
    user_id = random.choice(list(kunden.keys()))
    gruppe_id = kunden[user_id]

    cursor.execute(
        "INSERT INTO Warenkorb (Timestamp, UserID) VALUES (?, ?)",
        (timestamp, user_id)
    )
    warenkorb_id = cursor.scope_identity().value[0]

    for _ in range(random.randint(1, 4)):  # 1 bis 4 Produkte
        cursor.execute("""
            SELECT ProduktID, Confidence
            FROM Group_Product_Rules
            WHERE GruppeID = ?
        """, (gruppe_id,))
        rules = cursor.fetchall()

        # Gewichtete Auswahl
        weighted = []
        for pid, conf in rules:
            weighted.extend([pid] * int(conf * 100))

        if weighted:
            produkt_id = random.choice(weighted)
            menge = random.randint(1, 3)
            cursor.execute(
                "INSERT INTO Bestellungen (ProduktID, K_GruppeID, WarenkorbID, ProduktAnzahl) VALUES (?, ?, ?, ?)",
                (produkt_id, gruppe_id, warenkorb_id, menge)
            )

conn.commit()
conn.close()
print("✅ Testdaten erfolgreich generiert.")