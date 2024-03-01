# Codice

## Regolamenti

[Regole per la consegna del progetto](https://stem.elearning.unipd.it/mod/page/view.php?id=384922) <br>
[Regole per il concorso Accattivante Accessibile 2024](https://stem.elearning.unipd.it/pluginfile.php/640465/mod_resource/content/2/Concorso24.pdf)
[Regole accessibilità WCAG](https://www.w3.org/Translations/WCAG21-it/)

## Altri studenti

### Anni scorsi
 - [Onoranze Stecca](https://github.com/MrBrune01/OnoranzeStecca) [28 + 2]
 - [Pop Tech](https://github.com/gabrielrovesti/Pop-Tech) [30 e lode]
 - [Film - soundstage](https://github.com/ggardin/tecweb) [30 e lode] [Vincitori conconcorso Accessibilità]

### Quest'anno
 - [Clue Catchers](https://github.com/samuele-visentin/unipd-TecWeb) [30 + 2]
 - [Sushi Brombeis](https://github.com/Barutta02/TecWeb) [29,5 + 2]

## Possibile modo di lavorare

### Master

Contiene codice:

- che deriva da un merge con develop (e solo da qui!)
- validato per html [link validatore html]
- validato per css [link validatore css]
- accessibile [definire come]
- che rispetti i vincoli del progetto [link vincoli progetto]
- che è stato provato sul server tecweb [aia]
- che vada bene al gruppo

### Develop

Contiene codice:

- che deriva dalle feature branch
- che deriva da commit appositi per risolvere conflitti, bug tra features e per arrivare ai vincoli posti nel master
- che alla fine del lavoro verrà mergiato nel master stando attenti ai vincoli

### Feature branch

Contiene codice:

- che serve per risolvere una determinata issue (quindi il branch sarà associato ad una issue su github)
- che alla fine del lavoro (quindi risolta la issue) verrà mergiato in develop tramite una pull request (per eventuali conflitti non risolvibili indivualmente meglio contattare il gruppo)

Il branch:

- parte dall'ultima versione di develop
- diventa obsoleto dopo la corretta riuscita della pull request in develop
- non deve interagire direttamente con il master
- si può eliminare quando la sua feature viene inserita nel master

### Hotfix branch

Contiene codice:

- di manuntezione per il master

Il branch:

- parte dal master
- a fine lavoro verrà mergiato nel master e in develop e verrà eliminato
