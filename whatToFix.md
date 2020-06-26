# What to fix



## ~~Backend~~

### ~~index.php (REST-API)~~

- Auskommentierten Code ggf. wenn nicht mehr notwendig löschen
- @David (alte Branches löschen wenn nicht mehr notwendig)





## Frontend 

- <u>**LOGIN**</u> (PRIO--> @David & @Andrey)
- ~~Aufgaben adden~~
- ~~Aufgabenliste erstellen~~
- Navigation
  - ~~extra Komponente für die Navigation? Wenn ja, wie greifen wir auf die anderen Komponenten zu, auf die sich die Navigation bezieht (tasklists, tasks, ...). Wenn nicht, wie halten wir dann den Content bündig zusammen und in welche Komponente integrieren (evtl. todolist?)?~~
  - Inhalt der Navigation:
    - Login (--> vor der Realisierung des Logins können wir hier einfach eine Auswahl machen, wie mit den Aufgabenlisten)
    - ~~Auswählbare Aufgabenlisten~~
    - "Allgemeine" Kontrollelemente (~~createTasklist~~, deleteTasklist, changeUserPassword, ...)
    - **WICHTIG: Die Navigation sollte irgendwie als einzelne Komponente implementiert werden** --> aktuell wurde die Navigation in todolist implementiert. Problem ist, dass Navigation kein childElement von todlist ist und dementsprechend die Datenübertragung schwer ist. Bei der Auswahl der Aufgabenlist müsste ja von der Nav.Komponente über die todolist Komponente auf die task Komponente zugegriffen werden 
- User-Feedback: (@David)
  - Man kann erledigte Aufgaben nicht verändern 
  - " nicht löschen
  - Status dropdown 
- Design (Angular Material)
  - Ausblendung einer Aufgabe bis zu ihrer endgültigen Auslöschung (Hinter speichern Button mehrere Routen)
- @David (alte Branches löschen wenn nicht mehr notwendig)
- ~~BUG: Tasklistenname wird nicht umbenannt~~