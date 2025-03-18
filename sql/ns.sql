drop database if exists ns ;
-- Script MySQL pour la création de la base de données
create database ns;
use ns;

-- Création de la table Contrat
CREATE TABLE Contrat (
                         ID_Contrat INT PRIMARY KEY AUTO_INCREMENT,
                         Annee INT NOT NULL,
                         Statut VARCHAR(50) NOT NULL,
                         DateDebut DATE NOT NULL,
                         DateFin DATE NOT NULL
);

-- Création de la table Appartement
CREATE TABLE Appartement (
                             ID_Appartement INT PRIMARY KEY AUTO_INCREMENT,
                             Nom_Immeuble VARCHAR(100) NOT NULL,
                             Adresse VARCHAR(255) NOT NULL,
                             CP VARCHAR(10) NOT NULL,
                             Ville VARCHAR(100) NOT NULL,
                             Exposition VARCHAR(50),
                             Surface_Habitable DECIMAL(10, 2),
                             Surface_Balcon DECIMAL(10, 2),
                             Capacite_Accueil INT,
                             Distance_Pistes DECIMAL(10, 2)
);

-- Création de la table Client
CREATE TABLE Client (
                        ID_Locataire INT PRIMARY KEY AUTO_INCREMENT,
                        Nom VARCHAR(100) NOT NULL,
                        Prenom VARCHAR(100) NOT NULL,
                        Adresse VARCHAR(255) NOT NULL,
                        Telephone VARCHAR(15)
);

-- Création de la table Utilisateur
CREATE TABLE Utilisateur (
                             ID_Utilisateur INT PRIMARY KEY AUTO_INCREMENT,
                             Email VARCHAR(100) NOT NULL UNIQUE,
                             Mot_De_Passe VARCHAR(255) NOT NULL,
                             Type_Compte VARCHAR(50) NOT NULL
);

-- Création de la table Proprietaire
CREATE TABLE Proprietaire (
                              ID_Proprietaire INT PRIMARY KEY AUTO_INCREMENT,
                              Nom VARCHAR(100) NOT NULL,
                              Prenom VARCHAR(100) NOT NULL,
                              Adresse VARCHAR(255) NOT NULL,
                              Telephone VARCHAR(15),
                              RIB VARCHAR(34) NOT NULL
);

-- Création de la table Station
CREATE TABLE Station (
                         ID_Station INT PRIMARY KEY AUTO_INCREMENT,
                         Nom VARCHAR(100) NOT NULL,
                         TelephoneMairie VARCHAR(15),
                         Nombre_Habitant INT,
                         Nombre_Touriste_Ete INT,
                         Nombre_Touriste_Hiver INT
);

-- Création de la table Equipement
CREATE TABLE Equipement (
                            ID_Equipement INT PRIMARY KEY AUTO_INCREMENT,
                            Type_Equipement VARCHAR(100) NOT NULL,
                            Details VARCHAR(255)
);

-- Création de la table Tarif
CREATE TABLE Tarif (
                       ID_Tarif INT PRIMARY KEY AUTO_INCREMENT,
                       Saison VARCHAR(50) NOT NULL,
                       Montant DECIMAL(10, 2) NOT NULL
);

-- Création de la table Reservation
CREATE TABLE Reservation (
                             ID_Reservation INT PRIMARY KEY AUTO_INCREMENT,
                             DateDebut DATE NOT NULL,
                             DateFin DATE NOT NULL,
                             Statut VARCHAR(50) NOT NULL,
                             Montant_Total DECIMAL(10, 2) NOT NULL,
                             Acompte DECIMAL(10, 2),
                             SoldePaye DECIMAL(10, 2),
                             CautionVersee DECIMAL(10, 2)
);

-- Relations et contraintes
-- Relation Appartement - Station (Se Situe)
ALTER TABLE Appartement ADD COLUMN ID_Station INT,
                        ADD CONSTRAINT FK_Appartement_Station FOREIGN KEY (ID_Station) REFERENCES Station(ID_Station);

-- Relation Appartement - Equipement (Equipe)
CREATE TABLE Appartement_Equipement (
                                        ID_Appartement INT,
                                        ID_Equipement INT,
                                        PRIMARY KEY (ID_Appartement, ID_Equipement),
                                        FOREIGN KEY (ID_Appartement) REFERENCES Appartement(ID_Appartement),
                                        FOREIGN KEY (ID_Equipement) REFERENCES Equipement(ID_Equipement)
);

-- Relation Appartement - Tarif (Affiche)
ALTER TABLE Appartement ADD COLUMN ID_Tarif INT,
                        ADD CONSTRAINT FK_Appartement_Tarif FOREIGN KEY (ID_Tarif) REFERENCES Tarif(ID_Tarif);

-- Relation Reservation - Client (Effectuer)
ALTER TABLE Reservation ADD COLUMN ID_Locataire INT,
                        ADD CONSTRAINT FK_Reservation_Client FOREIGN KEY (ID_Locataire) REFERENCES Client(ID_Locataire);

-- Relation Appartement - Proprietaire (Appartient)
ALTER TABLE Appartement ADD COLUMN ID_Proprietaire INT,
                        ADD CONSTRAINT FK_Appartement_Proprietaire FOREIGN KEY (ID_Proprietaire) REFERENCES Proprietaire(ID_Proprietaire);

-- Relation Contrat - Appartement (Relier)
ALTER TABLE Contrat ADD COLUMN ID_Appartement INT,
                    ADD CONSTRAINT FK_Contrat_Appartement FOREIGN KEY (ID_Appartement) REFERENCES Appartement(ID_Appartement);

-- Relation Utilisateur - Proprietaire/Client (Etre)
-- Table pour gérer les relations multiples
CREATE TABLE Utilisateur_Role (
                                  ID_Utilisateur INT,
                                  Role VARCHAR(50),
                                  PRIMARY KEY (ID_Utilisateur, Role),
                                  FOREIGN KEY (ID_Utilisateur) REFERENCES Utilisateur(ID_Utilisateur)
);

create table image(
    idImage int not null auto_increment,
    ID_Appartement int not null,
    image varchar(255) not null,
    CONSTRAINT pk_image PRIMARY KEY (idImage),
    CONSTRAINT fk_image_user FOREIGN KEY (ID_Appartement) REFERENCES appartement(ID_Appartement)
)


select * from utilisateur
insert into user values (null, "Adel","Tristan","a@gmail.com", 
"123", "admin"); 

insert into user values (null, "Ryles","Youghorta","b@gmail.com", 
"456", "user"); 



















