<?php
    require_once('../../controller/VoyageC.php');
    require_once('../../model/voyage.php');
$controller = new VoyageC();
$listevoyage = $controller->listVoyages();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorldVenture - Nos Voyages</title>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <!-- FullCalendar Scripts -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation des variables globales
            window.calendar = null;

            // Fonction pour formater une date en format ISO
            window.formatDate = function(dateStr) {
                const [day, month, year] = dateStr.split('-');
                return `${year}-${month}-${day}`;
            };
        });
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
            background-color: #f8fafc;
        }

        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background-image: url('background.jpg');
            background-size: cover;
            background-position: center;
            filter: brightness(0.5) blur(2px);
            z-index: -2;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(to bottom right, rgba(10, 10, 30, 0.4), rgba(0, 70, 140, 0.4));
            z-index: -1;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.5rem 2rem;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 100;
        }

        .logo {
            width: 150px;
            height: auto;
            margin-top: 0.5rem;
        }

        .slogan {
            font-size: 3.2rem;
            font-weight: 900;
            color: #cceeff;
            text-shadow: 2px 2px 8px #000000;
            text-align: center;
            margin-top: 6rem;
            position: relative;
            padding: 0 2rem;
        }

        .main-nav {
            display: flex;
            justify-content: center;
            margin: 4rem auto 2rem;
            width: 100%;
        }

        .main-nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .main-nav-links a {
            background: linear-gradient(135deg, #1e90ff, #0099cc);
            padding: 1.2rem 2.5rem;
            border-radius: 2rem;
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            border: 2px solid #003366;
        }

        .main-nav-links a:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            background: linear-gradient(135deg, #00bfae, #0099cc);
        }

        .login-btn {
            background: linear-gradient(135deg, #1e90ff, #0099cc);
            padding: 1rem 2rem;
            border-radius: 2rem;
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            border: 2px solid #003366;
            margin-top: 0.5rem;
        }

        .login-btn:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            background: linear-gradient(135deg, #00bfae, #0099cc);
        }

        .voyages-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
            padding: 2rem;
            max-width: 1400px;
            margin: 8rem auto 2rem;
            position: relative;
            z-index: 1;
        }

        .voyage-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 0;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 45, 114, 0.1);
            transition: all 0.4s ease;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        .voyage-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 45, 114, 0.2);
        }

        .voyage-header {
            background: linear-gradient(135deg, #1e90ff, #0099cc);
            padding: 1.5rem;
            color: white;
            position: relative;
        }

        .voyage-header h3 {
            color: white;
            font-size: 1.6rem;
            margin: 0;
            padding-right: 80px;
        }

        .voyage-type {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            backdrop-filter: blur(5px);
        }

        .voyage-info {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .voyage-info p {
            margin: 0;
            line-height: 1.6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .voyage-info i {
            color: #0099cc;
            width: 20px;
        }

        .voyage-info strong {
            color: #0a4c8c;
            min-width: 140px;
        }

        .prix-badge {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-weight: bold;
            display: inline-block;
            margin-left: auto;
        }

        .places-badge {
            background: linear-gradient(135deg, #ff7675, #fd79a8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            font-weight: bold;
            display: inline-block;
            margin-left: auto;
        }

        .dates-container {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .date-block {
            text-align: center;
        }

        .date-block span {
            display: block;
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .date-block strong {
            color: #0a4c8c;
            font-size: 1.1rem;
        }

        .voyage-footer {
            padding: 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .reserver-btn {
            background: linear-gradient(135deg, #1e90ff, #0099cc);
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .reserver-btn:hover {
            background: linear-gradient(135deg, #00bfae, #0099cc);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 153, 204, 0.3);
        }

        .reserver-btn i {
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .voyages-container {
                grid-template-columns: 1fr;
                padding: 1rem;
                margin-top: 6rem;
            }

            .voyage-header h3 {
                font-size: 1.4rem;
                padding-right: 0;
            }

            .voyage-type {
                position: static;
                margin-top: 1rem;
                display: inline-block;
            }

            .dates-container {
                flex-direction: column;
                gap: 1rem;
            }

            .date-block {
                text-align: left;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
        }

        /* Styles pour le modal de réservation */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 45, 114, 0.2);
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal.active {
            display: block;
        }

        .modal.active .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .modal-title {
            color: #0a4c8c;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: #ef4444;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #0a4c8c;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3e9bff;
            box-shadow: 0 0 0 3px rgba(62, 155, 255, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            background-color: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%230a4c8c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
        }

        .reservation-summary {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            color: #64748b;
        }

        .summary-item strong {
            color: #0a4c8c;
        }

        .submit-reservation {
            background: linear-gradient(135deg, #1e90ff, #0099cc);
            color: white;
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-reservation:hover {
            background: linear-gradient(135deg, #00bfae, #0099cc);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 153, 204, 0.3);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .success-message {
            color: #10b981;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* Styles pour la barre de recherche et le tri */
        .search-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-box {
            flex: 1;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            border: 2px solid #e2e8f0;
            border-radius: 30px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .type-select {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            border: 2px solid #e2e8f0;
            border-radius: 30px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 200px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%230a4c8c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
        }

        .search-box:focus, .type-select:focus {
            outline: none;
            border-color: #1e90ff;
            box-shadow: 0 4px 20px rgba(30, 144, 255, 0.2);
        }

        .no-results {
            text-align: center;
            color: #64748b;
            padding: 2rem;
            font-size: 1.1rem;
            grid-column: 1 / -1;
        }

        /* Styles pour le bouton calendrier */
        .calendar-btn {
            background: linear-gradient(135deg, #1e90ff, #0099cc);
            color: white;
            padding: 1rem 2rem;
            border-radius: 30px;
            border: none;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 1rem;
        }

        .calendar-btn:hover {
            background: linear-gradient(135deg, #00bfae, #0099cc);
            transform: translateY(-2px);
        }

        /* Styles pour le modal calendrier */
        .calendar-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .calendar-modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 1000px;
            margin: 50px auto;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 45, 114, 0.2);
        }

        .calendar-modal.active {
            display: block;
        }

        .close-calendar {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            z-index: 1;
        }

        .close-calendar:hover {
            color: #ef4444;
        }

        #calendar {
            margin-top: 1rem;
        }

        .fc-event {
            padding: 4px 8px !important;
            margin: 2px 0 !important;
            border-radius: 4px !important;
        }

        .fc-daygrid-event {
            white-space: normal !important;
            align-items: flex-start !important;
        }

        .fc-event-title {
            font-weight: bold !important;
            font-size: 0.9em !important;
        }

        .fc-event-time {
            font-size: 0.8em !important;
        }

        .fc-col-header-cell {
            background-color: #f8fafc !important;
            padding: 8px 0 !important;
        }

        .fc-daygrid-day-number {
            font-size: 1em !important;
            font-weight: 500 !important;
            color: #1e293b !important;
        }

        .fc-day-today {
            background-color: rgba(30, 144, 255, 0.1) !important;
        }

        .download-pdf-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .download-pdf-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }

        .download-pdf-btn i {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="overlay"></div>
    
    <header>
        <img src="logo.png" alt="WorldVenture Logo" class="logo">
        <a href="#" class="login-btn" onclick="handleClick('login')">Se connecter</a>
    </header>

    <h1 class="slogan">Découvrez nos voyages exceptionnels</h1>

    <nav class="main-nav">
        <div class="main-nav-links">
            <a href="#" onclick="handleClick('destinations')">Destinations</a>
            <a href="inex.html">Nos offres</a>
            <a href="#" onclick="handleClick('blog')">Blog</a>
            <a href="#" onclick="handleClick('contact')">Contact</a>
            <a href="#" onclick="handleClick('reclamation')">Réclamation</a>
        </div>
    </nav>

    <div class="search-container">
        <input type="text" 
               class="search-box" 
               placeholder="Rechercher un voyage par nom..." 
               id="searchVoyage"
               onkeyup="searchVoyages()">
        <select class="type-select" id="typeFilter" onchange="filterVoyages()">
            <option value="">Tous les types</option>
            <option value="Culturel">Culturel</option>
            <option value="Aventure">Aventure</option>
            <option value="Détente">Détente</option>
            <option value="Gastronomique">Gastronomique</option>
        </select>
        <button class="calendar-btn" onclick="openCalendarModal()">
            <i class="fas fa-calendar-alt"></i>
            Voir le calendrier
        </button>
    </div>

    <div class="voyages-container" id="voyagesContainer">
<?php foreach ($listevoyage as $row) {
    $voyage = new voyage(
        $row['id'],
        $row['destination'],
        $row['titre'],
        $row['description'],
        $row['date_depart'],
        $row['date_retour'],
        $row['prix'],
        $row['place'],
        $row['type']
    );
?>
        <div class="voyage-card">
            <div class="voyage-header">
        <h3><?php echo htmlspecialchars($voyage->getTitre()); ?></h3>
                <span class="voyage-type"><?php echo htmlspecialchars($voyage->getType()); ?></span>
            </div>
            <div class="voyage-info">
                <p>
                    <i class="fas fa-map-marker-alt"></i>
                    <strong>Destination :</strong> 
                    <?php echo htmlspecialchars($voyage->getDestination()); ?>
                </p>
                <p>
                    <i class="fas fa-info-circle"></i>
                    <strong>Description :</strong>
                    <?php echo htmlspecialchars($voyage->getDescription()); ?>
                </p>
                
                <div class="dates-container">
                    <div class="date-block">
                        <span>Départ</span>
                        <strong><?php echo htmlspecialchars($voyage->getDateDepart()); ?></strong>
                    </div>
                    <div class="date-block">
                        <span>Retour</span>
                        <strong><?php echo htmlspecialchars($voyage->getDateRetour()); ?></strong>
                    </div>
                </div>

                <p>
                    <i class="fas fa-tag"></i>
                    <strong>Prix :</strong>
                    <span class="prix-badge"><?php echo htmlspecialchars($voyage->getPrix()); ?> TND</span>
                </p>
                <p>
                    <i class="fas fa-users"></i>
                    <strong>Places disponibles :</strong>
                    <span class="places-badge"><?php echo htmlspecialchars($voyage->getPlace()); ?></span>
                </p>
            </div>
            <div class="voyage-footer">
                <form onsubmit="event.preventDefault(); openReservationModal(
                    '<?php echo $voyage->getId(); ?>', 
                    '<?php echo htmlspecialchars($voyage->getTitre()); ?>', 
                    '<?php echo htmlspecialchars($voyage->getDestination()); ?>', 
                    '<?php echo htmlspecialchars($voyage->getDateDepart()); ?>', 
                    '<?php echo htmlspecialchars($voyage->getDateRetour()); ?>', 
                    '<?php echo htmlspecialchars($voyage->getPrix()); ?>'
                );">
                    <button type="submit" class="reserver-btn">
                        <i class="fas fa-plane"></i>
                        Réserver maintenant
                    </button>
        </form>
            </div>
    </div>
<?php } ?>
</div>

    <!-- Modal de réservation -->
    <div id="reservationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Réserver votre voyage</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <form id="reservationForm" onsubmit="submitReservation(event)">
                <input type="hidden" id="voyage_id" name="voyage_id">
                <input type="hidden" id="voyage_prix" name="voyage_prix">
                
                <div class="reservation-summary">
                    <div class="summary-item">
                        <span>Destination:</span>
                        <strong id="summary_destination"></strong>
                    </div>
                    <div class="summary-item">
                        <span>Dates:</span>
                        <strong id="summary_dates"></strong>
                    </div>
                    <div class="summary-item">
                        <span>Prix par personne:</span>
                        <strong id="summary_prix"></strong>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="mode_paiement">Mode de paiement</label>
                    <select class="form-select" id="mode_paiement" name="mode_paiement" required>
                        <option value="">Choisir un mode de paiement</option>
                        <option value="1">Carte bancaire</option>
                        <option value="2">PayPal</option>
                        <option value="3">Virement bancaire</option>
                        <option value="4">Espèces</option>
                    </select>
                </div>

                <button type="submit" class="submit-reservation">
                    Confirmer la réservation
                </button>
                <div id="reservationMessage"></div>
            </form>
        </div>
    </div>

    <!-- Modal Calendrier -->
    <div id="calendarModal" class="calendar-modal">
        <div class="calendar-modal-content">
            <button class="close-calendar" onclick="closeCalendarModal()">&times;</button>
            <div id="calendar"></div>
        </div>
    </div>

    <script>
        function handleClick(section) {
            console.log('Clicked on: ' + section);
            // Ajoutez ici la logique de navigation
        }

        function openReservationModal(voyageId, titre, destination, dateDepart, dateRetour, prix) {
            document.getElementById('voyage_id').value = voyageId;
            document.getElementById('voyage_prix').value = prix;
            document.getElementById('summary_destination').textContent = destination;
            document.getElementById('summary_dates').textContent = `${dateDepart} - ${dateRetour}`;
            document.getElementById('summary_prix').textContent = prix + ' TND';
            
            document.getElementById('reservationModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('reservationModal').classList.remove('active');
        }

        async function submitReservation(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const messageDiv = document.getElementById('reservationMessage');
            
            console.log('Début de la soumission de réservation');
            console.log('Données du formulaire:', Object.fromEntries(formData));
            
            try {
                console.log('Envoi de la requête au serveur...');
                const response = await fetch('process_reservation.php', {
                    method: 'POST',
                    body: formData
                });
                
                console.log('Réponse reçue du serveur');
                const result = await response.json();
                console.log('Résultat:', result);

                if (result.success) {
                    console.log('Réservation réussie, ID:', result.reservation_id);
                    // Créer le message de succès avec le bouton de téléchargement
                    const successHtml = `
                        <div class="success-message">
                            ${result.message}
                            <div style="margin-top: 15px;">
                                <a href="generate_pdf.php?reservation_id=${result.reservation_id}" 
                                   class="download-pdf-btn" 
                                   target="_blank"
                                   onclick="generatePDF(${result.reservation_id}); return false;">
                                    <i class="fas fa-file-pdf"></i>
                                    Télécharger la confirmation PDF
                                </a>
                            </div>
                        </div>
                    `;
                    messageDiv.innerHTML = successHtml;

                    // Fermer le modal après 5 secondes
                    setTimeout(() => {
                        console.log('Fermeture du modal et rechargement de la page');
                        closeModal();
                        location.reload();
                    }, 5000);
                } else {
                    console.error('Erreur de réservation:', result.message);
                    messageDiv.innerHTML = `
                        <div class="error-message">
                            ${result.message}
                            <div class="debug-info" style="font-size: 0.8em; margin-top: 10px; color: #666;">
                                Détails de l'erreur: ${JSON.stringify(result)}
                            </div>
                        </div>`;
                }
            } catch (error) {
                console.error('Erreur technique détaillée:', error);
                messageDiv.innerHTML = `
                    <div class="error-message">
                        Une erreur technique est survenue. Veuillez réessayer.
                        <div class="debug-info" style="font-size: 0.8em; margin-top: 10px; color: #666;">
                            Message d'erreur: ${error.message}
                            Stack trace: ${error.stack}
                        </div>
                    </div>`;
            }
        }

        function generatePDF(reservationId) {
            // Create status indicator
            const statusDiv = document.createElement('div');
            statusDiv.style.position = 'fixed';
            statusDiv.style.bottom = '20px';
            statusDiv.style.right = '20px';
            statusDiv.style.padding = '10px';
            statusDiv.style.background = 'rgba(0,0,0,0.8)';
            statusDiv.style.color = 'white';
            statusDiv.style.borderRadius = '5px';
            statusDiv.style.zIndex = '9999';
            statusDiv.innerHTML = 'Génération du PDF en cours...';
            document.body.appendChild(statusDiv);

            // Make the AJAX request with the correct path
            const baseUrl = window.location.pathname.split('/view/')[0];
            fetch(`${baseUrl}/view/front-office/generate_pdf.php?reservation_id=${reservationId}`)
                .then(response => {
                    // First check if the response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Raw response:', text);
                            throw new Error(`Response was not JSON: ${contentType}. Raw response logged to console.`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        statusDiv.style.background = 'rgba(0,128,0,0.8)';
                        statusDiv.innerHTML = 'PDF généré avec succès!';
                        
                        // Create download link with correct path
                        const link = document.createElement('a');
                        link.href = `${baseUrl}/${data.filename}`;
                        link.download = data.filename.split('/').pop();
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        // Remove status indicator after 3 seconds
                        setTimeout(() => {
                            statusDiv.remove();
                        }, 3000);
                    } else {
                        statusDiv.style.background = 'rgba(255,0,0,0.8)';
                        let errorMessage = data.message;
                        if (data.debug) {
                            console.error('Debug info:', data.debug);
                            errorMessage += '\n\nDétails techniques:';
                            if (data.debug.php_errors) {
                                errorMessage += '\nErreurs PHP: ' + data.debug.php_errors;
                            }
                            if (data.debug.output) {
                                errorMessage += '\nSortie: ' + data.debug.output;
                            }
                            if (data.debug.file) {
                                errorMessage += '\nFichier: ' + data.debug.file + ' (ligne ' + data.debug.line + ')';
                            }
                        }
                        statusDiv.style.whiteSpace = 'pre-wrap';
                        statusDiv.innerHTML = `Erreur: ${errorMessage}`;
                        
                        // Remove error message after 20 seconds
                        setTimeout(() => {
                            statusDiv.remove();
                        }, 20000);
                    }
                })
                .catch(error => {
                    statusDiv.style.background = 'rgba(255,0,0,0.8)';
                    statusDiv.style.whiteSpace = 'pre-wrap';
                    statusDiv.innerHTML = `Erreur lors de la génération du PDF:\n${error.message}`;
                    console.error('Error:', error);
                    
                    // Remove error message after 20 seconds
                    setTimeout(() => {
                        statusDiv.remove();
                    }, 20000);
                });
        }

        // Fermer le modal si on clique en dehors
        window.onclick = function(event) {
            const reservationModal = document.getElementById('reservationModal');
            const calendarModal = document.getElementById('calendarModal');
            if (event.target === reservationModal) {
                closeModal();
            }
            if (event.target === calendarModal) {
                closeCalendarModal();
            }
        }

        function filterVoyages() {
            const searchInput = document.getElementById('searchVoyage').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value;
            const voyageCards = document.querySelectorAll('.voyage-card');
            let hasResults = false;

            voyageCards.forEach(card => {
                const title = card.querySelector('.voyage-header h3').textContent.toLowerCase();
                const destination = card.querySelector('.voyage-info p:first-child').textContent.toLowerCase();
                const type = card.querySelector('.voyage-type').textContent;
                
                const matchesSearch = title.includes(searchInput) || destination.includes(searchInput);
                const matchesType = typeFilter === '' || type === typeFilter;
                
                if (matchesSearch && matchesType) {
                    card.style.display = '';
                    hasResults = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Afficher un message si aucun résultat
            const noResultsMsg = document.querySelector('.no-results');
            if (!hasResults) {
                if (!noResultsMsg) {
                    const message = document.createElement('div');
                    message.className = 'no-results';
                    message.textContent = 'Aucun voyage ne correspond à vos critères';
                    document.getElementById('voyagesContainer').appendChild(message);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Modifier la fonction searchVoyages pour utiliser filterVoyages
        function searchVoyages() {
            filterVoyages();
        }

        // Fonction pour remplir dynamiquement les types de voyage uniques
        function populateTypeFilter() {
            const typeFilter = document.getElementById('typeFilter');
            const types = new Set();
            
            // Récupérer tous les types existants
            document.querySelectorAll('.voyage-type').forEach(typeElement => {
                types.add(typeElement.textContent.trim());
            });

            // Vider et reremplir le select
            typeFilter.innerHTML = '<option value="">Tous les types</option>';
            
            // Ajouter les options de type
            types.forEach(type => {
                const option = document.createElement('option');
                option.value = type;
                option.textContent = type;
                typeFilter.appendChild(option);
            });
        }

        // Appeler la fonction au chargement de la page
        document.addEventListener('DOMContentLoaded', populateTypeFilter);

        let calendar = null;

        function openCalendarModal() {
            document.getElementById('calendarModal').classList.add('active');
            initializeCalendar();
        }

        function closeCalendarModal() {
            document.getElementById('calendarModal').classList.remove('active');
        }

        function initializeCalendar() {
            if (window.calendar) {
                window.calendar.destroy();
            }

            const calendarEl = document.getElementById('calendar');
            window.calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                initialDate: new Date(), // Définir la date initiale à aujourd'hui
                events: getVoyageEvents(),
                eventClick: function(info) {
                    alert(`
                        Voyage: ${info.event.title}
                        Destination: ${info.event.extendedProps.destination}
                        Départ: ${info.event.extendedProps.formattedStart}
                        Retour: ${info.event.extendedProps.formattedEnd}
                        Prix: ${info.event.extendedProps.prix}
                        Places disponibles: ${info.event.extendedProps.places}
                    `);
                },
                eventDidMount: function(info) {
                    info.el.title = `
                        ${info.event.title}
                        ${info.event.extendedProps.destination}
                        Du: ${info.event.extendedProps.formattedStart}
                        Au: ${info.event.extendedProps.formattedEnd}
                    `;
                },
                height: 'auto',
                firstDay: 1,
                titleFormat: { year: 'numeric', month: 'long' },
                dayHeaderFormat: { weekday: 'long', day: 'numeric', month: 'numeric' },
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: 'Mois',
                    week: 'Semaine'
                },
                views: {
                    dayGridMonth: {
                        dayHeaderFormat: { weekday: 'long', day: 'numeric', month: 'numeric' }
                    },
                    dayGridWeek: {
                        dayHeaderFormat: { weekday: 'long', day: 'numeric', month: 'numeric', year: 'numeric' }
                    }
                }
            });

            window.calendar.render();
        }

        function getVoyageEvents() {
            const events = [];
            document.querySelectorAll('.voyage-card').forEach(card => {
                try {
                    const title = card.querySelector('.voyage-header h3').textContent;
                    const destination = card.querySelector('.voyage-info p:first-child').textContent.split(':')[1].trim();
                    const dateDepart = card.querySelector('.date-block:first-child strong').textContent.trim();
                    const dateRetour = card.querySelector('.date-block:last-child strong').textContent.trim();
                    const prix = card.querySelector('.prix-badge').textContent;
                    const places = card.querySelector('.places-badge').textContent;
                    const type = card.querySelector('.voyage-type').textContent;

                    console.log('Dates brutes:', { dateDepart, dateRetour });

                    // Les dates sont déjà au format YYYY-MM-DD, pas besoin de conversion
                    const startDate = dateDepart;
                    const endDate = dateRetour;

                    console.log('Dates à utiliser:', { startDate, endDate });

                    if (startDate && endDate) {
                        // Créer une date de fin qui inclut le dernier jour
                        const endDateObj = new Date(endDate);
                        endDateObj.setDate(endDateObj.getDate() + 1);
                        const adjustedEndDate = endDateObj.toISOString().split('T')[0];

                        const event = {
                            title: title,
                            start: startDate,
                            end: adjustedEndDate,
                            color: getColorForType(type),
                            extendedProps: {
                                destination: destination,
                                prix: prix,
                                places: places,
                                type: type,
                                formattedStart: dateDepart,
                                formattedEnd: dateRetour
                            },
                            allDay: true
                        };

                        console.log('Événement créé:', event);
                        events.push(event);
                    }
                } catch (error) {
                    console.error('Erreur lors de la création d\'un événement:', error);
                }
            });

            console.log('Événements générés:', events);
            return events;
        }

        function getColorForType(type) {
            const colors = {
                'Culturel': '#FF6B6B',
                'Aventure': '#4ECDC4',
                'Détente': '#45B7D1',
                'Gastronomique': '#96CEB4'
            };
            return colors[type] || '#1e90ff';
        }
    </script>
 </body>
</html>
 