<?php
require_once('../controller/ReservationC.php');

header('Content-Type: application/json');

if (!isset($_GET['voyage_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID du voyage non fourni']);
    exit;
}

$voyage_id = intval($_GET['voyage_id']);
$reservationC = new ReservationC();

try {
    $reservations = $reservationC->getReservationsByVoyage($voyage_id);
    echo json_encode($reservations);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?> 