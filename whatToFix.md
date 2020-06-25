# What to fix





## Backend



### index.php (REST-API)

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