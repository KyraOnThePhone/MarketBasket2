
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'Login')
BEGIN
    CREATE DATABASE Login;
END
GO

USE Login;
GO


IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'roles' AND type = 'U')
BEGIN
    CREATE TABLE roles (
        id INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
        role_name VARCHAR(50) NOT NULL UNIQUE
    );
END
GO


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

IF NOT EXISTS (SELECT * FROM roles WHERE role_name = 'admin')
BEGIN
    INSERT INTO roles (role_name) VALUES ('admin'), ('dev'), ('user');
END
GO

IF NOT EXISTS (SELECT * FROM Accounts WHERE username = 'IN23')
BEGIN
    DECLARE @role_id INT;
    SELECT @role_id = id FROM roles WHERE role_name = 'dev';

    INSERT INTO Accounts (username, password, role_id)
    VALUES ('IN23', '$2y$10$MWYm7RKEVJ8Us7S1S4j/n.l4yEDQzytDMH15PCFT0YZvYGc7nqUnC', @role_id);
END
GO

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


IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'GruppenKaufverhalten')
BEGIN
    EXEC('
    CREATE VIEW GruppenKaufverhalten AS
    SELECT 
        g.Name AS Gruppenname,
        p.Name AS Produktname,
        COUNT(*) AS AnzahlBestellungen
    FROM Bestellungen b
    JOIN Produkte p ON b.ProduktID = p.ID
    JOIN Gruppen g ON b.K_GruppeID = g.ID
    GROUP BY g.Name, p.Name;
    ');
END
GO

IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'KundenWarenkoerbe')
BEGIN
    EXEC('
    CREATE VIEW KundenWarenkoerbe AS
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
    JOIN Produkte p ON b.ProduktID = p.ID;
    ');
END
GO

IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'KundenMitGruppennamen')
BEGIN
    EXEC('
    CREATE VIEW KundenMitGruppennamen AS
    SELECT 
        k.ID AS KundenID,
        k.Name AS Kundenname,
        g.Name AS Gruppenname
    FROM Kunden k
    JOIN Gruppen g ON k.GruppeID = g.ID;
    ');
END
GO

IF NOT EXISTS (SELECT * FROM sys.views WHERE name = 'KundenEmpfehlungen')
BEGIN
    EXEC('
    CREATE VIEW KundenEmpfehlungen AS
    SELECT 
        k.Name AS Kundenname,
        g.Name AS Gruppenname,
        p.Name AS Produktname
    FROM Product_Recommendations pr
    JOIN Kunden k ON pr.UserID = k.ID
    JOIN Gruppen g ON pr.KundenGruppeID = g.ID
    JOIN Produkte p ON pr.ProduktID = p.ID;
    ');
END
GO