-- --------------------------
-- 1. Login-Datenbank erstellen
-- --------------------------
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

IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Gruppen' AND type = 'U')
BEGIN
    CREATE TABLE Gruppen (
        ID INT PRIMARY KEY IDENTITY,
        Name NVARCHAR(255)
    );
END
GO

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

IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Warenkorb' AND type = 'U')
BEGIN
    CREATE TABLE Warenkorb (
        ID INT PRIMARY KEY IDENTITY,
        Timestamp DATETIME,
        UserID INT FOREIGN KEY REFERENCES Kunden(ID)
    );
END
GO

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

IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'Product_Combinations' AND type = 'U')
BEGIN
    CREATE TABLE Product_Combinations (
        ID INT IDENTITY(1,1) PRIMARY KEY,
        GruppeID INT NOT NULL,
        Produkt1ID INT NOT NULL,
        Produkt2ID INT NOT NULL,
        Wahrscheinlichkeit FLOAT NOT NULL,
        FOREIGN KEY (GruppeID) REFERENCES Gruppen(ID),
        FOREIGN KEY (Produkt1ID) REFERENCES Produkte(ID),
        FOREIGN KEY (Produkt2ID) REFERENCES Produkte(ID)
    );
END
GO


IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'GruppenKaufverhalten')
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

IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'KundenMitGruppennamen')
    EXEC('CREATE VIEW KundenMitGruppennamen AS
    SELECT 
        k.ID AS KundenID,
        k.Name AS Kundenname,
        g.Name AS Gruppenname
    FROM Kunden k
    JOIN Gruppen g ON k.GruppeID = g.ID;');
GO


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


IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'Transaktionen')
    EXEC('CREATE VIEW Transaktionen AS
    SELECT 
        w.ID AS WarenkorbID,
        b.ProduktID
    FROM Bestellungen b
    JOIN Warenkorb w ON b.WarenkorbID = w.ID;');
GO


IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'AlleGruppen')
BEGIN
    EXEC('CREATE VIEW AlleGruppen AS
          SELECT ID, Name FROM Gruppen;');
END
GO

IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'AlleProdukte')
BEGIN
    EXEC('CREATE VIEW AlleProdukte AS
          SELECT ID, Name, Hersteller, Bestand, Beschreibung FROM Produkte;');
END
GO


IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'ProductCombinationAnalysis')
BEGIN
    EXEC('CREATE VIEW ProductCombinationAnalysis AS
    SELECT 
        g.Name AS Gruppenname,
        p1.Name AS Produkt1,
        p2.Name AS Produkt2,
        pc.Wahrscheinlichkeit
    FROM Product_Combinations pc
    JOIN Gruppen g ON pc.GruppeID = g.ID
    JOIN Produkte p1 ON pc.Produkt1ID = p1.ID
    JOIN Produkte p2 ON pc.Produkt2ID = p2.ID;');
END
GO


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


DELETE FROM Kunden;
INSERT INTO Kunden (Name, Adresse, UserID, GruppeID)
VALUES
('Anna Meier', 'Hauptstraße 123', 1, (SELECT ID FROM Gruppen WHERE Name = 'Oma')),
('Lars Schmidt', 'Gamestreet 45', 2, (SELECT ID FROM Gruppen WHERE Name = 'Gamer')),
('Tim Becker', 'Kinderweg 10', 3, (SELECT ID FROM Gruppen WHERE Name = 'Kind')),
('Max Mustermann', 'Barstraße 1', 4, (SELECT ID FROM Gruppen WHERE Name = 'Alkoholiker')),
('Sophia Lux', 'Luxusallee 100', 5, (SELECT ID FROM Gruppen WHERE Name = 'Rich Kid'));
GO


DELETE FROM Bestellungen;
DELETE FROM Warenkorb;
DBCC CHECKIDENT ('Bestellungen', RESEED, 0);
DBCC CHECKIDENT ('Warenkorb', RESEED, 0);

DECLARE @i INT = 1;
WHILE @i <= 300 
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
        ORDER BY CHECKSUM(NEWID()) * (1 / (gpr.Confidence + 0.01));
        SET @j = @j + 1;
    END
    SET @i = @i + 1;
END
GO


DELETE FROM Product_Recommendations;


INSERT INTO Product_Recommendations (UserID, ProduktID, KundenGruppeID)
SELECT DISTINCT 
    k.ID,
    (SELECT ID FROM Produkte WHERE Name = 'Holy Energy'),
    k.GruppeID
FROM Kunden k
JOIN Warenkorb w ON k.ID = w.UserID
JOIN Bestellungen b ON w.ID = b.WarenkorbID
JOIN Produkte p ON b.ProduktID = p.ID AND p.Name = 'Controller';

INSERT INTO Product_Recommendations (UserID, ProduktID, KundenGruppeID)
SELECT DISTINCT 
    k.ID,
    (SELECT ID FROM Produkte WHERE Name = 'Tik Tok Mystery Box'),
    k.GruppeID
FROM Kunden k
JOIN Warenkorb w ON k.ID = w.UserID
JOIN Bestellungen b ON w.ID = b.WarenkorbID
JOIN Produkte p ON b.ProduktID = p.ID AND p.Name = 'Pokemon Karten';
GO


DELETE FROM Product_Combinations;
DBCC CHECKIDENT ('Product_Combinations', RESEED, 0);
GO

INSERT INTO Product_Combinations (GruppeID, Produkt1ID, Produkt2ID, Wahrscheinlichkeit)
SELECT 
    g.ID AS GruppeID,
    p1.ID AS Produkt1ID,
    p2.ID AS Produkt2ID,
    c.Wahrscheinlichkeit
FROM (
    VALUES
        ('Gamer', 'Holy Energy', 'Mehl', 0.05),
        ('Gamer', 'Holy Energy', 'Controller', 0.746),
        ('Gamer', 'Holy Energy', 'Tik Tok Mystery Box', 0.115),
        ('Gamer', 'Holy Energy', 'Pokemon Karten', 0.602),
        ('Gamer', 'Holy Energy', 'Pennergranate', 0.097),
        ('Gamer', 'Holy Energy', 'Vodka', 0.287),
        ('Gamer', 'Holy Energy', 'Sangria', 0.268),
        ('Gamer', 'Holy Energy', 'Mugler Alien', 0.087),
        ('Gamer', 'Holy Energy', 'I Phone 16 Pro Max', 0.283),
        ('Gamer', 'Holy Energy', 'Nähset', 0.054),
        ('Gamer', 'Controller', 'Mehl', 0),
        ('Gamer', 'Controller', 'Holy Energy', 0.943),
        ('Gamer', 'Controller', 'Tik Tok Mystery Box', 0.074),
        ('Gamer', 'Controller', 'Pokemon Karten', 0.259),
        ('Gamer', 'Controller', 'Pennergranate', 0),
        ('Gamer', 'Controller', 'Vodka', 0.254),
        ('Gamer', 'Controller', 'Sangria', 0.009),
        ('Gamer', 'Controller', 'Mugler Alien', 0.041),
        ('Gamer', 'Controller', 'I Phone 16 Pro Max', 0.239),
        ('Gamer', 'Controller', 'Nähset', 0),
        ('Gamer', 'Pokemon Karten', 'Mehl', 0.147),
        ('Gamer', 'Pokemon Karten', 'Holy Energy', 0.967),
        ('Gamer', 'Pokemon Karten', 'Controller', 0.767),
        ('Gamer', 'Pokemon Karten', 'Tik Tok Mystery Box', 0),
        ('Gamer', 'Pokemon Karten', 'Pennergranate', 0.066),
        ('Gamer', 'Pokemon Karten', 'Vodka', 0.321),
        ('Gamer', 'Pokemon Karten', 'Sangria', 0),
        ('Gamer', 'Pokemon Karten', 'Mugler Alien', 0.048),
        ('Gamer', 'Pokemon Karten', 'I Phone 16 Pro Max', 0.27),
        ('Gamer', 'Pokemon Karten', 'Nähset', 0),
        ('Kind', 'Tik Tok Mystery Box', 'Mehl', 0),
        ('Kind', 'Tik Tok Mystery Box', 'Holy Energy', 0.185),
        ('Kind', 'Tik Tok Mystery Box', 'Controller', 0.429),
        ('Kind', 'Tik Tok Mystery Box', 'Pokemon Karten', 0.844),
        ('Kind', 'Tik Tok Mystery Box', 'Pennergranate', 0.082),
        ('Kind', 'Tik Tok Mystery Box', 'Vodka', 0),
        ('Kind', 'Tik Tok Mystery Box', 'Sangria', 0.021),
        ('Kind', 'Tik Tok Mystery Box', 'Mugler Alien', 0),
        ('Kind', 'Tik Tok Mystery Box', 'I Phone 16 Pro Max', 0),
        ('Kind', 'Tik Tok Mystery Box', 'Nähset', 0.02),
        ('Kind', 'Pokemon Karten', 'Mehl', 0.124),
        ('Kind', 'Pokemon Karten', 'Holy Energy', 0.017),
        ('Kind', 'Pokemon Karten', 'Controller', 0.418),
        ('Kind', 'Pokemon Karten', 'Tik Tok Mystery Box', 0.84),
        ('Kind', 'Pokemon Karten', 'Pennergranate', 0),
        ('Kind', 'Pokemon Karten', 'Vodka', 0),
        ('Kind', 'Pokemon Karten', 'Sangria', 0),
        ('Kind', 'Pokemon Karten', 'Mugler Alien', 0.106),
        ('Kind', 'Pokemon Karten', 'I Phone 16 Pro Max', 0.034),
        ('Kind', 'Pokemon Karten', 'Nähset', 0),
        ('Kind', 'Controller', 'Mehl', 0.082),
        ('Kind', 'Controller', 'Holy Energy', 0),
        ('Kind', 'Controller', 'Tik Tok Mystery Box', 0.802),
        ('Kind', 'Controller', 'Pokemon Karten', 1),
        ('Kind', 'Controller', 'Pennergranate', 0.103),
        ('Kind', 'Controller', 'Vodka', 0.093),
        ('Kind', 'Controller', 'Sangria', 0),
        ('Kind', 'Controller', 'Mugler Alien', 0),
        ('Kind', 'Controller', 'I Phone 16 Pro Max', 0.033),
        ('Kind', 'Controller', 'Nähset', 0.098),
        ('Alkoholiker', 'Pennergranate', 'Vodka', 1),
        ('Alkoholiker', 'Pennergranate', 'Sangria', 0.933),
        ('Alkoholiker', 'Pennergranate', 'Mugler Alien', 0.1),
        ('Alkoholiker', 'Pennergranate', 'I Phone 16 Pro Max', 0.036),
        ('Alkoholiker', 'Pennergranate', 'Pokemon Karten', 0.081),
        ('Alkoholiker', 'Pennergranate', 'Mehl', 0),
        ('Alkoholiker', 'Pennergranate', 'Holy Energy', 0),
        ('Alkoholiker', 'Pennergranate', 'Controller', 0),
        ('Alkoholiker', 'Pennergranate', 'Tik Tok Mystery Box', 0),
        ('Alkoholiker', 'Pennergranate', 'Nähset', 0),
        ('Alkoholiker', 'Sangria', 'Vodka', 0.929),
        ('Alkoholiker', 'Sangria', 'Pennergranate', 1),
        ('Alkoholiker', 'Sangria', 'Holy Energy', 0.154),
        ('Alkoholiker', 'Sangria', 'Mugler Alien', 0),
        ('Alkoholiker', 'Sangria', 'I Phone 16 Pro Max', 0.009),
        ('Alkoholiker', 'Sangria', 'Mehl', 0.036),
        ('Alkoholiker', 'Sangria', 'Pokemon Karten', 0),
        ('Alkoholiker', 'Sangria', 'Controller', 0),
        ('Alkoholiker', 'Sangria', 'Tik Tok Mystery Box', 0.156),
        ('Alkoholiker', 'Sangria', 'Nähset', 0),
        ('Alkoholiker', 'Vodka', 'Pennergranate', 0.95),
        ('Alkoholiker', 'Vodka', 'Sangria', 1),
        ('Alkoholiker', 'Vodka', 'Holy Energy', 0.036),
        ('Alkoholiker', 'Vodka', 'I Phone 16 Pro Max', 0),
        ('Alkoholiker', 'Vodka', 'Nähset', 0.051),
        ('Alkoholiker', 'Vodka', 'Mehl', 0),
        ('Alkoholiker', 'Vodka', 'Pokemon Karten', 0),
        ('Alkoholiker', 'Vodka', 'Controller', 0.148),
        ('Alkoholiker', 'Vodka', 'Tik Tok Mystery Box', 0),
        ('Alkoholiker', 'Vodka', 'Mugler Alien', 0.033),
        ('Rich Kid', 'Mugler Alien', 'Holy Energy', 0.297),
        ('Rich Kid', 'Mugler Alien', 'Controller', 0.21),
        ('Rich Kid', 'Mugler Alien', 'Tik Tok Mystery Box', 0.407),
        ('Rich Kid', 'Mugler Alien', 'Pokemon Karten', 0.281),
        ('Rich Kid', 'Mugler Alien', 'I Phone 16 Pro Max', 0.911),
        ('Rich Kid', 'Mugler Alien', 'Pennergranate', 0),
        ('Rich Kid', 'Mugler Alien', 'Vodka', 0.04),
        ('Rich Kid', 'Mugler Alien', 'Sangria', 0.026),
        ('Rich Kid', 'Mugler Alien', 'Mehl', 0.05),
        ('Rich Kid', 'Mugler Alien', 'Nähset', 0.164),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Mugler Alien', 0.787),
        ('Rich Kid', 'Tik Tok Mystery Box', 'I Phone 16 Pro Max', 0.936),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Holy Energy', 0.158),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Controller', 0.246),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Pokemon Karten', 0.24),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Pennergranate', 0.004),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Vodka', 0.05),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Sangria', 0.189),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Mehl', 0),
        ('Rich Kid', 'Tik Tok Mystery Box', 'Nähset', 0.18),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Mugler Alien', 0.884),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Tik Tok Mystery Box', 0.686),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Holy Energy', 0.197),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Controller', 0.286),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Pokemon Karten', 0.301),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Pennergranate', 0.05),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Vodka', 0.007),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Sangria', 0),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Mugler Alien', 0.884),
        ('Rich Kid', 'I Phone 16 Pro Max', 'Nähset', 0.262)
) AS c(GruppeName, Produkt1Name, Produkt2Name, Wahrscheinlichkeit)
JOIN Gruppen g ON g.Name = c.GruppeName
JOIN Produkte p1 ON p1.Name = c.Produkt1Name
JOIN Produkte p2 ON p2.Name = c.Produkt2Name;
GO