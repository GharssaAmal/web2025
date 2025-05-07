<?php
// Activer l'affichage des erreurs dans la réponse JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Fonction pour capturer les erreurs PHP
function errorHandler($errno, $errstr, $errfile, $errline) {
    global $response;
    if (!isset($response)) {
        $response = [
            'success' => false,
            'message' => 'Erreur PHP',
            'logs' => [],
            'error_details' => []
        ];
    }
    
    $error_type = match($errno) {
        E_ERROR => 'E_ERROR',
        E_WARNING => 'E_WARNING',
        E_PARSE => 'E_PARSE',
        E_NOTICE => 'E_NOTICE',
        E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_USER_ERROR => 'E_USER_ERROR',
        E_USER_WARNING => 'E_USER_WARNING',
        E_USER_NOTICE => 'E_USER_NOTICE',
        default => 'UNKNOWN'
    };

    $error_detail = [
        'type' => $error_type,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ];

    $response['error_details'][] = $error_detail;
    $response['logs'][] = debug("Erreur PHP détectée", $error_detail);
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

set_error_handler("errorHandler");

// Capturer les erreurs fatales
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $error_type = match($error['type']) {
            E_ERROR => 'E_ERROR',
            E_PARSE => 'E_PARSE',
            E_CORE_ERROR => 'E_CORE_ERROR',
            E_COMPILE_ERROR => 'E_COMPILE_ERROR',
            default => 'FATAL_ERROR'
        };

        $response = [
            'success' => false,
            'message' => 'Erreur PHP fatale',
            'logs' => [],
            'error_details' => [[
                'type' => $error_type,
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line']
            ]]
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
});

try {
    require_once('../../controller/ReservationC.php');
    require_once('../../model/Reservation.php');
    require_once('../../controller/config.php');
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de chargement des fichiers requis'
    ]);
    exit;
}

header('Content-Type: application/json');

function debug($message, $data = null) {
    return [
        'timestamp' => date('Y-m-d H:i:s'),
        'message' => $message,
        'data' => $data
    ];
}

// Vérifier la connexion à la base de données
try {
    $db = config::getConnexion();
    if (!$db) {
        throw new Exception("La connexion à la base de données a échoué");
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de connexion à la base de données'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupération et validation des données
        $id = isset($_POST['voyage_id']) ? intval($_POST['voyage_id']) : 0;
        $prix = isset($_POST['voyage_prix']) ? floatval(str_replace(' TND', '', $_POST['voyage_prix'])) : 0;
        $mode_paiement = isset($_POST['mode_paiement']) ? $_POST['mode_paiement'] : '';
        $date_reservation = date('Y-m-d H:i:s');
        $statut_reservation = 'En attente';

        // Validation des données
        if ($id <= 0) {
            throw new Exception('ID du voyage invalide');
        }
        
        if ($prix <= 0) {
            throw new Exception('Prix invalide');
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

        // Création et ajout de la réservation
        $reservation = new Reservation(
            $id,
            $prix,
            $mode_paiement,
            $statut_reservation,
            $date_reservation,
            null
        );

        $result = $reservationC->addReservation($reservation);
        
        // Récupérer l'ID de la dernière réservation insérée
        $lastInsertId = $db->lastInsertId();
        
        if (strpos($result, 'succès') !== false) {
            echo json_encode([
                'success' => true,
                'message' => $result,
                'reservation_id' => $lastInsertId
            ]);
        } else {
            throw new Exception($result);
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// Si la méthode n'est pas POST
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Méthode non autorisée'
]);
?> 