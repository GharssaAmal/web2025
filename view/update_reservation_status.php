<?php
require_once('../controller/ReservationC.php');
require_once('../controller/voyageC.php');
require_once('../model/Reservation.php');

header('Content-Type: application/json');

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_reservation']) || !isset($data['statut_reservation'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

$reservationC = new ReservationC();
$voyageC = new voyageC();

try {
    // Récupérer la réservation existante
    $reservation = $reservationC->showReservation($data['id_reservation']);
    
    if (!$reservation) {
        throw new Exception('Réservation non trouvée');
    }

    // Créer un objet Reservation avec les nouvelles données
    $updatedReservation = new Reservation(
        $data['id_reservation'],
        $reservation['id'],
        $reservation['prix'],
        $reservation['mode_paiement'],
        $data['statut_reservation'],
        $reservation['date_reservation']
    );

    // Mettre à jour la réservation
    $result = $reservationC->updateReservation($updatedReservation);

    if ($result) {
        // Récupérer le nombre de places mis à jour
        $voyage = $voyageC->showVoyage($reservation['id']);
        
        echo json_encode([
            'success' => true,
            'message' => 'Statut mis à jour avec succès',
            'places_updated' => true,
            'voyage_id' => $reservation['id'],
            'places_remaining' => $voyage['place']
        ]);
    } else {
        throw new Exception('Erreur lors de la mise à jour du statut');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 