<?php
require_once('../controller/ReservationC.php');

header('Content-Type: application/json');

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_reservation'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de réservation non fourni']);
    exit;
}

$reservationC = new ReservationC();

try {
    $reservationC->deleteReservation($data['id_reservation']);
    echo json_encode(['success' => true, 'message' => 'Réservation supprimée avec succès']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 