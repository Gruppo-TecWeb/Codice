DROP TABLE IF EXISTS TagEvento;
DROP TABLE IF EXISTS TagTipoEvento;
DROP TABLE IF EXISTS Tags;
DROP TABLE IF EXISTS Punteggi;
DROP TABLE IF EXISTS Eventi;
DROP TABLE IF EXISTS Stagioni;
DROP TABLE IF EXISTS TipiEvento;
DROP TABLE IF EXISTS Utenti;
DROP TABLE IF EXISTS TipiUtente;

CREATE TABLE TipiUtente (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Descrizione VARCHAR(32) NOT NULL);

CREATE TABLE Utenti (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(32) NOT NULL,
    Password VARCHAR(32) NOT NULL,
    Email VARCHAR(32) NOT NULL,
    TipoUtente INTEGER NOT NULL,
    FOREIGN KEY (TipoUtente) REFERENCES TipiUtente(id));

CREATE TABLE TipiEvento (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Descrizione VARCHAR(32) NOT NULL);

CREATE TABLE Stagioni (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    AnnoInizio INTEGER NOT NULL,
    MeseInizio INTEGER NOT NULL,
    TipoEvento INTEGER NOT NULL,
    FOREIGN KEY (TipoEvento) REFERENCES TipiEvento(id));

CREATE TABLE Eventi (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Titolo VARCHAR(32) NOT NULL,
    Descrizione TEXT,
    Data DATE NOT NULL,
    Ora TIME NOT NULL,
    Luogo VARCHAR(32) NOT NULL,
    Stagione INTEGER,
    FOREIGN KEY (Stagione) REFERENCES Stagioni(id));

CREATE TABLE Punteggi (
    Partecipante VARCHAR(32) NOT NULL,
    Evento INTEGER NOT NULL,
    Punteggio INTEGER NOT NULL,
    PRIMARY KEY (Partecipante, Evento),
    FOREIGN KEY (Evento) REFERENCES Eventi(id));

CREATE TABLE Tags (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Tag VARCHAR(32) NOT NULL);

CREATE TABLE TagTipoEvento (
    Tag INTEGER NOT NULL,
    TipoEvento INTEGER NOT NULL,
    PRIMARY KEY (Tag, TipoEvento),
    FOREIGN KEY (Tag) REFERENCES Tags(id),
    FOREIGN KEY (TipoEvento) REFERENCES TipiEvento(id));

CREATE TABLE TagEvento (
    Tag INTEGER NOT NULL,
    Evento INTEGER NOT NULL,
    PRIMARY KEY (Tag, Evento),
    FOREIGN KEY (Tag) REFERENCES Tags(id),
    FOREIGN KEY (Evento) REFERENCES Eventi(id));

INSERT INTO TipiUtente (Descrizione) VALUES ('Admin');
INSERT INTO TipiUtente (Descrizione) VALUES ('User');

INSERT INTO Utenti (Username, Password, Email, TipoUtente) VALUES ('admin', 'admin', '', 1); -- la password va inserita col valore hashato
INSERT INTO Utenti (Username, Password, Email, TipoUtente) VALUES ('user', 'user', '', 2); -- la password va inserita col valore hashato

INSERT INTO TipiEvento (Descrizione) VALUES ('Fungo');
INSERT INTO TipiEvento (Descrizione) VALUES ('Micelio');

INSERT INTO Stagioni (AnnoInizio, MeseInizio, TipoEvento) VALUES (2023, 9, 1);
INSERT INTO Stagioni (AnnoInizio, MeseInizio, TipoEvento) VALUES (2023, 11, 2);

INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Meal the mic', 'Rapcolta alimentare', '2023-04-29', '18:00:00', 'Circolo culturale Carichi Sospesi (Padova)', NULL);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Hip hop night', 'Freestyel battle', '2023-07-13', '18:00:00', 'Parco Morandi (Padova)', NULL);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Fungo', NULL, '2023-09-05', '21:00:00', '', 1);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Fungo', NULL, '2023-09-12', '21:00:00', '', 1);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Fungo', NULL, '2023-09-19', '21:00:00', '', 1);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Fungo', NULL, '2023-09-26', '21:00:00', '', 1);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Fungo', NULL, '2023-10-03', '21:00:00', '', 1);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Fungo', NULL, '2023-10-10', '21:00:00', '', 1);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Fungo', NULL, '2023-10-17', '21:00:00', '', 1);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2023-11-07', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2023-11-14', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Meal the mic vol.2', 'Rapcolta alimentare', '2023-11-18', '18:00:00', 'Distretto Est (Padova)', NULL);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2023-11-21', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2023-11-28', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2023-12-05', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2023-12-12', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2023-12-19', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2024-12-19', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2024-10-19', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2024-9-19', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2024-8-19', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2024-7-19', '21:00:00', 'Distretto Est (Padova)', 2);
INSERT INTO Eventi (Titolo, Descrizione, Data, Ora, Luogo, Stagione) VALUES ('Micelio', NULL, '2024-11-19', '21:00:00', 'Distretto Est (Padova)', 2);

-- insert di punteggi casuali con gli stessi rapper per tutti gli eventi di tipo 1 o 2
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 3, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 3, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 3, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 3, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 3, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 3, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 3, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 3, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 3, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 3, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 3, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 3, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 3, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 3, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 3, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 3, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 4, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 4, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 4, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 4, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 4, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 4, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 4, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 4, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 4, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 4, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 4, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 4, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 4, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 4, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 4, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 4, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 5, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 5, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 5, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 5, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 5, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 5, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 5, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 5, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 5, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 5, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 5, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 5, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 5, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 5, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 5, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 5, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 6, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 6, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 6, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 6, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 6, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 6, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 6, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 6, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 6, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 6, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 6, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 6, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 6, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 6, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 6, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 6, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 7, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 7, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 7, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 7, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 7, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 7, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 7, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 7, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 7, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 7, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 7, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 7, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 7, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 7, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 7, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 7, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 8, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 8, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 8, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 8, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 8, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 8, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 8, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 9, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 9, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 9, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 9, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 9, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 9, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 9, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 9, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 9, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 9, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 9, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 9, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 9, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 9, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 9, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 9, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 10, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 10, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 10, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 10, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 10, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 10, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 10, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 10, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 10, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 10, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 10, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 10, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 10, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 10, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 10, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 10, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 11, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 11, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 11, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 11, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 11, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 11, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 11, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 11, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 11, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 11, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 11, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 11, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 11, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 11, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 11, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 11, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 13, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 13, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 13, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 13, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 13, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 13, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 13, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 13, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 13, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 13, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 13, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 13, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 13, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 13, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 13, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 13, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 14, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 14, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 14, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 14, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 14, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 14, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 14, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 14, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 14, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 14, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 14, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 14, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 14, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 14, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 14, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 14, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 15, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 15, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 15, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 15, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 15, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 15, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 15, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 15, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 15, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 15, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 15, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 15, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 15, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 15, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 15, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 15, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 16, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 16, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 16, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 16, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 16, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 16, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 16, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 16, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 16, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 16, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 16, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 16, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 16, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 16, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 16, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 16, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper1', 17, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper2', 17, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper3', 17, 25);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper4', 17, 50);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper5', 17, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper6', 17, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper7', 17, 20);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper8', 17, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper9', 17, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper10', 17, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper11', 17, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper12', 17, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper13', 17, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper14', 17, 10);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper15', 17, 0);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('rapper16', 17, 10);

INSERT INTO Tags (Tag) VALUES ('Beneficenza');
INSERT INTO Tags (Tag) VALUES ('Campionato');

INSERT INTO TagTipoEvento (Tag, TipoEvento) VALUES (2, 1);
INSERT INTO TagTipoEvento (Tag, TipoEvento) VALUES (2, 2);

INSERT INTO TagEvento (Tag, Evento) VALUES (1, 1);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 3);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 4);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 5);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 6);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 7);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 8);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 9);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 10);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 11);
INSERT INTO TagEvento (Tag, Evento) VALUES (1, 12);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 13);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 14);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 15);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 16);
INSERT INTO TagEvento (Tag, Evento) VALUES (2, 17);
