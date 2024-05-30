DROP TABLE IF EXISTS Punteggi;
DROP TABLE IF EXISTS Eventi;
DROP TABLE IF EXISTS Classifiche;
DROP TABLE IF EXISTS TipiEvento;
DROP TABLE IF EXISTS Utenti;

CREATE TABLE Utenti (
    Username VARCHAR(100) PRIMARY KEY,
    Password VARCHAR(60) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    TipoUtente CHAR(1) NOT NULL DEFAULT 'U',
    Attivo BOOLEAN NOT NULL DEFAULT TRUE);

CREATE TABLE TipiEvento (
    Titolo VARCHAR(100) PRIMARY KEY,
    Descrizione TEXT);

CREATE TABLE Classifiche (
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Titolo VARCHAR(100) UNIQUE NOT NULL,
    TipoEvento VARCHAR(100),
    DataInizio DATE NOT NULL,
    DataFine DATE NOT NULL,
    FOREIGN KEY (TipoEvento) REFERENCES TipiEvento(Titolo) ON DELETE RESTRICT ON UPDATE CASCADE);

CREATE TABLE Eventi (
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    TipoEvento VARCHAR(100),
    Titolo VARCHAR(100) NOT NULL,
    Descrizione TEXT,
    Data DATE NOT NULL,
    Ora TIME NOT NULL,
    Luogo TINYTEXT NOT NULL,
    Locandina TINYTEXT,
    FOREIGN KEY (TipoEvento) REFERENCES TipiEvento(Titolo) ON DELETE SET NULL ON UPDATE CASCADE);

CREATE TABLE Punteggi (
    Partecipante VARCHAR(100) NOT NULL,
    Evento INTEGER NOT NULL,
    Punteggio INTEGER NOT NULL,
    PRIMARY KEY (Partecipante, Evento),
    FOREIGN KEY (Evento) REFERENCES Eventi(Id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Partecipante) REFERENCES Utenti(Username) ON DELETE RESTRICT ON UPDATE CASCADE);

INsERT INTO Utenti (Username, Password, Email, TipoUtente) VALUES ('root', '$2y$10$x0SPRF3nMtr.a.gcpzJwqeS4YToXKGXLAGEHIzi7d//SkEpayFf8i', 'root@mail.it', 'R');
INSERT INTO Utenti (Username, Password, Email, TipoUtente) VALUES ('admin', '$2y$10$6HccIqtLp.aSP1X4H/X3GeNJaXsTLVrNCPYIaMURXPUfxSL7qjphi', 'admin@fungo.it', 'A');
INSERT INTO Utenti (Username, Password, Email) VALUES ('user', '$2y$10$Z0Aa3dQjyumq4IUcqxlIK.Han8U1eeETu7utaA9WhT.iKcggzR49G', 'user@fungo.it');

INSERT INTO Utenti (Username, Password, Email) VALUES ('Denny Raven', '', 'denny.raven@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Trago', '', 'trago@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Mabe', '', 'mabe@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Wawe', '', 'wawe@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Roma', '', 'roma@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Causma', '', 'causma@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Dikson', '', 'dikson@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Jkass', '', 'jkass@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Dessi', '', 'dessi@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Sinos', '', 'sinos@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Panda', '', 'panda@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('LucaGiusti', '', 'lucagiusti@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Draghums', '', 'draghums@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Tweak', '', 'tweak@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Becche', '', 'becche@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('T-Rain', '', 'train@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('La Rossa', '', 'larossa@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Ceppo', '', 'ceppo@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Pascal', '', 'pascal@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Smp900', '', 'smp900@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Poetemaudit', '', 'poetmaudit@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('L''Over', '', 'over@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Creamy Crime', '', 'creamy.crime@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Dmike', '', 'dmike@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Adc', '', 'adc@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Der', '', 'der@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('K Fenix', '', 'kfenix@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('2R2', '', '2r2@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Checcoglione', '', 'checcoglione@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Giandi', '', 'giandi@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Issa', '', 'issa@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Brex', '', 'brex@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Dusty', '', 'dusty@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Atp', '', 'atp@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Chrislie', '', 'chrislie@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('DjSeef', '', 'djseef@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Ange', '', 'ange@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('T-Brex', '', 'tbrex@fungo.it');

INSERT INTO TipiEvento (Titolo, Descrizione) VALUES ('Fungo', 'Il Fungo prende il nome dall''iconica struttura presente in piazza zanellato a Padova, dove ha luogo solitamente ogni martedì. Si tratta di una serie di eventi legati al mondo <span lang="en">rap freestyle</span> durante i quali si svolgono delle <span lang="en">battle</span> tra i vari partecipanti. Le classifiche sono i risultati di questi incontri.');
INSERT INTO TipiEvento (Titolo, Descrizione) VALUES ('Micelio', 'Il Micelio è la versione invernale del Fungo e ha luogo solitamente ogni martedì presso il Distretto Est, a Padova. Si tratta di una serie di eventi legati al mondo <span lang="en">rap freestyle</span> durante i quali si svolgono delle <span lang="en">battle</span> tra i vari partecipanti. Le classifiche sono i risultati di questi incontri.');

INSERT INTO Classifiche (Titolo, TipoEvento, DataInizio, DataFine) VALUES ('Fungo 2023', 'Fungo', '2023-09-05', '2023-10-17');
INSERT INTO Classifiche (Titolo, TipoEvento, DataInizio, DataFine) VALUES ('Micelio 2023', 'Micelio', '2023-11-07', '2024-12-19');
INSERT INTO Classifiche (Titolo, TipoEvento, DataInizio, DataFine) VALUES ('Micelio 2024', 'Micelio', '2024-01-16', '2024-03-31');

INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES (NULL, '<span lang="en">Meal the mic</span>', 'Rapcolta alimentare', '2023-04-29', '18:00:00', 'Circolo culturale Carichi Sospesi (Padova)', '1_meal_the_mic.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES (NULL, '<span lang="en">Hip hop night</span>', '<span lang="en">Freestyel battle</span>', '2023-07-13', '18:00:00', 'Parco Morandi (Padova)', '2_hip_hop_night.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', '<span lang="en">Battle</span>', '2023-09-05', '21:00:00', 'Piazza Zanellato (Padova)', '3_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', '<span lang="en">Battle</span>', '2023-09-12', '21:00:00', 'Piazza Zanellato (Padova)', '4_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', '<span lang="en">Battle</span>', '2023-09-19', '21:00:00', 'Piazza Zanellato (Padova)', '5_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', '<span lang="en">Battle</span>', '2023-09-26', '21:00:00', 'Piazza Zanellato (Padova)', '6_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', '<span lang="en">Battle</span>', '2023-10-03', '21:00:00', 'Piazza Zanellato (Padova)', '7_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', '<span lang="en">Battle</span>', '2023-10-10', '21:00:00', 'Piazza Zanellato (Padova)', '8_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', '<span lang="en">Battle</span>', '2023-10-17', '21:00:00', 'Piazza Zanellato (Padova)', '9_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2023-11-07', '21:00:00', 'Distretto Est (Padova)', '10_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2023-11-14', '21:00:00', 'Distretto Est (Padova)', '11_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES (NULL, '<span lang="en">Meal the mic vol.2</span>', 'Rapcolta alimentare', '2023-11-18', '18:00:00', 'Distretto Est (Padova)', '12_meal_the_mic_vol2.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2023-11-21', '21:00:00', 'Distretto Est (Padova)', '13_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2023-11-28', '21:00:00', 'Distretto Est (Padova)', '14_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2023-12-05', '21:00:00', 'Distretto Est (Padova)', '15_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2023-12-12', '21:00:00', 'Distretto Est (Padova)', '16_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2023-12-19', '21:00:00', 'Distretto Est (Padova)', '17_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-01-16', '21:00:00', 'Distretto Est (Padova)', '18_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-01-23', '21:00:00', 'Distretto Est (Padova)', '19_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-01-30', '21:00:00', 'Distretto Est (Padova)', '20_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-02-06', '21:00:00', 'Distretto Est (Padova)', '21_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-02-13', '21:00:00', 'Distretto Est (Padova)', '22_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-02-20', '21:00:00', 'Distretto Est (Padova)', '23_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-02-27', '21:00:00', 'Distretto Est (Padova)', '24_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-03-05', '21:00:00', 'Distretto Est (Padova)', '25_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-03-12', '21:00:00', 'Distretto Est (Padova)', '26_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-03-19', '21:00:00', 'Distretto Est (Padova)', '27_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-03-26', '21:00:00', 'Distretto Est (Padova)', '28_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-04-02', '21:00:00', 'Piazza Zanellato (Padova)', '29_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-04-09', '21:00:00', 'Piazza Zanellato (Padova)', '30_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-04-16', '21:00:00', 'Piazza Zanellato (Padova)', '31_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-04-23', '21:00:00', 'Piazza Zanellato (Padova)', '32_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-04-30', '21:00:00', 'Piazza Zanellato (Padova)', '33_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-05-07', '21:00:00', 'Piazza Zanellato (Padova)', '34_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-05-14', '21:00:00', 'Piazza Zanellato (Padova)', '35_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-05-21', '21:00:00', 'Piazza Zanellato (Padova)', '36_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-05-28', '21:00:00', 'Piazza Zanellato (Padova)', '37_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-06-04', '21:00:00', 'Piazza Zanellato (Padova)', '38_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-06-11', '21:00:00', 'Piazza Zanellato (Padova)', '39_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-06-18', '21:00:00', 'Piazza Zanellato (Padova)', '40_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-06-25', '21:00:00', 'Piazza Zanellato (Padova)', '41_fungo.jpg');


/* punteggi random eventi fungo e micelio ancora senza punteggi */
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 3, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Trago', 3, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 3, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 3, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 3, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 3, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 3, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 3, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Panda', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Draghums', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 3, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Smp900', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('L''Over', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Creamy Crime', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dmike', 3, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 3, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 4, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 4, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 4, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 4, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 4, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 4, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 4, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 4, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 4, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 4, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 4, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 5, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 5, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 5, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 5, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 5, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 5, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 5, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 5, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 5, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 5, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 5, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 6, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 6, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 6, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 6, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 6, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 6, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 6, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 6, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 6, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 6, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 6, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 7, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Trago', 7, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 7, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 7, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 7, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 7, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 7, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 7, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Panda', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Draghums', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 7, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Smp900', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('L''Over', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Creamy Crime', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dmike', 7, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 7, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 8, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 8, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 8, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 8, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 8, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 8, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 8, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 8, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 8, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 8, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 8, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 9, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 9, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 9, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 9, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 9, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 9, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 9, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 9, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 9, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 9, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 9, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 10, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 10, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 10, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 10, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 10, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 10, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 10, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 10, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 10, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 10, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 10, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 11, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 11, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 11, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 11, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 11, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 11, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 11, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 11, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 11, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 11, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 11, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 12, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Trago', 12, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 12, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 12, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 12, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 12, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 12, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 12, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 12, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 12, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Panda', 12, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 12, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Draghums', 12, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 12, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 12, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 12, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 12, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 12, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 12, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Smp900', 12, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 12, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('L''Over', 12, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Creamy Crime', 12, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dmike', 12, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 12, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 13, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 13, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 13, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 13, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 13, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 13, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 13, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 13, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 13, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 13, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 13, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 14, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 14, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 14, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 14, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 14, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 14, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 14, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 14, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 14, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 14, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 14, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 15, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Trago', 15, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 15, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 15, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 15, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 15, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 15, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 15, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Panda', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Draghums', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 15, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Smp900', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('L''Over', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Creamy Crime', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dmike', 15, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 15, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 16, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 16, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 16, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 16, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 16, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 16, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 16, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 16, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 16, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 16, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 16, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 17, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Trago', 17, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 17, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 17, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 17, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 17, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 17, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 17, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Panda', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Draghums', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 17, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Smp900', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('L''Over', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Creamy Crime', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dmike', 17, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 17, 1);

/* fine punteggi random */

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 18, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Trago', 18, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 18, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 18, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 18, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 18, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 18, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 18, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 18, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 18, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Panda', 18, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 18, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Draghums', 18, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 18, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 18, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 18, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 18, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 18, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 18, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Smp900', 18, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 18, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('L''Over', 18, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Creamy Crime', 18, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dmike', 18, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 18, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 19, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Wawe', 19, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 19, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 19, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dikson', 19, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Jkass', 19, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 19, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Sinos', 19, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 19, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 19, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Becche', 19, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 19, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 19, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 19, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 19, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 19, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 19, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 19, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 19, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('2R2', 19, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 19, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Giandi', 19, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Issa', 19, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 19, 1);

INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Mabe', 20, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Der', 20, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Trago', 20, 8);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Causma', 20, 7);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Adc', 20, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Roma', 20, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Tweak', 20, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 20, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Pascal', 20, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dessi', 20, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 20, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Brex', 20, 6);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Dusty', 20, 4);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Atp', 20, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Chrislie', 20, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('DjSeef', 20, 2);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('Ange', 20, 1);
INSERT INTO Punteggi (Partecipante, Evento, Punteggio) VALUES ('T-Brex', 20, 1);