DROP TABLE IF EXISTS Punteggi;
DROP TABLE IF EXISTS ClassificheEventi;
DROP TABLE IF EXISTS Eventi;
DROP TABLE IF EXISTS Classifiche;
DROP TABLE IF EXISTS TipiEvento;
DROP TABLE IF EXISTS Utenti;

CREATE TABLE Utenti (
    Username VARCHAR(100) PRIMARY KEY,
    Password VARCHAR(60) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Admin CHAR(1) NOT NULL DEFAULT 'N');

CREATE TABLE TipiEvento (
    Titolo VARCHAR(100) PRIMARY KEY,
    Descrizione TEXT);

CREATE TABLE Classifiche (
    TipoEvento VARCHAR(100),
    DataInizio DATE NOT NULL,
    DataFine DATE,
    FOREIGN KEY (TipoEvento) REFERENCES TipiEvento(Titolo),
    PRIMARY KEY (TipoEvento, DataInizio));

CREATE TABLE Eventi (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Titolo VARCHAR(100) NOT NULL,
    Descrizione TEXT,
    Data DATE NOT NULL,
    Ora TIME NOT NULL,
    Luogo TINYTEXT NOT NULL,
    Locandina TINYTEXT);

CREATE TABLE ClassificheEventi (
    TipoEvento VARCHAR(100) NOT NULL,
    DataInizio DATE NOT NULL,
    Evento INTEGER NOT NULL,
    PRIMARY KEY (TipoEvento, DataInizio, Evento),
    FOREIGN KEY (TipoEvento, DataInizio) REFERENCES Classifiche(TipoEvento, DataInizio),
    FOREIGN KEY (Evento) REFERENCES Eventi(id));

CREATE TABLE Punteggi (
    Partecipante VARCHAR(100) NOT NULL,
    Evento INTEGER NOT NULL,
    Punteggio INTEGER NOT NULL,
    PRIMARY KEY (Partecipante, Evento),
    FOREIGN KEY (Evento) REFERENCES Eventi(id),
    FOREIGN KEY (Partecipante) REFERENCES Utenti(Username));

INSERT INTO Utenti (Username, Password, Email, Admin) VALUES ('admin', '$2y$10$6HccIqtLp.aSP1X4H/X3GeNJaXsTLVrNCPYIaMURXPUfxSL7qjphi', 'admin@mail.it', 'S');
INSERT INTO Utenti (Username, Password, Email) VALUES ('user', '$2y$10$Z0Aa3dQjyumq4IUcqxlIK.Han8U1eeETu7utaA9WhT.iKcggzR49G', 'user@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Juice WRLD', '', 'juicewrld@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Lil Peep', '', 'lilpeep@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('XXXTentacion', '', 'xxxtentacion@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Mac Miller', '', 'macmiller@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Pop Smoke', '', 'popsmoke@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Nipsey Hussle', '', 'nipseyhussle@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Tupac Shakur', '', 'tupacshakur@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('The Notorious B.I.G.', '', 'thenotoriousbig@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Eminem', '', 'eminem@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Kendrick Lamar', '', 'kendricklamar@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('J. Cole', '', 'jcole@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Drake', '', 'drake@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Travis Scott', '', 'travisscott@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Kanye West', '', 'kanyewest@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Jay-Z', '', 'jayz@mail.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Snoop Dogg', '', 'snoopdogg@mail.it');

INSERT INTO TipiEvento (Titolo) VALUES ('Fungo');
INSERT INTO TipiEvento (Titolo) VALUES ('Micelio');

INSERT INTO Classifiche (TipoEvento, DataInizio, DataFine) VALUES ('Fungo', '2023-09-05', '2023-10-17');
INSERT INTO Classifiche (TipoEvento, DataInizio, DataFine) VALUES ('Micelio', '2023-11-07', '2023-12-19');

INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Meal the mic', 'Rapcolta alimentare', '2023-04-29', '18:00:00', 'Circolo culturale Carichi Sospesi (Padova)', 'media/locandine/meal_the_mic.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Hip hop night', 'Freestyel battle', '2023-07-13', '18:00:00', 'Parco Morandi (Padova)', 'media/locandine/hip_hop_night.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', NULL, '2023-09-05', '21:00:00', '', 'media/locandine/fungo.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', NULL, '2023-09-12', '21:00:00', '', 'media/locandine/fungo.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', NULL, '2023-09-19', '21:00:00', '', 'media/locandine/fungo.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', NULL, '2023-09-26', '21:00:00', '', 'media/locandine/fungo.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', NULL, '2023-10-03', '21:00:00', '', 'media/locandine/fungo.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', NULL, '2023-10-10', '21:00:00', '', 'media/locandine/fungo.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', NULL, '2023-10-17', '21:00:00', '', 'media/locandine/fungo.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', NULL, '2023-11-07', '21:00:00', 'Distretto Est (Padova)', 'media/locandine/micelio.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', NULL, '2023-11-14', '21:00:00', 'Distretto Est (Padova)', 'media/locandine/micelio.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Meal the mic vol.2', 'Rapcolta alimentare', '2023-11-18', '18:00:00', 'Distretto Est (Padova)', 'media/locandine/meal_the_mic_vol2.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', NULL, '2023-11-21', '21:00:00', 'Distretto Est (Padova)', 'media/locandine/micelio.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', NULL, '2023-11-28', '21:00:00', 'Distretto Est (Padova)', 'media/locandine/micelio.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', NULL, '2023-12-05', '21:00:00', 'Distretto Est (Padova)', 'media/locandine/micelio.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', NULL, '2023-12-12', '21:00:00', 'Distretto Est (Padova)', 'media/locandine/micelio.jpg');
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', NULL, '2023-12-19', '21:00:00', 'Distretto Est (Padova)', 'media/locandine/micelio.jpg');

INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Fungo', '2023-09-05', 3);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Fungo', '2023-09-05', 4);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Fungo', '2023-09-05', 5);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Fungo', '2023-09-05', 6);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Fungo', '2023-09-05', 7);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Fungo', '2023-09-05', 8);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Fungo', '2023-09-05', 9);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Micelio', '2023-11-07', 10);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Micelio', '2023-11-07', 11);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Micelio', '2023-11-07', 13);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Micelio', '2023-11-07', 14);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Micelio', '2023-11-07', 15);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Micelio', '2023-11-07', 16);
INSERT INTO ClassificheEventi (TipoEvento, DataInizio, Evento) VALUES ('Micelio', '2023-11-07', 17);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 3, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 3, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 3, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 3, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 4, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 4, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 4, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 4, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 5, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 5, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 5, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 5, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 6, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 6, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 6, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 6, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 7, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 7, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 7, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 7, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 8, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 8, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 8, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 8, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 9, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 9, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 9, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 9, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 10, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 10, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 10, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 10, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 11, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 11, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 11, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 11, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 13, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 13, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 13, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 13, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 14, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 14, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 14, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 14, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 15, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 15, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 15, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 15, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 16, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 16, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 16, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 16, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tupac Shakur', 17, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('XXXTentacion', 17, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Nipsey Hussle', 17, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kendrick Lamar', 17, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('The Notorious B.I.G.', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Drake', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pop Smoke', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Lil Peep', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jay-Z', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('J. Cole', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Snoop Dogg', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Kanye West', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mac Miller', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Juice WRLD', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Travis Scott', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Eminem', 17, 1);