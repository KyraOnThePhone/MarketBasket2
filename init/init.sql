IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'Login')
BEGIN
    CREATE DATABASE Login;
END
GO

USE Login;
GO

-- Tabelle: roles
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'roles' AND type = 'U')
BEGIN
    CREATE TABLE roles (
        id INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
        role_name VARCHAR(50) NOT NULL UNIQUE
    );
END
GO

-- Tabelle: Accounts
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Accounts' AND type = 'U')
BEGIN
    CREATE TABLE Accounts (
        id INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role_id INT NOT NULL,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
    );
END
GO

-- Tabelle: permissions
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'permissions' AND type = 'U')
BEGIN
    CREATE TABLE permissions (
        id INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
        role_id INT NOT NULL,
        permission_name NVARCHAR(50) NOT NULL,
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
    );
END
GO

-- Rollen einfügen
IF NOT EXISTS (SELECT * FROM roles WHERE role_name IN ('admin', 'dev', 'user'))
BEGIN
    INSERT INTO roles (role_name) VALUES ('admin'), ('dev'), ('user');
END
GO

-- Benutzer IN23 einfügen
IF NOT EXISTS (SELECT * FROM Accounts WHERE username = 'IN23')
BEGIN
    DECLARE @role_id INT;
    SELECT @role_id = id FROM roles WHERE role_name = 'dev';

    INSERT INTO Accounts (username, password, role_id)
    VALUES ('IN23', '$2y$10$MWYm7RKEVJ8Us7S1S4j/n.l4yEDQzytDMH15PCFT0YZvYGc7nqUnC', @role_id);
END
GO

-- Berechtigungen einfügen
IF NOT EXISTS (SELECT * FROM permissions WHERE permission_name = 'dev')
BEGIN
    DECLARE @admin_role INT, @dev_role INT, @user_role INT;

    SELECT @admin_role = id FROM roles WHERE role_name = 'admin';
    SELECT @dev_role = id FROM roles WHERE role_name = 'dev';
    SELECT @user_role = id FROM roles WHERE role_name = 'user';

    INSERT INTO permissions (role_id, permission_name) VALUES
    (@admin_role, 'manage_shop'),
    (@dev_role, 'dev'),
    (@dev_role, 'manage_roles'),
    (@user_role, 'custom');
END
GO

-- --------------------------
-- 2. Shop-Datenbank erstellen
-- --------------------------
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'Shop')
BEGIN
    CREATE DATABASE Shop;
END
GO

USE Shop;
GO

-- Tabelle: Produkte
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Produkte' AND type = 'U')
BEGIN
    CREATE TABLE Produkte (
        ID INT PRIMARY KEY IDENTITY,
        Name NVARCHAR(255),
        Hersteller NVARCHAR(255),
        Bestand INT,
        Beschreibung NVARCHAR(MAX)
    );
END
GO

-- Tabelle: Gruppen
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Gruppen' AND type = 'U')
BEGIN
    CREATE TABLE Gruppen (
        ID INT PRIMARY KEY IDENTITY,
        Name NVARCHAR(255)
    );
END
GO

-- Tabelle: Kunden
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Kunden' AND type = 'U')
BEGIN
    CREATE TABLE Kunden (
        ID INT PRIMARY KEY IDENTITY,
        Name NVARCHAR(255),
        Adresse NVARCHAR(255),
        UserID INT UNIQUE,
        GruppeID INT FOREIGN KEY REFERENCES Gruppen(ID)
    );
END
GO

-- Tabelle: Warenkorb
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Warenkorb' AND type = 'U')
BEGIN
    CREATE TABLE Warenkorb (
        ID INT PRIMARY KEY IDENTITY,
        Timestamp DATETIME,
        UserID INT FOREIGN KEY REFERENCES Kunden(ID)
    );
END
GO

-- Tabelle: Bestellungen
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Bestellungen' AND type = 'U')
BEGIN
    CREATE TABLE Bestellungen (
        ID INT PRIMARY KEY IDENTITY,
        ProduktID INT FOREIGN KEY REFERENCES Produkte(ID),
        K_GruppeID INT FOREIGN KEY REFERENCES Gruppen(ID),
        WarenkorbID INT FOREIGN KEY REFERENCES Warenkorb(ID),
        ProduktAnzahl INT
    );
END
GO

-- Tabelle: Product_Recommendations
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Product_Recommendations' AND type = 'U')
BEGIN
    CREATE TABLE Product_Recommendations (
        ID INT PRIMARY KEY IDENTITY,
        UserID INT FOREIGN KEY REFERENCES Kunden(ID),
        ProduktID INT FOREIGN KEY REFERENCES Produkte(ID),
        KundenGruppeID INT FOREIGN KEY REFERENCES Gruppen(ID)
    );
END
GO

-- Tabelle: Group_Product_Rules
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Group_Product_Rules' AND type = 'U')
BEGIN
    CREATE TABLE Group_Product_Rules (
        ID INT PRIMARY KEY IDENTITY,
        GruppeID INT FOREIGN KEY REFERENCES Gruppen(ID),
        ProduktID INT FOREIGN KEY REFERENCES Produkte(ID),
        Confidence FLOAT
    );
END
GO

-- Views erstellen
-- --------------------------

-IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'GruppenKaufverhalten')
    EXEC('CREATE VIEW GruppenKaufverhalten AS
    SELECT 
        g.Name AS Gruppenname,
        p.Name AS Produktname,
        COUNT(*) AS AnzahlBestellungen
    FROM Bestellungen b
    JOIN Produkte p ON b.ProduktID = p.ID
    JOIN Gruppen g ON b.K_GruppeID = g.ID
    GROUP BY g.Name, p.Name;');
GO

-- View: KundenWarenkoerbe
IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'KundenWarenkoerbe')
    EXEC('CREATE VIEW KundenWarenkoerbe AS
    SELECT 
        k.ID AS KundenID,
        k.Name AS Kundenname,
        w.ID AS WarenkorbID,
        w.Timestamp,
        p.Name AS Produktname,
        b.ProduktAnzahl
    FROM Kunden k
    JOIN Warenkorb w ON k.ID = w.UserID
    JOIN Bestellungen b ON w.ID = b.WarenkorbID
    JOIN Produkte p ON b.ProduktID = p.ID;');
GO

-- View: KundenMitGruppennamen
IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'KundenMitGruppennamen')
    EXEC('CREATE VIEW KundenMitGruppennamen AS
    SELECT 
        k.ID AS KundenID,
        k.Name AS Kundenname,
        g.Name AS Gruppenname
    FROM Kunden k
    JOIN Gruppen g ON k.GruppeID = g.ID;');
GO

-- View: KundenEmpfehlungen
IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'KundenEmpfehlungen')
    EXEC('CREATE VIEW KundenEmpfehlungen AS
    SELECT 
        k.Name AS Kundenname,
        g.Name AS Gruppenname,
        p.Name AS Produktname
    FROM Product_Recommendations pr
    JOIN Kunden k ON pr.UserID = k.ID
    JOIN Gruppen g ON pr.KundenGruppeID = g.ID
    JOIN Produkte p ON pr.ProduktID = p.ID;');
GO

-- View: Transaktionen
IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'Transaktionen')
    EXEC('CREATE VIEW Transaktionen AS
    SELECT 
        w.ID AS WarenkorbID,
        b.ProduktID
    FROM Bestellungen b
    JOIN Warenkorb w ON b.WarenkorbID = w.ID;');
GO

-- --------------------------
-- 3. Produktdaten befüllen
-- --------------------------
IF NOT EXISTS (SELECT * FROM Produkte WHERE Name = 'Nähset')   
BEGIN
    INSERT INTO Produkte (Name, Beschreibung, Bestand, Hersteller) 
    VALUES
    ('Nähset', 'Komplettes Set für Handarbeitsfans mit Garn, Nadeln und Anleitung. Nein keine Keksdose', 150, 'SewCraft'),
    ('Mehl', 'Weizenmehl Type 405, ideal für Backen und Kochen', 500, 'Müller Mühle'),
    ('Holy Energy', 'Zuckerfreier Performance-Drink für Gamer und Studenten', 300, 'Holy GmbH'),
    ('Controller', 'Kabelloser Controller, kompatibel mit PS5, Xbox und PC', 200, 'GameTech'),
    ('Tik Tok Mystery Box', 'Überraschungsbox mit viralen TikTok-Gadgets', 120, 'TrendBox Inc.'),
    ('Pokemon Karten', 'Booster Pack mit 10 zufälligen Pokémon-Karten', 400, 'The Pokémon Company'),
    ('Pennergranate', 'Fertig gemischter alkoholischer Drink für zwischendurch', 80, 'StraßenPower'),
    ('Vodka', 'Klarer Wodka, 40% Vol., 0,7L Flasche', 250, 'Gorbatschow'),
    ('Sangria', 'Spanischer Fruchtwein mit Zitrusnote, 1L', 150, 'Casa España'),
    ('Mugler Alien', 'Intensives Eau de Parfum mit Jasmin-Note', 100, 'Thierry Mugler'),
    ('I Phone 16 Pro Max', 'Apple Smartphone mit 1TB Speicher und A18 Chip', 50, 'Apple'),
    ('Kaffeetasse', 'Keramiktasse mit lustigem Spruch', 300, 'MugLife'),
    ('Gaming Maus', 'Ergonomische Gaming-Maus mit RGB-Beleuchtung', 150, 'GameTech'),
    ('Kopfhörer', 'Noise-Cancelling Over-Ear Kopfhörer', 80, 'SoundMaster'),
    ('Laptop Rucksack', 'Wasserabweisender Rucksack für Laptops bis 15 Zoll', 200, 'TechGear'),
    ('Fitness Tracker', 'Smartwatch mit Herzfrequenzmessung und GPS', 120, 'FitTrack'),
    ('Bluetooth Lautsprecher', 'Tragbarer Lautsprecher mit 20 Stunden Akkulaufzeit', 90, 'SoundWave');
END
GO

-- --------------------------
-- 4. Kundengruppen befüllen
-- --------------------------
IF NOT EXISTS (SELECT * FROM Gruppen WHERE Name = 'Oma')   
BEGIN
    INSERT INTO Gruppen (Name) VALUES
    ('Oma'),
    ('Gamer'),
    ('Kind'),
    ('Alkoholiker'),
    ('Rich Kid');
END
GO

-- --------------------------
-- 5. Fülle Group_Product_Rules
-- --------------------------
DELETE FROM Group_Product_Rules;

INSERT INTO Group_Product_Rules (GruppeID, ProduktID, Confidence)
SELECT 
    g.ID AS GruppeID,
    p.ID AS ProduktID,
    CASE g.Name
        WHEN 'Oma' THEN a.Oma
        WHEN 'Gamer' THEN a.Gamer
        WHEN 'Kind' THEN a.Kind
        WHEN 'Alkoholiker' THEN a.Alkoholiker
        WHEN 'Rich Kid' THEN a.[Rich Kids]
    END AS Confidence
FROM (
    VALUES
        ('Mehl', 0.95, 0, 0.05, 0, 0.04),
        ('Holy Energy', 0.01, 0.99, 0, 0, 0.2),
        ('Controller', 0.28, 0.76, 0.43, 0, 0.28),
        ('Tik Tok Mystery Box', 0.44, 0.05, 0.87, 0, 0.44),
        ('Pokemon Karten', 0.45, 0.45, 0.95, 0, 0.32),
        ('Pennergranate', 0, 0.12, 0, 1, 0.02),
        ('Vodka', 0, 0.31, 0, 0.92, 0.01),
        ('Sangria', 0, 0.11, 0, 0.94, 0),
        ('Mugler Alien', 0.12, 0.01, 0, 0, 0.77),
        ('I Phone 16 Pro Max', 0.23, 0.33, 0, 0, 0.91),
        ('Nähset', 0.88, 0, 0, 0, 0.187)
) AS a(Produkt, Oma, Gamer, Kind, Alkoholiker, [Rich Kids])
JOIN Gruppen g ON g.Name IN ('Oma', 'Gamer', 'Kind', 'Alkoholiker', 'Rich Kid')
JOIN Produkte p ON p.Name = a.Produkt;
GO

-- --------------------------
-- 6. Fiktive Kunden anlegen
-- --------------------------
DELETE FROM Kunden;

INSERT INTO Kunden (Name, Adresse, UserID, GruppeID)
VALUES
('Anna Meier', 'Hauptstraße 123', 1, (SELECT ID FROM Gruppen WHERE Name = 'Oma')),
('Lars Schmidt', 'Gamestreet 45', 2, (SELECT ID FROM Gruppen WHERE Name = 'Gamer')),
('Tim Becker', 'Kinderweg 10', 3, (SELECT ID FROM Gruppen WHERE Name = 'Kind')),
('Max Mustermann', 'Barstraße 1', 4, (SELECT ID FROM Gruppen WHERE Name = 'Alkoholiker')),
('Sophia Lux', 'Luxusallee 100', 5, (SELECT ID FROM Gruppen WHERE Name = 'Rich Kid'));
GO

-- --------------------------
-- 7. Generiere Testwarenkörbe und Bestellungen
-- --------------------------
DELETE FROM Bestellungen;
DELETE FROM Warenkorb;
DBCC CHECKIDENT ('Bestellungen', RESEED, 0);
DBCC CHECKIDENT ('Warenkorb', RESEED, 0);

DECLARE @i INT = 1;
WHILE @i <= 300 -- ca. 300 Warenkörbe generieren
BEGIN
    DECLARE @Timestamp DATETIME = DATEADD(MINUTE, -ABS(CAST(CHECKSUM(NEWID()) AS BIGINT) % 10000), GETDATE());
    DECLARE @UserID INT = ABS(CAST(CHECKSUM(NEWID()) AS BIGINT) % 5) + 1;
    DECLARE @GruppeID INT = (SELECT GruppeID FROM Kunden WHERE ID = @UserID);

    INSERT INTO Warenkorb (Timestamp, UserID)
    VALUES (@Timestamp, @UserID);

    DECLARE @WarenkorbID INT = SCOPE_IDENTITY();

    DECLARE @j INT = 1;
    WHILE @j <= ABS(CAST(CHECKSUM(NEWID()) AS BIGINT) % 3) + 1
    BEGIN
        DECLARE @Anzahl INT = ABS(CAST(CHECKSUM(NEWID()) AS BIGINT) % 3) + 1;

        INSERT INTO Bestellungen (ProduktID, K_GruppeID, WarenkorbID, ProduktAnzahl)
        SELECT TOP 1 
            p.ID, 
            @GruppeID, 
            @WarenkorbID,
            @Anzahl
        FROM Produkte p
        JOIN Group_Product_Rules gpr ON gpr.ProduktID = p.ID
        WHERE gpr.GruppeID = @GruppeID
        ORDER BY NEWID() * (1 / (gpr.Confidence + 0.01));

        SET @j = @j + 1;
    END

    SET @i = @i + 1;
END
GO

-- --------------------------
-- 8. Empfehlungen basierend auf häufigen Kombinationen
-- --------------------------
DELETE FROM Product_Recommendations;

-- Wenn ein Kunde "Controller" kauft, empfehle "Holy Energy"
INSERT INTO Product_Recommendations (UserID, ProduktID, KundenGruppeID)
SELECT DISTINCT 
    b.UserID,
    (SELECT ID FROM Produkte WHERE Name = 'Holy Energy'),
    k.GruppeID
FROM Bestellungen b
JOIN Produkte p ON b.ProduktID = p.ID AND p.Name = 'Controller'
JOIN Kunden k ON b.UserID = k.ID;
GO

-- Wenn ein Kunde "Pokemon Karten" kauft, empfehle "Tik Tok Mystery Box"
INSERT INTO Product_Recommendations (UserID, ProduktID, KundenGruppeID)
SELECT DISTINCT 
    b.UserID,
    (SELECT ID FROM Produkte WHERE Name = 'Tik Tok Mystery Box'),
    k.GruppeID
FROM Bestellungen b
JOIN Produkte p ON b.ProduktID = p.ID AND p.Name = 'Pokemon Karten'
JOIN Kunden k ON b.UserID = k.ID;
GO
