# Codice

<https://github.com/gabrielrovesti/Pop-Tech> (prendere ispirazione da qui)

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
