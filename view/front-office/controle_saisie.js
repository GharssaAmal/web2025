document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    form.addEventListener('submit', function (event) {
        let valid = true;

        // Réinitialiser les messages d'erreur
        resetErrors();

        // Validation du titre
        const titre = document.getElementById('titre');
        if (!titre.value.trim()) {
            valid = false;
            document.getElementById('titreError').textContent = "Veuillez entrer un titre.";
            document.getElementById('titreError').style.display = "block";
        }

        // Validation de la description
        const description = document.getElementById('description');
        if (!description.value.trim()) {
            valid = false;
            document.getElementById('descriptionError').textContent = "Veuillez entrer une description.";
            document.getElementById('descriptionError').style.display = "block";
        }

        // Validation de la date de départ
        const dateDepart = document.getElementById('date_depart');
        if (!dateDepart.value) {
            valid = false;
            document.getElementById('dateDepartError').textContent = "Veuillez sélectionner une date de départ.";
            document.getElementById('dateDepartError').style.display = "block";
        }

        // Validation de la date de retour
        const dateRetour = document.getElementById('date_retour');
        if (!dateRetour.value) {
            valid = false;
            document.getElementById('dateRetourError').textContent = "Veuillez sélectionner une date de retour.";
            document.getElementById('dateRetourError').style.display = "block";
        } else if (dateDepart.value && dateRetour.value < dateDepart.value) {
            valid = false;
            document.getElementById('dateRetourError').textContent = "La date de retour doit être après la date de départ.";
            document.getElementById('dateRetourError').style.display = "block";
        }

        // Validation du prix
        const prix = document.getElementById('prix');
        if (!prix.value || isNaN(prix.value) || Number(prix.value) <= 0) {
            valid = false;
            document.getElementById('prixError').textContent = "Veuillez entrer un prix valide.";
            document.getElementById('prixError').style.display = "block";
        }

        // Validation du nombre de places
        const place = document.getElementById('place');
        if (!place.value || isNaN(place.value) || Number(place.value) < 1) {
            valid = false;
            document.getElementById('placeError').textContent = "Veuillez entrer un nombre de places valide.";
            document.getElementById('placeError').style.display = "block";
        }

        // Validation du type
        const type = document.getElementById('type');
        if (!type.value || type.selectedIndex === 0) {
            valid = false;
            document.getElementById('typeError').textContent = "Veuillez choisir un type de voyage.";
            document.getElementById('typeError').style.display = "block";
        }

        if (!valid) {
            event.preventDefault();
        }
    });

    // Réinitialisation des messages d'erreur
    function resetErrors() {
        const errorSpans = document.querySelectorAll('.error');
        errorSpans.forEach(span => {
            span.textContent = "";
            span.style.display = "none";
        });
    }
});
