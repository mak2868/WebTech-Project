const menuSelect = document.getElementById('menu');
const adminContent = document.getElementById('adminContent');
let unterSelectListenerAdded = false;

menuSelect.addEventListener('change', function () {
    adminContent.innerHTML = '';
    document.getElementById('hinzufuegen-options').style.display = 'none';
    document.getElementById('new-parent-category-form').style.display = 'none';

    if (menuSelect.value === 'benutzerverwaltung') {
        fetch('index.php?page=admin-users')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let userList = `<div class="user-grid">`;

                    data.forEach(user => {
                        userList += `<div class="user-card" data-id="${user.id}">`;

                        const safe = val => val ?? '';

                        userList += `
                            <div class="user-field" data-field="id"><strong>ID:</strong> <div>${safe(user.id)}</div></div>
                            <div class="user-field" data-field="username"><strong>Username:</strong> <input type="text" value="${safe(user.username)}" /></div>
                            <div class="user-field" data-field="email"><strong>Email:</strong> <input type="text" value="${safe(user.email)}" /></div>
                            <div class="user-field" data-field="phone"><strong>Telefon:</strong> <input type="text" value="${safe(user.phone)}" /></div>
                            <div class="user-field" data-field="birthdate"><strong>Geburtstag:</strong> <input type="text" value="${safe(user.birthdate)}" /></div>
                            <div class="user-field" data-field="gender"><strong>Geschlecht:</strong> <input type="text" value="${safe(user.gender)}" /></div>
                            <div class="user-field" data-field="first_name"><strong>Vorname:</strong> <input type="text" value="${safe(user.first_name)}" /></div>
                            <div class="user-field" data-field="last_name"><strong>Nachname:</strong> <input type="text" value="${safe(user.last_name)}" /></div>
                            <div class="user-field" data-field="created_at"><strong>Erstellt am:</strong> <input type="text" value="${safe(user.created_at)}" /></div>

                            <div class="user-field" id="address" data-field="type"><strong>Type:</strong> <input type="text" value="${safe(user.type)}" /></div>
                            <div class="user-field" id="address" data-field="street"><strong>Straße:</strong> <input type="text" value="${safe(user.street)}" /></div>
                            <div class="user-field" id="address" data-field="city"><strong>Stadt:</strong> <input type="text" value="${safe(user.city)}" /></div>
                            <div class="user-field" id="address" data-field="postal_code"><strong>PLZ:</strong> <input type="text" value="${safe(user.postal_code)}" /></div>
                            <div class="user-field" id="address" data-field="country"><strong>Land:</strong> <input type="text" value="${safe(user.country)}" /></div>
                            <div class="user-field" id="address" data-field="created_at"><strong>Erstellt am:</strong> <input type="text" value="${safe(user.ua_created_at)}" /></div>
                            <div class="user-field" id="address" data-field="updated_at"><strong>Aktualisiert am:</strong> <input type="text" value="${safe(user.ua_updated_at)}" /></div>

                            <button class="delete-btn">Datensatz löschen</button>
                        </div>`;
                    });

                    userList += `</div>`;
                    adminContent.innerHTML = userList;

                    // Speichern mit Enter
                    document.querySelectorAll('.user-field input').forEach(field => {
                        field.addEventListener('keydown', function (e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                const newValue = field.value.trim();
                                const userCard = field.closest('.user-card');
                                const fieldName = field.closest('.user-field').getAttribute('data-field');
                                const userId = userCard.getAttribute('data-id');
                                const isAddressField = field.closest('#address') !== null;
                                console.log(isAddressField);

                                fetch('index.php?page=admin-update-user-field', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({
                                        id: userId,
                                        field: fieldName,
                                        value: newValue,
                                        isAddressField: isAddressField
                                    })
                                })
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Änderung gespeichert!');
                                        } else {
                                            alert('Fehler beim Speichern: ' + data.error);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('AJAX-Fehler:', error);
                                        alert('Serverfehler.');
                                    });

                                field.blur();
                            }
                        });
                    });

                    // Löschen
                    document.querySelectorAll('.delete-btn').forEach(button => {
                        button.addEventListener('click', function () {
                            const userId = this.closest('.user-card').getAttribute('data-id');
                            if (confirm(`Soll Benutzer mit ID ${userId} gelöscht werden?`)) {
                                fetch('index.php?page=admin-delete-user', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ id: userId })
                                })
                                    .then(res => res.json())
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
            });
    } else if (menuSelect.value === 'hinzufuegen') {
        const hinzufuegenOptions = document.getElementById('hinzufuegen-options');
        hinzufuegenOptions.style.display = 'block';
        document.getElementById('new-parent-category-form').style.display = 'none';


        const unterSelect = document.getElementById('hinzufuegen-select');

        unterSelect.value = ''; // Zurücksetzen, falls schon was gewählt
        if (!unterSelectListenerAdded) {
            unterSelectListenerAdded = true;
            unterSelect.addEventListener('change', () => {
                if (unterSelect.value === 'ueberkategorie') {
                    fetch('index.php?page=admin-show-parent-category')
                        .then(response => response.text())
                        .then(text => {
                            console.log('Raw response text:', text);
                            let data;
                            try {
                                data = JSON.parse(text);
                            } catch (e) {
                                console.error('Ungültiges JSON:', e);
                                adminContent.innerHTML = 'Fehler: Ungültige Server-Antwort';
                                return;
                            }

                            if (!Array.isArray(data)) {
                                console.error('Erwartet ein Array, aber bekommen:', data);
                                adminContent.innerHTML = 'Fehler: Server-Antwort ist kein Array';
                                return;
                            }

                            // Jetzt kannst du sicher foreach nutzen
                            let html = `
            <table class="admin-table">
                <thead>
                    <tr><th>ID</th><th>Name</th></tr>
                </thead>
                <tbody>
        `;

                            data.forEach(entry => {
                                html += `
                <tr>
                    <td>${entry.id}</td>
                    <td>${entry.name}</td>
                </tr>
            `;
                            });

                            html += `</tbody></table>`;
                            adminContent.innerHTML = html;

                            const container = document.getElementById('new-parent-category-form');
                            container.style.display = 'block';



                        });

                    const addButton = document.getElementById("addParentCategoryBtn");

                    if (addButton) {
                        if (!addButton.dataset.listenerAdded) {
                            addButton.dataset.listenerAdded = "true";
                            addButton.addEventListener("click", function () {
                                const input = document.getElementById("newParentCategoryInput");
                                const parentCategoryName = input.value.trim();

                                if (parentCategoryName === "") {
                                    alert("Bitte gib einen Kategorienamen ein.");
                                    return;
                                }

                                console.log("fetch kommt");
                                fetch("index.php?page=admin-add-parent-category", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({ name: parentCategoryName })
                                })
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error("Fehler beim Senden der Kategorie.");
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        console.log("Antwort vom Server:", data);

                                        if (data.success) {
                                            alert("Kategorie erfolgreich hinzugefügt!");
                                            input.value = "";
                                        } else {
                                            alert("Fehler: " + (data.message || "Unbekannter Fehler"));
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Fetch-Fehler:", error);
                                        alert("Beim Hinzufügen ist ein Fehler aufgetreten.");
                                    });
                                fetch('index.php?page=admin-show-parent-category')
                                    .then(response => response.text())
                                    .then(text => {
                                        console.log('Raw response text:', text);
                                        let data;
                                        try {
                                            data = JSON.parse(text);
                                        } catch (e) {
                                            console.error('Ungültiges JSON:', e);
                                            adminContent.innerHTML = 'Fehler: Ungültige Server-Antwort';
                                            return;
                                        }

                                        if (!Array.isArray(data)) {
                                            console.error('Erwartet ein Array, aber bekommen:', data);
                                            adminContent.innerHTML = 'Fehler: Server-Antwort ist kein Array';
                                            return;
                                        }

                                        // Jetzt kannst du sicher foreach nutzen
                                        let html = `
            <table class="admin-table">
                <thead>
                    <tr><th>ID</th><th>Name</th></tr>
                </thead>
                <tbody>
        `;

                                        data.forEach(entry => {
                                            html += `
                <tr>
                    <td>${entry.id}</td>
                    <td>${entry.name}</td>
                </tr>
            `;
                                        });

                                        html += `</tbody></table>`;
                                        adminContent.innerHTML = html;

                                        const container = document.getElementById('new-parent-category-form');
                                        container.style.display = 'block';



                                    });
                            });

                        }
                    }
                } else if (unterSelect.value === 'unterkategorie') {
                    
                    fetch('index.php?page=admin-show-all-categories')
                        .then(response => response.text())
                        .then(text => {
                            console.log('Raw response text:', text);
                            let data;
                            try {
                                data = JSON.parse(text);
                            } catch (e) {
                                console.error('Ungültiges JSON:', e);
                                adminContent.innerHTML = 'Fehler: Ungültige Server-Antwort';
                                return;
                            }

                            if (!Array.isArray(data) || data.length !== 2) {
                                console.error('Erwartet ein Array mit zwei Unter-Arrays:', data);
                                adminContent.innerHTML = 'Fehler: Unerwartete Server-Antwort';
                                return;
                            }

                            const [mainCategories, subCategories] = data;

                            let html = `
                            <div style="display: flex; gap: 40px; align-items: flex-start;">
                            <div>
                             <h4>Hauptkategorien</h4>
                             <table class="admin-table">
                            <thead>
                               <tr><th>ID</th><th>Name</th></tr>
                          </thead>
                          <tbody>
                             `;

                            mainCategories.forEach(entry => {
                                html += `
                            <tr>
                                <td>${entry.id}</td>
                                <td>${entry.name}</td>
                            </tr>
            `;
                            });

                            html += `
                        </tbody>
                    </table>
                </div>

                <div>
                    <h4>Unterkategorien</h4>
                    <table class="admin-table">
                        <thead>
                            <tr><th>ID</th><th>Name</th><th>Parent ID</th></tr>
                        </thead>
                        <tbody>
        `;

                            subCategories.forEach(entry => {
                                html += `
                            <tr>
                                <td>${entry.id}</td>
                                <td>${entry.name}</td>
                                <td>${entry.parent_id}</td>
                            </tr>
            `;
                            });

                            html += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;

                            adminContent.innerHTML = html;
                        });

                        const addButton = document.getElementById("addParentCategoryBtn");

                    if (addButton) {
                        if (!addButton.dataset.listenerAdded) {
                            addButton.dataset.listenerAdded = "true";
                            addButton.addEventListener("click", function () {
                    const input = document.getElementById("newCategoryInput");
                    const categoryName = input.value.trim();

                    if (categoryName === "") {
                        alert("Bitte gib einen Kategorienamen ein.");
                        return;
                    }

                    fetch("index.php?page=admin-add-parent-category", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ name: categoryName })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Fehler beim Senden der Kategorie.");
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log("Antwort vom Server:", data);

                            if (data.success) {
                                alert("Kategorie erfolgreich hinzugefügt!");
                                input.value = "";
                            } else {
                                alert("Fehler: " + (data.message || "Unbekannter Fehler"));
                            }
                        })
                        .catch(error => {
                            console.error("Fetch-Fehler:", error);
                            alert("Beim Hinzufügen ist ein Fehler aufgetreten.");
                        });


                })
            
            }})}}
        

        }

    }
});
