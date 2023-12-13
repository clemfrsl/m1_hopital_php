DROP TABLE IF EXISTS Patients; 
DROP TABLE IF EXISTS Pays;
DROP TABLE IF EXISTS Motifs; 
DROP TABLE IF EXISTS Sexe;

CREATE TABLE Patients ( 
	CodePatients INT, 
	Nom VARCHAR(255), 
	Prénom VARCHAR(255), 
	Sexe VARCHAR(1) REFERENCES Sexe(CodeSexe), 
	DateNaissance date, 
	Sécu VARCHAR(255), 
	CodePays VARCHAR(2) REFERENCES Pays(CodePays), 
	dateEntrée date, 
	CodeMotif int REFERENCES Motifs(CodeMotifs), 
	PRIMARY KEY(CodePatients));
	
	
	
Create table Pays (CodePays VARCHAR(2), Libellé VARCHAR(255), primary key(CodePays));
Create table Motifs (CodeMotifs INT, Libellé VARCHAR(255), primary key(CodeMotifs));
Create table Sexe (CodeSexe VARCHAR(1), Libellé VARCHAR(255), primary key(CodeSexe));
Create table Media (CodeMedia INT AS PRIMARY KEY, CodePatients INT REFERENCES Patients(CodePatients), TypeMedia VARCHAR(10), URLMedia VARCHAR(50), DateEnregistrement Date);




INSERT INTO Pays VALUES('FR', 'France');
INSERT INTO Pays VALUES('BE', 'Belgique');
INSERT INTO Pays VALUES('MA', 'Maroc');
INSERT INTO Pays VALUES('TN', 'Tunisie');
INSERT INTO Pays VALUES('DZ', 'Algérie');

INSERT INTO Motifs VALUES(1, 'Consultation');
INSERT INTO Motifs VALUES(2, 'Urgence');
INSERT INTO Motifs VALUES(3, 'Prescription');

INSERT INTO Sexe VALUES('F', 'Féminin');
INSERT INTO Sexe VALUES('M', 'Masculin');
INSERT INTO Sexe VALUES('N', 'Neutre');

INSERT INTO Patients VALUES(1, 'SY', 'Omar', 'M', '1978-01-20', '178017830240455', 'FR', '2023-02-01', 1);
INSERT INTO Patients VALUES(2, 'DEPARDIEU', 'Gérard', 'M', '1948-12-27', '148127504406759', 'FR', '2023-04-05', 2);
INSERT INTO Patients VALUES(3, 'DUJARDIN', 'Jean', 'M', '1972-06-19', '172065903800855', 'FR', '2023-06-12', 3);
INSERT INTO Patients VALUES(4, 'RENO', 'Jean', 'M', '1948-07-30', null, 'MA', '2023-08-18', 1);
INSERT INTO Patients VALUES(5, 'COTTILARD', 'Marion', 'F', '1975-09-30', '275097503200542', 'FR', '2023-09-26', 1);
INSERT INTO Patients VALUES(6, 'CASSEL', 'Vincent', 'M', '1966-11-23', '166117500600711', 'FR', '2023-01-01', 3);
INSERT INTO Patients VALUES(7, 'GREEN', 'Eva', 'F', '1980-06-17', '280067500400733', 'FR', '2023-11-15', 2);
INSERT INTO Patients VALUES(8, 'EFIRA', 'Virginie', 'F', '1977-05-05', null, 'BE', '2023-10-30', 2)

INSERT INTO Media VALUES(1, 1, "photo", "pizza", '2023-02-01');
INSERT INTO Media VALUES(2, 1, "prescription", "Mario", '2023-02-01');









