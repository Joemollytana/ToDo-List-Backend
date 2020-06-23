# What to fix





## Backend



### initDb.php

- ~~evtl. vernünftigen DB-User für die DB anlegen?~~
- Passwort hashen beim login im backend
- (Attribute als optional & nicht optional in der DB anlegen)
- ~~evtl. Deadline~~ 

### index.php (REST-API)

- ~~boolean Parameter einführen, mit dem überprüft wird, ob der User bereits eingeloggt ist und dementsprechend seine Listen sehen kann oder nicht ~~
- Restliche Routen fertig stellen (mit Kommentaren im Code gekennzeichnet welche Routen) 
  - In Abspracht mit Andrey die alten POST-Routen (newTasklist & new Task) löschen (Inhalt wird jetzt über den HTTP-Body (JSON) statt über die URL übermittelt)

// Change task /

/* DELETE-Requests */
// bearbeiten 'if' Andrey
// delete, create, change user


// Login --> compare input with DB-User



## Frontend 

- interaction clientseitig... 
angular typescript

### todolist-Komponente

- Evtl. Umbenennung in tasklist, um einheitlich zu bleiben. Habe bei der Erstellung nicht nachgedacht. Für die Umbenennung müsste die gesamte Datei über die CLI gelöscht und neu erstellt werden. 