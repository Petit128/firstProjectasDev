// MultipleFiles/script.js

document.addEventListener('DOMContentLoaded', () => {
    const sidenav = document.getElementById('sidenav');
    const menuToggleBtn = document.getElementById('menuToggleBtn');
    const sidenavCloseBtn = document.getElementById('sidenavCloseBtn');
    const mainWrapper = document.getElementById('mainWrapper');
    const cartButton = document.getElementById('cartButton');
    const messageContainer = document.getElementById('messageContainer');

    function toggleSidenav() {
        sidenav.classList.toggle('active');
        mainWrapper.classList.toggle('shifted');
    }

    if (menuToggleBtn) {
        menuToggleBtn.addEventListener('click', toggleSidenav);
    }
    if (sidenavCloseBtn) {
        sidenavCloseBtn.addEventListener('click', toggleSidenav);
    }

    if (cartButton) {
        cartButton.addEventListener('click', () => {
            window.location.href = 'index.php?page=panier';
        });
    }

    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', () => {
            const platDetails = card.querySelector('.plat-details');
            if (platDetails) {
                if (platDetails.style.display === 'none' || platDetails.style.display === '') {
                    platDetails.style.display = 'block';
                } else {
                    platDetails.style.display = 'none';
                }
            }
        });
    });

    function displayMessage(type, text) {
        if (messageContainer) {
            messageContainer.innerHTML = `<p class="${type}">${text}</p>`;
            messageContainer.style.display = 'block';
            setTimeout(() => {
                messageContainer.style.display = 'none';
                messageContainer.innerHTML = '';
            }, 3000);
        }
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const id_menu_plat = button.getAttribute('data-id');
            const quantity = 1;

            fetch('index.php?page=add_to_cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id_menu_plat=' + encodeURIComponent(id_menu_plat) + '&quantite=' + encodeURIComponent(quantity)
            })
            .then(response => response.text())
            .then(text => {
                if (text.includes("Location: index.php?page=panier")) {
                    window.location.href = 'index.php?page=panier&message=Plat ajouté au panier!';
                } else if (text.includes("success")) {
                    displayMessage('message', 'Plat ajouté au panier!');
                } else {
                    displayMessage('error', 'Erreur lors de l\'ajout au panier: ' + text);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                displayMessage('error', 'Une erreur est survenue lors de l\'ajout au panier.');
            });
        });
    });
});

function editPlat(plat) {
    const form = document.querySelector('form[name="add_or_update_plat_form"]');
    if (form) {
        form.querySelector('input[name="id_plat"]').value = plat.id_plat;
        form.querySelector('input[name="nom_plat"]').value = plat.nom_plat;
        form.querySelector('textarea[name="description"]').value = plat.description;
        form.querySelector('select[name="type_plat"]').value = plat.type_plat;
        form.querySelector('select[name="categorie"]').value = plat.categorie;
        form.querySelector('input[name="prix"]').value = plat.prix;
        form.querySelector('input[name="calories"]').value = plat.calories;
        form.querySelector('input[name="stock"]').value = plat.stock;
        form.querySelector('select[name="statut"]').value = plat.statut;

        // Handle quota_max and menu_id, ensuring they are cleared if not present in plat object
        const quotaMaxInput = form.querySelector('input[name="quota_max"]');
        if (quotaMaxInput) {
            if (plat.quota_max) {
                quotaMaxInput.value = plat.quota_max;
            } else {
                quotaMaxInput.value = '';
            }
        }

        const menuIdSelect = form.querySelector('select[name="menu_id"]');
        if (menuIdSelect) {
            if (plat.id_menu) { // Use id_menu as per RepasModel::getAllMenuPlatsWithDetails
                menuIdSelect.value = plat.id_menu;
            } else {
                menuIdSelect.value = '';
            }
        }

        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.textContent = 'Modifier le Plat';
        }
    }
}


document.addEventListener('DOMContentLoaded', () => {
    // ... (autres écouteurs d'événements)

    function displayMessage(type, text) {
        if (messageContainer) {
            messageContainer.innerHTML = `<p class="${type}">${text}</p>`;
            messageContainer.style.display = 'block';
            setTimeout(() => {
                messageContainer.style.display = 'none';
                messageContainer.innerHTML = '';
            }, 3000);
        }
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation(); // Empêche l'événement de clic de la carte parente
            const id_menu_plat = button.getAttribute('data-id'); // Récupère l'ID du plat
            const quantity = 1; // Quantité par défaut

            fetch('index.php?page=add_to_cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id_menu_plat=' + encodeURIComponent(id_menu_plat) + '&quantite=' + encodeURIComponent(quantity)
            })
            .then(response => response.text())
            .then(text => {
                // Gère la réponse du serveur
                if (text.includes("Location: index.php?page=panier")) {
                    // Si le serveur redirige vers le panier, on redirige aussi
                    window.location.href = 'index.php?page=panier&message=Plat ajouté au panier!';
                } else if (text.includes("success")) {
                    // Si le serveur renvoie "success", affiche un message
                    displayMessage('message', 'Plat ajouté au panier!');
                } else {
                    // Sinon, affiche une erreur
                    displayMessage('error', 'Erreur lors de l\'ajout au panier: ' + text);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                displayMessage('error', 'Une erreur est survenue lors de l\'ajout au panier.');
            });
        });
    });
});

// Dans MultipleFiles/script.js

document.addEventListener('DOMContentLoaded', () => {
    // ... (votre code existant) ...

    // Gérer le clic sur le bouton "Voir Ticket"
    document.querySelectorAll('.btn-view-ticket').forEach(button => {
        button.addEventListener('click', (event) => {
            const reservationId = event.target.closest('tr').dataset.reservationId;
            // Ici, vous feriez un appel AJAX pour récupérer les détails complets du ticket
            // et les afficher dans une modale.
            // Pour l'exemple, nous allons juste afficher une alerte.
            alert(`Afficher les détails du ticket pour la réservation ID: ${reservationId}`);
            // Si vous avez une modale générique, vous pourriez faire :
            // openGenericModal('Détails du Ticket', 'Contenu HTML du ticket ici');
        });
    });

    // ... (le reste de votre code existant) ...
});


