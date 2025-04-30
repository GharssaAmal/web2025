<?php
    require_once('C:\xampp\htdocs\voyage\controller\voyageC.php');
    require_once('C:\xampp\htdocs\voyage\model\voyage.php');
$controller = new voyageC();
$listevoyage = $controller->listVoyages();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorldVenture - Nos Voyages</title>
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

    <div class="voyages-container">
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
            
            try {
                const response = await fetch('process_reservation.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    messageDiv.innerHTML = `<div class="success-message">${result.message}</div>`;
                    setTimeout(() => {
                        closeModal();
                        location.reload();
                    }, 2000);
                } else {
                    messageDiv.innerHTML = `<div class="error-message">${result.message}</div>`;
                }
            } catch (error) {
                messageDiv.innerHTML = '<div class="error-message">Une erreur est survenue. Veuillez réessayer.</div>';
            }
        }

        // Fermer le modal si on clique en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('reservationModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
 </body>
</html>
 