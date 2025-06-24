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

        let isUserAddressEditable; // oder false, je nachdem welche Felder angezeigt werden sollen
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
                            let userList = `
<style>
    .user-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .user-card {
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 15px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    .user-card:hover {
        transform: translateY(-4px);
    }
    .user-field {
        margin: 8px 0;
    }
    .editable {
        background-color: #fff8dc;
        border-radius: 4px;
        padding: 2px 4px;
        cursor: text;
        display: inline-block;
        min-width: 50px;
    }
    .delete-btn {
        margin-top: 10px;
        padding: 6px 12px;
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .delete-btn:hover {
        background-color: #c0392b;
    }
</style>
<div class="user-grid">
`;

                               data.forEach(user => {
                userList += `<div class="user-card" data-id="${user.id}">`;

            
                    userList += `
            <div class="user-field" data-field="id"><strong>ID:</strong> <span>${user.id}</span></div>
            <div class="user-field" data-field="username"><strong>Username:</strong> <span class="editable" contenteditable="true">${user.username}</span></div>
            <div class="user-field" data-field="email"><strong>Email:</strong> <span class="editable" contenteditable="true">${user.email}</span></div>
            <div class="user-field" data-field="phone"><strong>Telefon:</strong> <span class="editable" contenteditable="true">${user.phone}</span></div>
            <div class="user-field" data-field="birthdate"><strong>Geburtstag:</strong> <span class="editable" contenteditable="true">${user.birthdate}</span></div>
            <div class="user-field" data-field="gender"><strong>Geschlecht:</strong> <span class="editable" contenteditable="true">${user.gender}</span></div>
            <div class="user-field" data-field="first_name"><strong>Vorname:</strong> <span class="editable" contenteditable="true">${user.first_name}</span></div>
            <div class="user-field" data-field="last_name"><strong>Nachname:</strong> <span class="editable" contenteditable="true">${user.last_name}</span></div>
            <div class="user-field" data-field="created_at"><strong>Erstellt am:</strong> <span class="editable" contenteditable="true">${user.created_at}</span></div>
        `;
                
                    userList += `
            <div class="user-field" id="address" data-field="type"><strong>Type:</strong> <span class="editable" contenteditable="true">${user.type || ''}</span></div>
            <div class="user-field" id="address" data-field="street"><strong>Straße:</strong> <span class="editable" contenteditable="true">${user.street || ''}</span></div>
            <div class="user-field" id="address" data-field="city"><strong>Stadt:</strong> <span class="editable" contenteditable="true">${user.city || ''}</span></div>
            <div class="user-field" id="address" data-field="postal_code"><strong>PLZ:</strong> <span class="editable" contenteditable="true">${user.postal_code || ''}</span></div>
            <div class="user-field" id="address" data-field="country"><strong>Land:</strong> <span class="editable" contenteditable="true">${user.country || ''}</span></div>
            <div class="user-field" id="address" data-field="created_at"><strong>Erstellt am:</strong> <span class="editable" contenteditable="true">${user.ua_created_at || ''}</span></div>
            <div class="user-field" id="address" data-field="updated_at"><strong>Aktualisiert am:</strong> <span class="editable" contenteditable="true">${user.ua_updated_at || ''}</span></div>
        `;
                

                userList += `<button class="delete-btn">Datensatz löschen</button></div>`;
            }); // Ende forEach

            userList += `</div>`; // Ende user-grid
            adminContent.innerHTML = userList;

            // Rest deiner Event Listener Code hier...
        } // Ende if (data.length > 0)
    });
                    

                // Event Listener nur für die editable Spans
                document.querySelectorAll('.editable').forEach(field => {
                    field.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const newValue = field.textContent.trim();
                            const userCard = field.closest('.user-card');
                            const fieldName = field.closest('.user-field').getAttribute('data-field');
                            const userId = userCard.getAttribute('data-id');
                            isUserAddressEditable = field.closest('#address') !== null;
                            
                            fetch('index.php?page=update-user-field', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id: userId,
                                    field: fieldName,
                                    value: newValue,
                                    isAddressField: isUserAddressEditable  // Hier gibst du mit, ob es user_address oder user Feld ist
                                })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Änderung gespeichert!');
                                    } else {
                                        alert('Fehler beim Speichern: ' + data.error);
                                    }
                                })
                                .catch(error => {
                                    console.error('Fehler beim AJAX-Request:', error);
                                    alert('Serverfehler.');
                                });

                            field.blur();
                        }
                    });
                });

                // Delete Buttons
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.closest('.user-card').getAttribute('data-id');
                        if (confirm(`Soll Benutzer mit ID ${userId} gelöscht werden?`)) {
                            fetch('index.php?page=delete-user', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ id: userId })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Benutzer gelöscht');
                                        this.closest('.user-card').remove();
                                    } else {
                                        alert('Fehler beim Löschen: ' + data.error);
                                    }
                                });
                        }
                    });
                });
            }
        })
                   
    </script>
</body>

</html>