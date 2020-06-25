# What to fix



## ~~Backend~~

### ~~index.php (REST-API)~~

- Auskommentierten Code ggf. wenn nicht mehr notwendig löschen

- ~~Login --> compare input with DB-User~~

- ~~Überprüfung, ob DELETE task noch funktioniert. Ich ergänze "s" bei:~~

  ```php
  $task = $tasklist->xownTasksList[$args['taskId']];
  ```






## Frontend 

- interaction clientseitig... 
angular typescript
- **Login **
- ~~Aufgaben adden~~
- **Aufgabenliste erstellen**
- Ausblendung einer Aufgabe bis zu ihrer endgültigen Auslöschung (Hinter speichern Button mehrere Routen)
- **WICHTIG: NAVIGATION !**
  - extra Komponente für die Navigation? Wenn ja, wie greifen wir auf die anderen Komponenten zu, auf die sich die Navigation bezieht (tasklists, tasks, ...). Wenn nicht, wie halten wir dann den Content bündig zusammen und in welche Komponente integrieren (evtl. todolist?)?
  - Inhalt der Navigation:
    - Login (--> vor der Realisierung des Logins können wir hier einfach eine Auswahl machen, wie mit den Aufgabenlisten)
    - Auswählbare Aufgabenlisten
    - "Allgemeine" Kontrollelemente (createTasklist, deleteTasklist, changeUserPassword, ...)