# What to fix





## Backend



### initDb.php

- ~~Passwort hashen beim login im backend~~
- ~~Tasklistname hinzufügen (andrey)~~
- ~~(Attribute als optional & nicht optional in der DB anlegen)~~

### index.php (REST-API)

- ~~boolean Parameter einführen, mit dem überprüft wird, ob der User bereits eingeloggt ist und dementsprechend seine Listen sehen kann oder nicht~~

- ~~Restliche Routen fertig stellen (mit Kommentaren im Code gekennzeichnet welche Routen)~~
  - ~~Change task --> bezogen aufs Frontend~~
  - ~~bearbeiten 'if' Andrey~~
  - ~~create user~~
  - ~~change user --> User bearbeiten (Password / Username ändern) (David)~~
  - Auskommentierten Code ggf. wenn nicht mehr notwendig löschen
  
- ~~Login --> compare input with DB-User~~

- ~~Tasklistnamen hinzufügen (andrey)~~

- Überprüfung, ob DELETE task noch funktioniert. Ich ergänze "s" bei:

  ```php
  $task = $tasklist->xownTasksList[$args['taskId']];
  ```

  



## Frontend 

- interaction clientseitig... 
angular typescript
- **Login **
- **Aufgaben adden**
- **Aufgabenliste erstellen**
- Ausblendung einer Aufgabe bis zu ihrer endgültigen Auslöscung (Hinter speicern button mehrere Routen)