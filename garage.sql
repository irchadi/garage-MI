-- Création de la base de données
CREATE DATABASE garage_v_parrot;

-- Utilisation de la base de données
USE garage_v_parrot;

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    email VARCHAR(255),
    mot_de_passe VARCHAR(255),
    type ENUM('admin', 'employe')
);

-- Table des horaires
CREATE TABLE horaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour_semaine VARCHAR(20),
    heure_ouverture TIME,
    heure_fermeture TIME
);

-- Table des vehicules_occasion
CREATE TABLE vehicules_occasion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(255),
    modele VARCHAR(255),
    prix DECIMAL(10,2),
    annee_mise_en_circulation INT,
    kilometrage INT,
    description TEXT,
    caracteristiques_techniques TEXT,
    equipements_options TEXT,
    image_principale VARCHAR(255)
);

-- Table des temoignages
CREATE TABLE temoignages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_client VARCHAR(255),
    commentaire TEXT,
    note INT,
    date_temoignage DATETIME,
    approuve BOOLEAN
);

-- Table des contacts
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    email VARCHAR(255),
    numero_telephone VARCHAR(20),
    message TEXT,
    date_soumission DATETIME,
    annonce_associee VARCHAR(255)
);

-- Insertion d'un compte administrateur
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type) 
VALUES ('Vincent', 'Parrot', 'vincent.parrot@outlook.fr', 'mot_de_passe_employe', 'employe');

-- Insertion d'un compte employé
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type) 
VALUES ('Miradji', 'Irchadi', 'irchadi3@hotmail.fr', 'mot_de_passe_employe', 'employe');

-- Insertion des des horaires d'ouverture du garage
INSERT INTO horaires (jour_semaine, heure_ouverture, heure_fermeture) 
VALUES ('Lundi', '08:00:00', '18:00:00');

-- Insertion des informations sur les véhicules d'occasion 
INSERT INTO vehicules_occasion (marque, modele, prix, annee_mise_en_circulation, kilometrage, description, caracteristiques_techniques, equipements_options, image_principale) 
VALUES ('Fiat', '500', 15000.00, 2018, 50000, 'Description du véhicule', 'Caractéristiques techniques', 'Équipements et options', 'lien_image.jpg');

-- Insertion des témoignages
INSERT INTO temoignages (nom_client, commentaire, note, date_temoignage, approuve) 
VALUES ('NomClient', 'Excellent service!', 5, NOW(), true);

-- Pour enregistrer les informations de contact des visiteurs 
INSERT INTO contacts (nom, prenom, email, numero_telephone, message, date_soumission, annonce_associee) 
VALUES ('NomContact', 'PrenomContact', 'email_contact@example.com', '123456789', 'Message du visiteur', NOW(), 'AnnonceAssociee');

INSERT INTO vehicules_occasion (marque, modele, prix, annee_mise_en_circulation, kilometrage, description, caracteristiques_techniques, equipements_options, image_principale) 
VALUES 
('Toyota', 'Corolla', 15000.00, 2017, 60000, 'Belle voiture compacte en excellent état', 'Moteur 4 cylindres, 1.8L, automatique', 'Climatisation, caméra de recul, régulateur de vitesse', 'toyota_corolla.jpg'),
('Volkswagen', 'Golf', 18000.00, 2016, 50000, 'Volkswagen Golf en bon état général', 'Moteur 4 cylindres, 2.0L, boîte manuelle', 'Climatisation, système audio haut de gamme', 'volkswagen_golf.jpg'),
('Ford', 'Focus', 12000.00, 2018, 40000, 'Ford Focus bien entretenue', 'Moteur 4 cylindres, 1.6L, boîte automatique', 'Climatisation, vitres électriques, régulateur de vitesse', 'ford_focus.jpg'),
('Renault', 'Clio', 10000.00, 2019, 30000, 'Renault Clio compacte et économique', 'Moteur 3 cylindres, 1.2L, boîte manuelle', 'Climatisation, direction assistée, ABS', 'renault_clio.jpg'),
('Peugeot', '208', 11000.00, 2018, 35000, 'Peugeot 208 sportive et élégante', 'Moteur 4 cylindres, 1.4L, boîte automatique', 'Climatisation, régulateur de vitesse, phares LED', 'peugeot_208.jpg'),
('Audi', 'A3', 22000.00, 2017, 40000, 'Audi A3 luxueuse et performante', 'Moteur 4 cylindres, 2.0L, boîte automatique', 'Climatisation automatique, système de navigation, cuir', 'audi_a3.jpg'),
('BMW', '3 Series', 25000.00, 2018, 45000, 'BMW Série 3 élégante et dynamique', 'Moteur 6 cylindres, 3.0L, boîte automatique', 'Toit ouvrant panoramique, système audio Harman/Kardon', 'bmw_3series.jpg'),
('Mercedes-Benz', 'C-Class', 28000.00, 2019, 30000, 'Mercedes-Benz Classe C haut de gamme', 'Moteur 4 cylindres, 2.0L, boîte automatique', 'Climatisation, système audio Burmester, sellerie en cuir', 'mercedes_cclass.jpg');


INSERT INTO temoignages (nom_client, commentaire, note, date_temoignage, approuve) 
VALUES 
('Jean Dupont', 'Excellent service client, je recommande vivement ce garage!', 5, '2023-08-15 10:30:00', true),
('Marie Martin', 'Très satisfait de mon expérience avec ce garage. Le personnel est compétent et aimable.', 4, '2023-09-02 14:45:00', true),
('Pierre Leclerc', 'Je suis déçu par la qualité du service. Les réparations ont pris plus de temps que prévu.', 2, '2023-10-10 11:20:00', true),
('Sophie Dubois', 'Le meilleur garage de la ville! Des prix compétitifs et un travail de qualité.', 5, '2023-11-05 09:15:00', true),
('Nicolas Petit', 'Je suis très content de mon achat chez ce garage. La voiture est en excellent état.', 5, '2023-12-20 16:00:00', true);

INSERT INTO vehicules_occasion (marque, modele, prix, annee_mise_en_circulation, kilometrage, image_principale)
VALUES ('Toyota', 'Corolla', 15000, 2018, 50000, 'assets\img\1200px-Toyota_Corolla_1.8_VVT-i_Hybrid_Team_Deutschland_XII_–_f_03012021-1024x521.jpg');

UPDATE vehicules_occasion
SET image_principale = 'assets\img\1200px-Toyota_Corolla_1.8_VVT-i_Hybrid_Team_Deutschland_XII_–_f_03012021-1024x521.jpg'
WHERE id = 2;

UPDATE vehicules_occasion
SET image_principale = 'assets\img\VW-Golf-7-R-Frontansicht-Weiß-1536x796.jpg'
WHERE id = 3;

UPDATE `vehicules_occasion` SET `image_principale` = 'assets\\img\\1200px-2018_Ford_Focus_ST-Line_EcoBoost_1.0.jpg' WHERE `vehicules_occasion`.`id` = 4;

UPDATE `vehicules_occasion` SET `image_principale` = 'assets\\img\\Fiat_500_front-1.jpg' WHERE `vehicules_occasion`.`id` = 1;

UPDATE `vehicules_occasion` SET `image_principale` = 'assets\\img\\Renault_Clio_Kfz_Steuer-scaled.jpg' WHERE `vehicules_occasion`.`id` = 5;

UPDATE `vehicules_occasion` SET `image_principale` = 'assets\\img\\FC921QK-1.jpg' WHERE `vehicules_occasion`.`id` = 6;

DELETE FROM vehicules_occasion
WHERE id >= 10 AND id <= 18;













