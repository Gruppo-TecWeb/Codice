DROP TABLE IF EXISTS Modalità;
DROP TABLE IF EXISTS Basi;
DROP TABLE IF EXISTS Punteggi;
DROP TABLE IF EXISTS Eventi;
DROP TABLE IF EXISTS Classifiche;
DROP TABLE IF EXISTS TipiEvento;
DROP TABLE IF EXISTS Utenti;

CREATE TABLE Utenti (
    Username VARCHAR(100) PRIMARY KEY,
    Password VARCHAR(60) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    ImmagineProfilo TINYTEXT,
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

CREATE TABLE Modalità (
    Id INTEGER PRIMARY KEY AUTO_INCREMENT,
    Titolo VARCHAR(100) NOT NULL,
    Link VARCHAR(255) NOT NULL,
    Descrizione TEXT
);

CREATE TABLE Basi (
    Id int(11) PRIMARY KEY AUTO_INCREMENT,
    Titolo varchar(255) DEFAULT NULL,
    Descrizione varchar(255) DEFAULT NULL
);
INSERT INTO Modalità (Titolo, Link, Descrizione) VALUES
('Minuto', 'https://www.youtube-nocookie.com/embed/RszfbKxb460?enablejsapi=1&amp;start=98&amp;end=210&amp;autoplay=1', 'I <span lang="en">rapper</span> fanno un minuto di <span lang="en">freestyle</span> a testa, semplice semplice.'),
('4/4', 'https://www.youtube-nocookie.com/embed/2ttgML437Ho?enablejsapi=1&amp;start=370&amp;end=510&amp;autoplay=1', 'La modalità più classica tra tutte, a turno i <span lang="en">rapper</span> fanno 4 barre a testa!'),
('Cypher', 'https://www.youtube-nocookie.com/embed/n2qPwcpdeeQ?enablejsapi=1&amp;start=117&amp;end=364&amp;autoplay=1', 'Un <span lang="en">cypher</span> è un gruppo di <span lang="en">rapper</span> che a turno rappano; modalità spesso utilizzata come sorta di riscaldamento pre-<span lang="en">battle</span>.'),
('3/4', 'https://www.youtube-nocookie.com/embed/n2qPwcpdeeQ?enablejsapi=1&amp;start=778&amp;end=1014&amp;autoplay=1', 'Un <span lang="en">rapper</span> canta per <span aria-hidden="true"><strong><sup>3</sup>/<sub>4</sub></strong></span> e l''altro dovrà chiudere la rima nell''ultimo quarto rimanente, continuando a ruota.'),
('KickBack', 'https://www.youtube-nocookie.com/embed/n2qPwcpdeeQ?enablejsapi=1&amp;start=1604&amp;end=3610&amp;autoplay=1', 'Un <span lang="en">rapper</span> fa una domanda e l''altro risponde, continuando con altre domande, tutto seguendo il tempo dei <span lang="en">tre quarti</span>.'),
('Royal rumble', 'https://www.youtube-nocookie.com/embed/Czp97FOKaDM?enablejsapi=1&amp;autoplay=1', 'I <span lang="en">rapper</span> cantano a rotazione e il <span lang="en">rapper</span> meno apprezzato viene eliminato. Gli altri continuano a sfidarsi.'),
('Argomento', 'https://www.youtube-nocookie.com/embed/95SZIlMiFfQ?enablejsapi=1&amp;start=4&amp;autoplay=1', 'Viene dato un argomento su cui i <span lang="en">rapper</span> dovranno cantare.'),
('Acappella', 'https://www.youtube-nocookie.com/embed/OvVk892HzmE?enablejsapi=1&amp;start=762&amp;autoplay=1', 'I <span lang="en">rapper</span> si sfidano senza supporto musicale, concentrandosi solo sulle loro abilità vocali e liriche.'),
('Oggetti', 'https://www.youtube-nocookie.com/embed/S8Ze0GCgo4k?enablejsapi=1&amp;autoplay=1', 'Ai <span lang="en">rapper</span> vengono forniti oggetti a sorpresa, su cui dovranno rappare.');

INSERT INTO Basi (Id, Titolo, Descrizione) VALUES
(1, 'Goodbye - Big Joe.mp3','Un ritmo ipnotico che fluisce come un fiume in piena, dando spazio alle tue parole per danzare sulla sua corrente.'),
(2, 'Big L Ebonics.mp3','Un beat con elementi futuristici e suoni elettronici che ti trasportano in un mondo di possibilità nel quale puoi sperimentare senza limiti.'),
(3, 'Busta Rhymes - Psycobusta.mp3','Una composizione minimalista che mette in risalto la tua voce, permettendoti di dominare la scena con il tuo stile unico.'),
(4, 'Dilated Peoples - The Platform (Erik Sermon Remix).mp3','Un beat dal groove classico e accattivante, un richiamo alle radici dell''hip-hop, pronto a far vibrare l''anima delle tue rime.'),
(5, 'DJ Premier - BAP.mp3','Una base ritmica pesante e decisa, pronta a sostenere le tue rime con una potenza inarrestabile.'),
(6, 'DJ Premier - That White.mp3','Una traccia con un ritmo contagioso e un beat che invoglia all''azione, perfetto per mettere in mostra la tua versatilità e la tua abilità nel freestyle rap'),
(7, 'Full Clip-Gangstarr.mp3','Un beat con una melodia maliziosa che ti invita a sfidare le convenzioni e a esplorare nuove dimensioni nel tuo freestyle.'),
(8, 'Funkdoobiest - Lost in Thought.mp3','Un beat con un mix di elementi jazz e percussioni incalzanti, regalando una base sofisticata per le tue improvvisazioni poetiche.'),
(9, 'Gang Starr - Battle.mp3','Una mescolanza di suoni urbani e ritmi urbani, creando un''atmosfera che ti avvolge e ti spinge a esprimere la tua autenticità nel freestyle.'),
(10, 'J. Cole - Fire Squad.mp3','Un beat dall''andamento selvaggio e irregolare, perfetto per testare le tue abilità nel mantenere il controllo durante il freestyle.'),
(11, 'Jam Baxter - Fine (Prod GhostTown).mp3','Un beat dai suoni energetici e taglienti, pronto a guidare le tue rime con una potente base ritmica.'),
(12, 'Method Man _ Redman - A-Yo.mp3','Un beat dal ritmo incalzante che cattura l''attenzione sin dai primi battiti, perfetto per scatenare la tua creatività nel freestyle rap.'),
(13, 'Movie Villains.mp3','Un beat oscuro e potente, perfetto per trasportarti nel mondo dei cattivi del cinema. Con i suoi bassi profondi e i suoni inquietanti, ti farà sentire come il protagonista di un film d''azione.'),
(14, 'Noyz Narcos - Trucemala.mp3','Questo beat cattura l''essenza del rap hardcore, con ritmi aggressivi e bassi intensi. Ideale per testi forti e decisi, ti darà l''energia giusta per esprimere la tua rabbia e passione'),
(15, 'RA Rugged man - Dangerous Three.mp3','Un beat incalzante e grintoso, caratterizzato da campionamenti vintage e ritmi serrati. Perfetto per mostrare le tue abilità liriche e mantenere alta l''attenzione degli ascoltatori.'),
(16, 'Step Brothers (Alchemist _ Evidence) - Step Masters.mp3','Un beat sofisticato e melodico che unisce elementi di jazz e hip-hop. Ideale per raccontare storie e mostrare il tuo talento nel costruire narrazioni coinvolgenti attraverso il rap.'),
(17, 'Tecniche Perfette - 5.mp3','Un beat classico che combina l''energia grezza del Wu-Tang Clan con la profondità lirica dei Mobb Deep. Ideale per testi riflessivi e potenti, questo beat ti porterà direttamente nelle strade di New York.'),
(18, 'Wu Tang Clan _ Mobb Deep - Phat Beat.mp3','Un beat dal ritmo travolgente, con batteria pesante e melodie accattivanti. Perfetto per le battaglie di freestyle e per mettere in mostra la tua capacità di improvvisazione.'),
(19, 'Timbaland - They Ain_t Ready.mp3','Un beat innovativo e sperimentale, con ritmi complessi e suoni futuristici. Perfetto per dimostrare la tua originalità e creatività, spingendo i confini del genere rap.');

INSERT INTO Utenti (Username, Password, Email, TipoUtente) VALUES ('root', '$2y$10$x0SPRF3nMtr.a.gcpzJwqeS4YToXKGXLAGEHIzi7d//SkEpayFf8i', 'root@mail.it', 'R');
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
INSERT INTO Utenti (Username, Password, Email) VALUES ('Trick', '', 'trick@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('3ska', '', '3ska@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Alex M.', '', 'alexm@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Buddah', '', 'buddah@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Calak', '', 'calak@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Dehli', '', 'dehli@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Dies', '', 'dies@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Dvu', '', 'dvu@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Ed', '', 'ed@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Gio', '', 'gio@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Groomo', '', 'groomo@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Ill Capu', '', 'illcapu@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('J-D', '', 'jd@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Maro', '', 'maro@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Marvin', '', 'marvin@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Nyber', '', 'nyber@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('O-K', '', 'ok@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Oilerua', '', 'oilerua@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Pad', '', 'pad@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Pappa', '', 'pappa@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Ppnr', '', 'ppnr@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Prince', '', 'Prince@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Rico', '', 'rico@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Riukz', '', 'riukz@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Rozen', '', 'rozen@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Secko', '', 'secko@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Silver', '', 'silver@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Skala', '', 'skala@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Thenigro', '', 'thenigro@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Tob', '', 'tob@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Tobia', '', 'tobia@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Ydra', '', 'ydra@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Zaza', '', 'zaza@fungo.it');
INSERT INTO Utenti (Username, Password, Email) VALUES ('Twesh', '', 'twesh@fungo.it');

INSERT INTO TipiEvento (Titolo, Descrizione) VALUES ('Fungo', 'Il Fungo prende il nome dall''iconica struttura presente in piazza zanellato a Padova, dove ha luogo solitamente ogni martedì. Si tratta di una serie di eventi legati al mondo <span lang="en">rap freestyle</span> durante i quali si svolgono delle <span lang="en">battle</span> tra i vari partecipanti. Le classifiche sono i risultati di questi incontri.');
INSERT INTO TipiEvento (Titolo, Descrizione) VALUES ('Micelio', 'Il Micelio è la versione invernale del Fungo e ha luogo solitamente ogni martedì presso il Distretto Est, a Padova. Si tratta di una serie di eventi legati al mondo <span lang="en">rap freestyle</span> durante i quali si svolgono delle <span lang="en">battle</span> tra i vari partecipanti. Le classifiche sono i risultati di questi incontri.');

INSERT INTO Classifiche (Titolo, TipoEvento, DataInizio, DataFine) VALUES ('Fungo 2023', 'Fungo', '2023-09-05', '2023-10-31');
INSERT INTO Classifiche (Titolo, TipoEvento, DataInizio, DataFine) VALUES ('Micelio 2024', 'Micelio', '2024-01-15', '2024-04-28');
INSERT INTO Classifiche (Titolo, TipoEvento, DataInizio, DataFine) VALUES ('Fungo 2024', 'Fungo', '2024-05-13', '2024-10-31');

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
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', 'Udite udite, con piacere annunciamo che martedì 16 Gennaio riaprirá il Micelio con tante novità. Si riaprirá il campionato, con le stesse regole del precedente. Inoltre, ci saranno altre sorpresine che lasceremo si svelino da sole nel corso dei prossimi mesi. Per il resto sarà tutto come la precedente stagione: si può dire tutto basta si usi buon gusto nel farlo. Come sempre ricordiamo a chi viene di portare rispetto verso il posto e la comunità che anima la festa.', '2024-01-16', '21:00:00', 'Distretto Est (Padova)', '18_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-01-23', '21:00:00', 'Distretto Est (Padova)', '19_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Gio Fog Live Showcase', 'Ci vediamo il 30 Gennaio con Gio FoG, che ci porterà il suo disco "Pressure", uscito il 15 Dicembre, fresco fresco. La Battle si svolgerà come da prassi al pari di ogni martedì.', '2024-01-30', '21:00:00', 'Distretto Est (Padova)', '20_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-02-06', '21:00:00', 'Distretto Est (Padova)', '21_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Clinical Mentes Live Showcase', 'Clinical Mentes è una realtà che nasce con l''intento di unire persone affini con l''amore per la cultura Hip Hop. Amicizia e lealtà sono le fondamenta di questo gruppo. Rispetto, ambizione ed impegno le prerogative. Attitudine vecchia scuola e passione vera per la disciplina come biglietto da visita. La battle si svolgerà come da prassi al pari di ogni martedì.', '2024-02-13', '21:00:00', 'Distretto Est (Padova)', '22_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-02-20', '21:00:00', 'Distretto Est (Padova)', '23_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Plebeians Crew Live Showcase', 'Plebeians Crew è un collettivo hip-hop, nato con lo scopo di supportare e dar voce alla street culture locale e non. Fondata in un piccolo paese dell''alto vicentino, ora conta circa 30 membri tra cui rappers, djs, writers, skaters e riders. Giovani e freschissimi, ci daranno modo di sentire un pò di sano hip hoppe nel corso della serata. La Battle si svolgerà come da prassi al pari di ogni martedì.', '2024-02-27', '21:00:00', 'Distretto Est (Padova)', '24_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-03-05', '21:00:00', 'Distretto Est (Padova)', '25_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Tusco Live Showcase', 'Tusco è un rapper di Pavia classe ''93. Inizia il suo percorso musicale nel 2012. Ha attualmente pubblicato tre EP e tre album, l''ultimo dei quali titolato "Ironicon LP" nel 2022. Vanta collaborazioni con artisti del calibro di Fritz Da Cat, Inoki, Esa, Inoki, Dj Skizo, Mattak e altri, esibendosi in tutta italia con performance anche internazionali in Svizzera, UK e Olanda. Dalla penna pregiata, porta rap d''altri tempi senza compromessi sui contenuti. La Battle si svolgerà come da prassi al pari di ogni martedì.', '2024-03-12', '21:00:00', 'Distretto Est (Padova)', '26_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-03-19', '21:00:00', 'Distretto Est (Padova)', '27_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', '2R2 + Tide + Draghums Live Showcase', 'Tide, come l''onda (cit.), rimescola varie sonorità spaziando tra rap, cantato e parlato nella ricerca di nuovi flow. Draghums, trentino trapiantato a Padova da alcuni anni, architetto di barre ad alta densità. Ciurciu, colui che più diretto non può rappare, punchline precise e pochi fronzoli, che siamo street. La Battle si svolgerà come da prassi al pari di ogni martedì.', '2024-03-26', '21:00:00', 'Distretto Est (Padova)', '28_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-04-02', '21:00:00', 'Distretto Est (Padova)', '29_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Groomo & Giandi Live Showcase', 'Pensavate che l''host hostasse e basta? E che il dj djasse e basta? Building blocks del fungo e del micelio, Giandi e Groomo si conoscono nel 2021 e iniziano a coltivare un circolo virtuoso che sfocia nel vostro martedì. Spinti da ideali e non da minchiate, se la vivono nel chill, per quanto possibile, senza cercare nessun compromesso nella loro musica. La Battle si svolgerà come da prassi al pari di ogni martedì.', '2024-04-09', '21:00:00', 'Distretto Est (Padova)', '30_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio', NULL, '2024-04-16', '21:00:00', 'Distretto Est (Padova)', '31_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Bomber Citro e Karma 22 Live Showcase', '“LGNDZ” è il nuovo progetto di Bombercitro e Karma22. I due rapper sono i veterani della scena padovana. Bombercitro, rappresentante della crew "Massima Tackenza" da cui provengono artisti del calibro di Dutch Nazari e Wairaki (produttore del trapper Tonyboy) è attivo dal 2002. Ha avuto una buona carriera come freestyler e ora è da anni il giudice dello "Sherwood Hip Hop Day". Karma22 è un rapper e beatmaker proveniente dalle crew OdioTribale, Trve Vandals, K273. Ha collaborato con molti rapper della scena nazionale e internazionale. Ha all''attivo più di 20 anni di performance live condividendo i palchi con i migliori artisti del panorama italiano. "Musica Leggenda" è il primo singolo del duo. La Battle si svolgerà come da prassi al pari di ogni martedì.', '2024-04-23', '21:00:00', 'Distretto Est (Padova)', '32_micelio.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Micelio', 'Micelio Madness', 'Siete diventati pazzi? Si! Con piacere e dispiacere vi annunciamo la data di chiusura della prima stagione del Micelio. Ringraziamo tutti voi che venite sempre, voi che venite qualche volta, e perchè no, anche chi non viene mai. Ringraziamo ancora anche il Distretto Est per l''ospitalità che ci ha fornito e il tetto che ci ha messo sulla testa nel corso di questi freddi mesi. È tutto vero: dal 7 Maggio si torna al Fungo. Come chiudere se non col botto? E quindi sì, 500 pigne per chi vince. Sarà richiesto un piccolo contributo d''ingresso ma siamo sicuri che potrete capire la situazione. Micelio goes mad for salutare Distretto. Non serve specificare che chiunque abbia un minimo di sale in zucca potrà capire che a questa serata esploderà tutto e quindi perdersela sarebbe una mossa poco sgaia.', '2024-04-30', '21:00:00', 'Distretto Est (Padova)', '33_madness.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-05-14', '21:00:00', 'Piazza Zanellato (Padova)', '34_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-05-21', '21:00:00', 'Piazza Zanellato (Padova)', '35_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-05-28', '21:00:00', 'Piazza Zanellato (Padova)', '36_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-06-04', '21:00:00', 'Piazza Zanellato (Padova)', '37_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-06-11', '21:00:00', 'Piazza Zanellato (Padova)', '38_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-06-18', '21:00:00', 'Piazza Zanellato (Padova)', '39_fungo.jpg');
INSERT INTO Eventi (TipoEvento, Titolo, Descrizione, Data, Ora, Luogo, Locandina) VALUES ('Fungo', 'Fungo', NULL, '2024-06-25', '21:00:00', 'Piazza Zanellato (Padova)', '40_fungo.jpg');


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

/* 1 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Becche', 18, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 18, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Creamy Crime', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 18, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 18, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dikson', 18, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dmike', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Draghums', 18, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Jkass', 18, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('L''Over', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 18, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 18, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Panda', 18, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 18, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 18, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Smp900', 18, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 18, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trago', 18, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 18, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Wawe', 18, 6);

/* 2 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('2R2', 19, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 19, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Becche', 19, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 19, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ceppo', 19, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Checcoglione', 19, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 19, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 19, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dikson', 19, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Giandi', 19, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 19, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Jkass', 19, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 19, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 19, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 19, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 19, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 19, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 19, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 19, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 19, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Brex', 19, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 19, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 19, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Wawe', 19, 2);

/* 3 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 20, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ange', 20, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Atp', 20, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 20, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 20, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Chrislie', 20, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 20, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 20, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('DjSeef', 20, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dusty', 20, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 20, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 20, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 20, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 20, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 20, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trago', 20, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 20, 2);

/* 4 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 21, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Atp', 21, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Becche', 21, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 21, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 21, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 21, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 21, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Draghums', 21, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 21, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 21, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 21, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 21, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 21, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 21, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 21, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 21, 4);

/* 5 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 22, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Atp', 22, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Becche', 22, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 22, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 22, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 22, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 22, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 22, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Draghums', 22, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dvu', 22, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 22, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Jkass', 22, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 22, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 22, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Oilerua', 22, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 22, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 22, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 22, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Secko', 22, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Smp900', 22, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 22, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 22, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Wawe', 22, 1);

/* 6 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 23, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Atp', 23, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Becche', 23, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 23, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 23, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 23, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 23, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Gio', 23, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 23, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 23, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 23, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 23, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 23, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 23, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 23, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Smp900', 23, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 23, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 23, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 23, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Wawe', 23, 4);

/* 7 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('2R2', 24, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 24, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 24, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 24, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 24, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 24, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dusty', 24, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('L''Over', 24, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 24, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 24, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 24, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 24, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 24, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Secko', 24, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 24, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 24, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 24, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ydra', 24, 1);

/* 8 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('2R2', 25, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 25, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Atp', 25, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Becche', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 25, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dehli', 25, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dikson', 25, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Draghums', 25, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Giandi', 25, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 25, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 25, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Panda', 25, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 25, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 25, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Secko', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Skala', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trago', 25, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 25, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Twesh', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Wawe', 25, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ydra', 25, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Zaza', 25, 1);

/* 9 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 26, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Becche', 26, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 26, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 26, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 26, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 26, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 26, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dusty', 26, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 26, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 26, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 26, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 26, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pappa', 26, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 26, 3);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 26, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Rico', 26, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Riukz', 26, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 26, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Rozen', 26, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 26, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 26, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tobia', 26, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 26, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 26, 7);

/* 10 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 27, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 27, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 27, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ed', 27, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 27, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pappa', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Silver', 27, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trago', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 27, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Zaza', 27, 4);

/* 11 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('3ska', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 28, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Atp', 28, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Becche', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Calak', 28, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 28, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 28, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 28, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dikson', 28, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ed', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Gio', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ill Capu', 28, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('La Rossa', 28, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Maro', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pad', 28, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ppnr', 28, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Riukz', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 28, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 28, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Smp900', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 28, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tobia', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trago', 28, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 28, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 28, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ydra', 28, 1);

/* 12 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('2R2', 29, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('3ska', 29, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 29, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 29, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 29, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Creamy Crime', 29, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 29, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dusty', 29, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 29, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 29, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 29, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 29, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Skala', 29, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 29, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 29, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 29, 2);

/* 13 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('2R2', 30, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Atp', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Buddah', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dies', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Draghums', 30, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dvu', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ed', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 30, 3);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('J-D', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Jkass', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('L''Over', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 30, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Maro', 30, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Marvin', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Panda', 30, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Riukz', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 30, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Skala', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Smp900', 30, 3);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 30, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Thenigro', 30, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tob', 30, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trago', 30, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 30, 1);

/* 14 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 31, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Atp', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Calak', 31, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 31, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Denny Raven', 31, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Der', 31, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dusty', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ed', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Jkass', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('K Fenix', 31, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 31, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Mabe', 31, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pad', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Ppnr', 31, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Prince', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Riukz', 31, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 31, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Trick', 31, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 31, 6);

/* 15 giornata micelio 2024 */
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('2R2', 32, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Adc', 32, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Alex M.', 32, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Brex', 32, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Causma', 32, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dessi', 32, 3);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dikson', 32, 8);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Draghums', 32, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Dvu', 32, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Groomo', 32, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Issa', 32, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('LucaGiusti', 32, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Maro', 32, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Nyber', 32, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('O-K', 32, 2);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Pascal', 32, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Poetemaudit', 32, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Roma', 32, 7);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Secko', 32, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Sinos', 32, 6);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Skala', 32, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('T-Rain', 32, 4);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Tweak', 32, 1);
INSERT INTO PUNTEGGI (Partecipante, Evento, Punteggio) VALUES ('Zaza', 32, 4);