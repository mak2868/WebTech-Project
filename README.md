Namenskonvention zur Erstellung eines neuen Branchens: Blatt<Nr.>/Aufgabe<Nr.>/<Nummierierung des Branches einer Aufgabe eiens Aufgabenblattes> (bspw. "Blatt1/Aufgabe9/1)

Terminalanweisungen: 

Wie ziehe ich das Projekt auf meinen Rechner / in VSC? 
    - passenden Ordner auswählen, an welchem das Projekt lokal gepsiechert werden soll 
    - git clone https://github.com/mak2868/WebTech-Project.git

Wie kann ich mir den aktuellen Stand des gesamten Projekts holen?
    - git fetch (um die aktuellsten Änderungen des main Branches zu holen)
    - git merge origin/main (die Änderungen aus origin/main in deinen aktuellen Branch zu übernehmen)

Wie kann ich einen Branch erstellen?
    - git checkout main (wechselt in den main branch, falls es nicht der Fall ist)
    - git pull origin main (holt sich den aktuellsten Stand des main Branches)
    - git checkout -b <Name des Branches (siehe ganz oben)>
  
Wie kann ich meinen Stand pushen / veröffentlichen / speichern / comitten?
    über die UI: 
        - Änderungen speichern
        - Source Control auswählen
        - Veränderungen anschauen und stagen 
        - Commit-Nachricht hinzufügen (Was hast du verändert?) -> commit 
        - Sync-Button links unten betätigen

Wie kann ich einen Branch auschecken / mir ziehen?
    - git checkout <Branchname>

Wie kann ich einen Pull Request stellen / wann erstelle ich einen Pull Request und wozu?

    Was ist ein Pull Request (PR) und wozu dient er?

    Ein Pull Request (PR) wird verwendet, um Änderungen in einem Feature-Branch zur Überprüfung und späteren Integration in den Hauptbranch (main oder develop) vorzuschlagen.

    Ein PR wird in Collaborative-Workflows genutzt, z. B.:
    ✅ Code-Review → Andere Entwickler können den Code prüfen und Feedback geben.
    ✅ Fehlervermeidung → Bevor Änderungen in main gemerged werden, können Tests und Überprüfungen stattfinden.
    ✅ Teamkommunikation → Diskussion über Änderungen, bevor sie in den Hauptcode übernommen werden.


    Wann erstelle ich einen Pull Request?
    Ein PR sollte erstellt werden, wenn:
    ✔ Die Aufgabe fertig gestellt wurde 
    ✔ Dein Feature-Branch bereit zur Überprüfung ist.
    ✔ Du möchtest Feedback von Teamkollegen einholen.


    1. Änderungen committen & pushen (falls noch nicht geschehen)
    2. Auf GitHub gehen: 
       1. Gehe zum Repository auf GitHub.
       2. Klicke auf "Pull Requests" → "New Pull Request".
       3. Wähle deinen Branch, an welchem du zuletzt gearbeitet hast und abgeschlossen ist, als Source, der in main oder develop gemerged werden soll.
       4. Schreibe ggf. eine Beschreibung, warum dieser PR notwendig ist.
       5. Klicke auf "Create Pull Request".
    3. Feedback einholen & Änderungen umsetzen
    4. PR mergen (nach Freigabe)
