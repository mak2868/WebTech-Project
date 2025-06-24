<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title id="pageTitle"></title>
    <title>Verwaltungsbereich</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">

    <!-- JS -->
    <!-- <script src="<?= BASE_URL ?>/js/navbar.js" defer></script> -->

</head>

<?php
// include __DIR__ . '/../layouts/navbar.php';
?>

<body>
    <div class="container">
        <h1>Hallo, Admin!</h1>
        <form>
            <label for="menu">Wählen Sie eine Option:</label>
            <select id="menu" name="menu">
                <option value="" disabled selected hidden>Bitte wählen...</option>
                <option value="benutzerverwaltung">Benutzerverwaltung</option>
                <option value="bestellverwaltung">Bestellverwaltung</option>
                <option value="hinzufuegen">Hinzufügen von neuen Elementen</option>
            </select>
        </form>

        <div class="sub-options" id="sub-options">
            <div class="hinzufuegen-option" id="hinzufuegen-options" style="display:none;">
                <h2>Hinzufügen von neuen Elementen</h2>
                <select>
                    <option value="" disabled selected hidden>Bitte wählen...</option>
                    <option value="ueberkategorie">Überkategorie</option>
                    <option value="unterkategorie">Unterkategorie</option>
                    <option value="produkte">Produkte</option>
                </select>
            </div>

        </div>
        <div id="adminContent"></div>
    </div>

    <script>
        const menuSelect = document.getElementById('menu');
        const adminContent = document.getElementById('adminContent');

        menuSelect.addEventListener('change', function () {
            // Die Optionen ausblenden
            adminContent.innerHTML = '';  // Leere Inhalte löschen

            // Die ausgewählte Option anzeigen
            if (menuSelect.value === 'benutzerverwaltung') {
                // AJAX-Anfrage an den Controller senden
                //  document.getElementById('adminContent').innerHTML = 'userList';
                fetch('index.php?page=admin-users')
                    .then(response => response.json())
                    .then(data => {
                        // Daten werden erfolgreich empfangen
                        console.log(data);  // Überprüfe die Daten im Browser-Console

                        if (data.length > 0) {
                            // HTML-Tabelle mit den Kopfzeilen
                            let userList = "<table border='1' style='width: 100%; margin-top: 20px;' id='userTable'>";
                            userList += "<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Phone</th><th>Geburtstag</th><th>Geschlecht</th><th>Vorname</th><th>Nachname</th><th>Erstellt am</th></tr></thead>";
                            userList += "<tbody>";

                            // Durchlaufe alle Benutzer und füge die entsprechenden Zeilen hinzu
                            data.forEach(user => {
                                userList += `<tr>
                                <td id="id">${user.id}</td>
                                <td>${user.username}</td>
                                <td>${user.email}</td>
                                <td>${user.phone}</td>
                                <td>${user.birthdate}</td>
                                <td>${user.gender}</td>
                                <td>${user.first_name}</td>
                                <td>${user.last_name}</td>
                                <td>${user.created_at}</td>
                                <td id="delete">Datensatz löschen</td>
                             </tr>`;
                            });

                            userList += "</tbody></table>";
                            document.getElementById('adminContent').innerHTML = userList;  // Benutzerliste in die Seite einfügen
                        } else {
                            document.getElementById('adminContent').innerHTML = "<p>Keine Benutzer gefunden.</p>";
                        }
                    })
                    .catch(error => {
                        console.error("Fehler beim Laden der Benutzer:", error);
                        document.getElementById('adminContent').innerHTML = "<p>Fehler beim Laden der Benutzer.</p>";
                    });
            }
        }
        )

        document.getElementById('adminContent').addEventListener('click', function (event) {
            // Wenn die Tabelle existiert und eine Zeile angeklickt wird
            const table = document.getElementById('userTable');  // Stellen sicher, dass die Tabelle existiert

            if (!table) return; // Wenn die Tabelle noch nicht existiert, keine Aktion durchführen

            const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent);

            // Wenn eine Zelle in der Tabelle angeklickt wird
            if (event.target.tagName === 'TD') {
                const row = event.target.parentElement;  // Zeile (TR)
                const idCell = row.querySelector('td[id="id"]');  // ID-Zelle der aktuellen Zeile finden
                const deleteCell = row.querySelector('td[id="delete"]');

                const idValue = idCell ? idCell.textContent : null;  // ID-Wert der Zelle (z.B. "1")

                // Wenn die angeklickte Zelle eine "editierbare" Zelle ist, machen wir sie editierbar
                if (event.target !== idCell) {
                    if (event.target == deleteCell) {
                        fetch('index.php?page=delete-user', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: idValue,              // ID der betroffenen Zeile
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert(`Löschen erfolgreich!`);
                                } else {
                                    alert(`Fehler beim Löschen: ${data.error}`);
                                }
                            })
                            .catch(error => {
                                console.error('Fehler beim AJAX-Request:', error);
                                alert('Es ist ein Fehler aufgetreten.');
                            });;
                    } else {
                        const originalValue = event.target.textContent;  // Wert der angeklickten Zelle

                        // Erstelle ein Input-Feld
                        const inputField = document.createElement('input');
                        inputField.value = originalValue;
                        event.target.innerHTML = '';  // Lösche den aktuellen Inhalt der Zelle
                        event.target.appendChild(inputField);

                        // Wenn der Benutzer mit der Eingabe fertig ist, speichern wir die Änderung
                        inputField.addEventListener('blur', function () {
                            const newValue = inputField.value;
                            event.target.textContent = newValue;

                            const columnName = headers[event.target.cellIndex];  // Spaltenname anhand des Indexes
                            // Ausgabe der Änderung mit dem Spaltennamen statt Index
                            console.log(`Neuer Wert für ${columnName}: ${newValue} (ID der Zeile: ${idValue})`);

                            // Hier kannst du auch die Änderung an den Server senden, z.B. mit einer AJAX-Anfrage
                        });

                        // Falls der Benutzer die Eingabe mit Enter abschließt:
                        inputField.addEventListener('keydown', function (e) {
                            if (e.key === 'Enter') {
                                const newValue = inputField.value;
                                event.target.textContent = newValue;

                                const columnName = headers[event.target.cellIndex];  // Spaltenname anhand des Indexes
                                fetch('index.php?page=update-user-field', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        id: idValue,              // ID der betroffenen Zeile
                                        field: columnName,        // Spaltenname
                                        value: newValue           // Neuer Wert
                                    })
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert(`Änderung erfolgreich gespeichert!`);
                                        } else {
                                            alert(`Fehler beim Speichern: ${data.error}`);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Fehler beim AJAX-Request:', error);
                                        alert('Es ist ein Fehler aufgetreten.');
                                    });
                            }
                        });
                    }
                }
            }
        });




    </script>
</body>

</html>