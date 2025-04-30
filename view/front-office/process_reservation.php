<?php
require_once('../../controller/ReservationC.php');
require_once('../../model/Reservation.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];

    try {
        // Récupération et validation des données
        $id = isset($_POST['voyage_id']) ? intval($_POST['voyage_id']) : 0;
        $prix = isset($_POST['voyage_prix']) ? filter_var($_POST['voyage_prix'], FILTER_VALIDATE_FLOAT) : false;
        $mode_paiement = isset($_POST['mode_paiement']) ? htmlspecialchars($_POST['mode_paiement']) : '';
        $date_reservation = date('Y-m-d H:i:s');
        $statut_reservation = 'En attente'; // Statut initial

        // Validation des données
        if ($id <= 0) {
            throw new Exception('ID du voyage invalide');
        }
        
        // Validation améliorée du prix
        if ($prix === false || $prix <= 0) {
            throw new Exception('Le prix doit être un nombre positif valide');
        }
        
        if (empty($mode_paiement)) {
            throw new Exception('Mode de paiement requis');
        }

        // Vérification de la disponibilité
        $reservationC = new ReservationC();
        $places_disponibles = $reservationC->checkDisponibilite($id);
        
        if ($places_disponibles <= 0) {
            throw new Exception('Désolé, plus de places disponibles pour ce voyage');
        }

        // Création de la réservation avec le constructeur
        $reservation = new Reservation(
            null, // id_reservation est null car il sera généré par la base de données
            $id,
            $prix,
            $mode_paiement,
            $statut_reservation,
            $date_reservation
        );

        // Ajout de la réservation
        $result = $reservationC->addReservation($reservation);

        if ($result === "Réservation effectuée avec succès !") {
            $response['success'] = true;
            $response['message'] = $result;
        } else {
            throw new Exception($result);
        }

    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
    exit;
}

// Si la méthode n'est pas POST
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
?> 