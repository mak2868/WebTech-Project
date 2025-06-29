/**
 * admin.js
 * zuständig für die clientseitige Steuerung + Visualisierung der admin.php - Seite
 * @author Marvin Kunz
 */

const menuSelect = document.getElementById('menu');
const adminContent = document.getElementById('adminContent');
let unterSelectListenerAdded = false;

/**
 * Eventlistener für das erste Auswahlmenü (Benutzerverwaltung, Bestellverwaltung oder Hinzufügen von neuen Elementen)
 */
menuSelect.addEventListener('change', function () {

    // Alle möglichweise zuvor eingeblendeten Inhalte werden ausgeblendet
    adminContent.innerHTML = '';
    document.getElementById('hinzufuegen-options').style.display = 'none';
    document.getElementById('new-parent-category-form').style.display = 'none';
    document.getElementById("allOrdersContainer").style.display = 'none';
    document.getElementById("new-product-form").style.display = 'none';

    // Aufruf unterschiedlichster Funktionen, abhängig davon, welche Auswahl im Selector getroffen wurde
    if (menuSelect.value === 'benutzerverwaltung') {
        displayBenutzerverwaltung();
    } else if (menuSelect.value === 'bestellverwaltung') {
        displayBestellverwaltung();
    } else if (menuSelect.value === 'support') {
        displaySupport();
    } else if (menuSelect.value === 'hinzufuegen') {
        displayHinzufuegen();
    }

})

/**
 * Funktion, die sich um die Benutzerverwaltung kümmert (Visualisierung aller Benutzer + Anstoßen der Speicherung von vorgenommen Änderungen)
 */
function displayBenutzerverwaltung() {
    fetch('index.php?page=admin-users') // Visualisierung
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
                            <div class="user-field" id="address" data-field="created_at"><strong>Erstellt am:</strong> <input type="text" value="${safe(user.addCreated_at)}" /></div>
                            <div class="user-field" id="address" data-field="updated_at"><strong>Aktualisiert am:</strong> <input type="text" value="${safe(user.addUpdated_at)}" /></div>

                            <button class="delete-btn">Datensatz löschen</button>
                        </div>`;
                });

                userList += `</div>`;
                adminContent.innerHTML = userList;

                document.querySelectorAll('.user-field input').forEach(field => {
                    field.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const newValue = field.value.trim();
                            const userCard = field.closest('.user-card');
                            const fieldName = field.closest('.user-field').getAttribute('data-field');
                            const userId = userCard.getAttribute('data-id');
                            const isAddressField = field.closest('#address') !== null;

                            fetch('index.php?page=admin-update-user-field', { // Speicherung (Auslöser: siehe Eventlistener Enter-Button)
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
                                        displayBenutzerverwaltung();
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

                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.closest('.user-card').getAttribute('data-id');
                        if (confirm(`Soll Benutzer mit ID ${userId} gelöscht werden?`)) {
                            fetch('index.php?page=admin-delete-user', { // Löschen (Auslöser: siehe Eventlistener delete-Button)
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
        })
};

/**
 * Funktion, die sich um die Bestellverwaltung kümmert (Visualisierung aller Bestellungen + Anstoßen der Speicherung von vorgenommen Statusänderungen)
 */
function displayBestellverwaltung() {
    const container = document.getElementById("allOrdersContainer");
    container.style.display = 'block';

    fetch("index.php?page=admin-get-all-orders")
        .then(res => {
            if (!res.ok) throw new Error("Netzwerkfehler");
            return res.json();
        })
        .then(data => {
            container.innerHTML = ""; // leeren

            let currentUser = null;
            data.forEach(order => {
                if (currentUser !== order.user_id) {
                    if (currentUser !== null) {
                        container.appendChild(document.createElement('hr'));
                    }
                    currentUser = order.user_id;

                    const h3 = document.createElement('h3');
                    h3.textContent = `Benutzer-ID: ${currentUser}`;
                    container.appendChild(h3);
                }

                console.log("order_id:", order.order_id);
                const orderBox = document.createElement('div');
                orderBox.className = 'order-box';
                orderBox.dataset.orderId = order.order_id;

                order.items.forEach(item => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'order-item';

                    const img = document.createElement('img');
                    img.src = BASE_URL + '/' + item.product_image.replace(/^\/+/, '');
                    img.alt = 'Produktbild';

                    const infoDiv = document.createElement('div');
                    infoDiv.className = 'item-info';
                    infoDiv.innerHTML = `<strong>${item.product_name}</strong><br>${item.quantity} x ${item.size}g`;

                    itemDiv.appendChild(img);
                    itemDiv.appendChild(infoDiv);
                    orderBox.appendChild(itemDiv);
                });

                const metaDiv = document.createElement('div');
                metaDiv.className = 'order-meta';

                const statusSelect = document.createElement('select');
                statusSelect.name = 'order_status';
                statusSelect.className = 'order-status-select';

                const statusOptions = ['in Bearbeitung', 'bezahlt', 'versendet', 'storniert', 'abgeschlossen'];
                statusOptions.forEach(status => {
                    const option = document.createElement('option');
                    option.value = status;
                    option.textContent = capitalize(status);
                    if (status === order.status) {
                        option.selected = true;
                    }
                    statusSelect.appendChild(option);
                });

                metaDiv.innerHTML = `
                    <p><strong>Order-ID:</strong> ${order.order_id}</p>
                    <p><strong>Datum:</strong> ${order.order_date}</p>
                    <p><strong>Versandadresse:</strong> ${order.shipping_address}</p>
                    <p style="text-align:right;"><strong>Gesamt:</strong> ${Number(order.total).toFixed(2)} €</p>
                `;

                const statusWrapper = document.createElement('p');
                statusWrapper.innerHTML = `<strong>Status:</strong> `;
                statusWrapper.appendChild(statusSelect);

                metaDiv.insertBefore(statusWrapper, metaDiv.children[1]);

                orderBox.appendChild(metaDiv);
                container.appendChild(orderBox);
            });

            container.dataset.loaded = "true";
        })
        .then(() => {
            // Alle Status-Selects mit EventListener versehen
            document.querySelectorAll(".order-status-select").forEach(select => {
                select.addEventListener("change", function () {
                    const newStatus = this.value;
                    const orderBox = this.closest('.order-box');
                    const orderId = orderBox.dataset.orderId;



                    console.log("Neuer Status:", newStatus);
                    console.log("oid:", orderId);


                    // Ajax an den Server schicken
                    fetch("index.php?page=admin-update-order-status", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            new_status: newStatus
                        })
                    })
                        .then(res => {
                            if (!res.ok) throw new Error("Fehler beim Aktualisieren des Status");
                            return res.json();
                        })
                        .then(result => {
                            if (result.success) {
                                console.log("Status erfolgreich geändert");
                                displayBestellverwaltung();
                            } else {
                                throw new Error("Antwort ohne Erfolg");
                            }
                        })
                        .catch(error => {
                            console.error("Statusänderung fehlgeschlagen:", error);
                            alert("Status konnte nicht aktualisiert werden.");
                        });
                });
            });
        })
        .catch(error => {
            container.innerHTML = "<p style='color:red;'>Fehler beim Laden der Bestellungen.</p>";
            console.error(error);
        });
}

/**
 * Hilfsfunktion für Großschreibung
 * @param {String für die Konvertierung} s 
 * @returns String mit großem Anfangsbuchstaben
 */
function capitalize(s) {
    return s.charAt(0).toUpperCase() + s.slice(1);
}

/**
 * Funktion, die sich um die Verwaltung der Supporttickets kümmert (Visualisierung aller Supporttickets + Anstoßen der Speicherung von vorgenommen Statusänderungen)
 */
function displaySupport() {
    const container = document.getElementById("allOrdersContainer");
    container.style.display = 'block';

    fetch("index.php?page=admin-get-all-support-tickets")
        .then(res => {
            if (!res.ok) throw new Error("Netzwerkfehler");
            return res.json();
        })
        .then(data => {
            container.innerHTML = ""; 

            let currentSupportTicket = null;

            data.forEach(ticket => {
                if (currentSupportTicket !== ticket.user_id) {
                    if (currentSupportTicket !== null) {
                        container.appendChild(document.createElement('hr'));
                    }
                    currentSupportTicket = ticket.user_id;

                    const h3 = document.createElement('h3');
                    h3.textContent = `Benutzer-ID: ${currentSupportTicket}`;
                    container.appendChild(h3);
                }

                const ticketBox = document.createElement('div');
                ticketBox.className = 'ticket-box';
                ticketBox.dataset.ticketid = ticket.id;

                console.log("tid:" + ticket.id);

                const metaDiv = document.createElement('div');
                metaDiv.className = 'ticket-meta';

                const statusSelect = document.createElement('select');
                statusSelect.name = 'ticket_status';
                statusSelect.className = 'ticket-status-select';

                const statusOptions = ['open', 'in_progress', 'closed'];
                statusOptions.forEach(status => {
                    const option = document.createElement('option');
                    option.value = status;
                    option.textContent = capitalize(status);
                    if (status === ticket.status) {
                        option.selected = true;
                    }
                    statusSelect.appendChild(option);
                });

                metaDiv.innerHTML = `
                    <p><strong>Ticket-ID:</strong> ${ticket.id}</p>
                    <p><strong>Datum:</strong> ${ticket.created_at}</p>
                    <p><strong>Betreff:</strong> ${ticket.subject}</p>
                    <strong>Nachricht:</strong>
                    <p>${ticket.message.replace(/\r?\n/g, '<br>')}</p>
                `;

                const statusWrapper = document.createElement('p');
                statusWrapper.innerHTML = `<strong>Status:</strong> `;
                statusWrapper.appendChild(statusSelect);

                metaDiv.insertBefore(statusWrapper, metaDiv.children[1]);

                ticketBox.appendChild(metaDiv);
                container.appendChild(ticketBox);
            });

            container.dataset.loaded = "true";
        })
        .then(() => {
            document.querySelectorAll(".ticket-status-select").forEach(select => {
                select.addEventListener("change", function () {
                    const newStatus = this.value;
                    const ticketBox = this.closest('.ticket-box');
                    const ticketId = ticketBox.dataset.ticketid;

                    console.log("Neuer Status:", newStatus);

                    fetch("index.php?page=admin-update-ticket-status", { // Änderung des Ticketstatus (Starten des Speicherungsprozesses)
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            ticket_id: ticketId,
                            new_status: newStatus
                        })
                    })
                        .then(res => {
                            if (!res.ok) throw new Error("Fehler beim Aktualisieren des Status");
                            return res.json();
                        })
                        .then(result => {
                            if (result.success) {
                                console.log("Status erfolgreich geändert");
                                displaySupport();
                            } else {
                                throw new Error("Antwort ohne Erfolg");
                            }
                        })
                        .catch(error => {
                            console.error("Statusänderung fehlgeschlagen:", error);
                            alert("Status konnte nicht aktualisiert werden.");
                        });
                });
            });
        })
        .catch(error => {
            container.innerHTML = "<p style='color:red;'>Fehler beim Laden der Tickets.</p>";
            console.error(error);
        });
}

/**
 * Visualisierung des Auswahlsmenü (Überkategorie, Unterkategorie, Produkt) + Aufrufen der entsprechenden Funktion
 */
function displayHinzufuegen() {
    const hinzufuegenOptions = document.getElementById('hinzufuegen-options');
    hinzufuegenOptions.style.display = 'block';

    hideEverythingFromHinzufuegen();

    const unterSelect = document.getElementById('hinzufuegen-select');

    unterSelect.value = ''; 
    if (!unterSelectListenerAdded) {
        unterSelectListenerAdded = true;
        unterSelect.addEventListener('change', () => {

            hideEverythingFromHinzufuegen();

            if (unterSelect.value === 'ueberkategorie') {
                displayHinzufuegenUeberkategorie();
            } else if (unterSelect.value === 'unterkategorie') {
                displayHinzufuegenUnterkategorie();
            } else if (unterSelect.value === 'produkte') {
                displayHinzufuegenProdukte();
            }
        })
    }
}

/**
 * Funktion, die sich um den Bereich kümmert, in welchem neue Überkategorien erstellt werden können (Visualisierung + Anstoßen der Speicherung von vorgenommen Änderungen)
 */
function displayHinzufuegenUeberkategorie() {

    showParentCategories(); // Visualisierung bereits existierender Kategorien

    const containerU = document.getElementById('new-parent-category-form');
    containerU.style.display = 'block';

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
                fetch("index.php?page=admin-add-parent-category", { // Anlegen einer neuen Überkategorie (Starten des Speicherungsprozesses)
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
                            showParentCategories();
                        } else {
                            alert("Fehler: " + (data.message || "Unbekannter Fehler"));
                        }
                    })
                    .catch(error => {
                        console.error("Fetch-Fehler:", error);
                        alert("Beim Hinzufügen ist ein Fehler aufgetreten.");
                    });

            });
        }
    }
}

/**
 * Visualisierung aller Überkategorien
 */
function showParentCategories() {
    fetch('index.php?page=admin-show-parent-category') // Anzeigen aller Überkategorien
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
}

/**
 * Funktion, die sich um den Bereich kümmert, in welchem neue Unterkategorien erstellt werden können (Visualisierung + Anstoßen der Speicherung von vorgenommen Änderungen)
 * @returns (nur im Fehlerfall)
 */
async function displayHinzufuegenUnterkategorie() {

    const parentCategories = await showAllCategories();

    if (!Array.isArray(parentCategories)) {
        alert("Fehler beim Laden der Hauptkategorien.");
        return;
    }

    const containerU = document.getElementById('new-category-form');
    containerU.style.display = 'block';

    console.log(parentCategories);
    let selectBox = document.getElementById('parent-category-select');
    selectBox.innerHTML = '';

    for (let i = 0; i < parentCategories.length; i++) {
        const selectItem = document.createElement('option');
        const parentCategoryName = parentCategories[i].name;

        selectItem.textContent = parentCategoryName;

        selectBox.appendChild(selectItem);
    }

    const addButton = document.getElementById("addCategoryBtn");

    if (addButton) {
        if (!addButton.dataset.listenerAdded) {
            addButton.dataset.listenerAdded = "true";
            addButton.addEventListener("click", function () {
                const input = document.getElementById("newCategoryInput");
                const categoryName = input.value.trim();

                const parentCategorySelector = document.getElementById("parent-category-select");
                const parentCategoryName = parentCategorySelector.value;

                if (categoryName === "") {
                    alert("Bitte gib einen Kategorienamen ein.");
                    return;
                }

                console.log("fetch kommt");
                fetch("index.php?page=admin-add-category", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ parentCategory: parentCategoryName, name: categoryName })
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
                            displayHinzufuegenUnterkategorie();
                        } else {
                            alert("Fehler: " + (data.message || "Unbekannter Fehler"));
                        }
                    })
                    .catch(error => {
                        console.error("Fetch-Fehler:", error);
                        alert("Beim Hinzufügen ist ein Fehler aufgetreten.");
                    });

            });
        }
    }
}

/**
 * Visualisierung aller Kategorien
 * @returns Kategorien
 */
async function showAllCategories() {
    const response = await fetch('index.php?page=admin-show-all-categories');
    const text = await response.text();
    let data;

    try {
        data = JSON.parse(text);
    } catch (e) {
        console.error('Ungültiges JSON:', e);
        adminContent.innerHTML = 'Fehler: Ungültige Server-Antwort';
        return null;
    }

    if (!Array.isArray(data) || data.length !== 2) {
        console.error('Erwartet ein Array mit zwei Unter-Arrays:', data);
        adminContent.innerHTML = 'Fehler: Unerwartete Server-Antwort';
        return null;
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

    return mainCategories;
}

/**
 * Funktion, die sich um den Bereich kümmert, in welchem neue Produkte erstellt werden können (Visualisierung + Anstoßen der Speicherung von vorgenommen Änderungen)
 */
async function displayHinzufuegenProdukte() {
   
    // Visualisierung aller benltigten Elemente
    const containerProduct = document.getElementById('new-product-form');
    containerProduct.style.display = 'block';

    const productDetailInputs = document.getElementById('product-details');
    productDetailInputs.style.display = 'none';

    const categoryNameList = await getCategories();
    console.log(categoryNameList);

    let selectBox = document.getElementById('product-select');
    selectBox.innerHTML = '';

    const defaultOption = document.createElement('option');
    defaultOption.textContent = 'Bitte wählen...';
    defaultOption.disabled = true;  
    defaultOption.selected = true;  
    selectBox.appendChild(defaultOption);

    for (let i = 0; i < categoryNameList.length; i++) {
        const selectItem = document.createElement('option');

        selectItem.textContent = categoryNameList[i];

        selectBox.appendChild(selectItem);
    }

    selectBox.addEventListener('change', async e => {
        const value = e.target.value;

        const boolIsPulver = await isPulver(value);

        productDetailInputs.style.display = 'block';
        document.getElementById('aminoAcids-input').style.display = 'block';
        document.getElementById('tipDiv').style.display = 'block';
        document.getElementById('recipes-input').style.display = 'block';

        if (!boolIsPulver) {
            document.getElementById('aminoAcids-input').style.display = 'none';
            document.getElementById('recipes-input').style.display = 'none';
            document.getElementById('tipDiv').style.display = 'none';
        } else if (value === "Isolat") {
            document.getElementById('recipes-input').style.display = 'none';
        }
    });

    const addProductBtn = document.getElementById("addProductBtn");

    // Anstoßen des Prozesses zur Übermittlung des neuen Produktes
    if (addProductBtn) {
        if (!addProductBtn.dataset.listenerAdded) {
            document.getElementById("addProductBtn").addEventListener("click", () => {
                const form = document.querySelector('#product-details');
                const data = {};

                form.querySelectorAll('input, textarea, select').forEach(el => {
                    const isVisible = el.offsetParent !== null; 
                    if (el.name && !el.disabled && isVisible) {
                        data[el.name] = el.value;
                    }
                });

                const select = document.getElementById("product-select");
                if (select) data["category"] = select.value.toLowerCase();

                const newProductValue = document.getElementById("newProductInput")?.value;
                if (newProductValue) data["name"] = newProductValue;


                // Senden der Eingaben als JSON
                fetch("index.php?page=admin-add-product", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert("Produkt erfolgreich hinzugefügt!");
                        } else {
                            alert("Fehler beim Hinzufügen: " + (result.message || "Unbekannter Fehler"));
                        }
                    })
                    .catch(error => {
                        console.error("Fehler beim Senden:", error);
                        alert("Netzwerkfehler oder Serverproblem.");
                    });
            });
        }
    }
}

/**
 * gibt alle Unterkategorien zurück
 * @returns Unterkategorien (Namen)
 */
async function getCategories() {
    const response = await fetch('index.php?page=admin-get-categories');
    const text = await response.text();
    let categories;
    let categoryList;
    let categoriesName = []; // Hier definieren wir categoriesName außerhalb der try-catch

    try {
        categories = JSON.parse(text);

        // Zugriff auf das innere Array
        categoryList = categories[0];

        categoryList.forEach(categorie => {
            if (categorie.name) {
                categoriesName.push(categorie.name);
            }
        });
    } catch (e) {
        console.error('Ungültiges JSON:', e);
        adminContent.innerHTML = 'Fehler: Ungültige Server-Antwort';
        return null;
    }

    return categoriesName;
}

/**
 * Funktion, die alle Visualisierungen des Hinzufügen-Bereichs ausblendet
 */
function hideEverythingFromHinzufuegen() {

    const containerCategory = document.getElementById('new-category-form');
    containerCategory.style.display = 'none';

    const containerParentCategory = document.getElementById('new-parent-category-form');
    containerParentCategory.style.display = 'none';

    const containerProduct = document.getElementById('new-product-form');
    containerProduct.style.display = 'none';

    adminContent.innerHTML = '';

}

/**
 * Ermittlung, ob eine Kategorie ein Pulver ist oder nicht (-> Anstoßen des entsprechenden Controllers)
 * @param {Kategorie} value 
 * @returns true: ist ein Pulver; false: ist kein Pulver
 */
async function isPulver(value) {
    const response = await fetch(`index.php?page=admin-is-pulver&value=${encodeURIComponent(value)}`);
    const data = await response.json();

    return data.isPulver === true;
}