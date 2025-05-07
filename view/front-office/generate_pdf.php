<?php
// Start output buffering to catch any PHP errors
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable direct error output

// Ensure all errors are caught
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // Get any PHP errors that occurred
    $phpErrors = ob_get_clean();
    ob_start(); // Start a new buffer for the response

    // Verify FPDF path
    $fpdfPath = __DIR__ . '/../../lib/fpdf/fpdf.php';
    if (!file_exists($fpdfPath)) {
        throw new Exception('FPDF library not found at: ' . $fpdfPath);
    }
    require_once($fpdfPath);

    // Define the PDF output directory with absolute path
    define('PDF_DIR', __DIR__ . '/../../pdfs');

    // Create the PDF directory if it doesn't exist
    if (!file_exists(PDF_DIR)) {
        if (!mkdir(PDF_DIR, 0777, true)) {
            throw new Exception('Failed to create PDF directory at: ' . PDF_DIR);
        }
    }

    // Verify the directory is writable
    if (!is_writable(PDF_DIR)) {
        throw new Exception('PDF directory is not writable: ' . PDF_DIR);
    }

    class PDF extends FPDF {
        function Header() {
            // Simple text-based header without images
            $this->SetFont('Arial', 'B', 20);
            $this->Cell(0, 10, 'WorldVenture', 0, 1, 'C');
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, 'Confirmation de Reservation', 0, 1, 'C');
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln(10);
        }
        
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' | WorldVenture - ' . date('d/m/Y H:i'), 0, 0, 'C');
        }

        function AddReservationDetails($data) {
            $this->SetFont('Arial', '', 12);
            
            // Add a decorative line
            $this->SetDrawColor(0, 0, 0);
            $this->SetLineWidth(0.5);
            
            // Format the reservation details in a table-like structure
            $this->SetFillColor(240, 240, 240);
            $this->Cell(0, 10, 'Details de la Reservation', 0, 1, 'L', true);
            $this->Ln(5);
            
            foreach ($data as $key => $value) {
                // Left column: key
                $this->SetFont('Arial', 'B', 11);
                $this->Cell(50, 8, ucfirst($key) . ':', 0);
                
                // Right column: value
                $this->SetFont('Arial', '', 11);
                $this->Cell(0, 8, $value, 0);
                $this->Ln();
            }
            
            // Add terms and conditions
            $this->Ln(10);
            $this->SetFont('Arial', 'I', 10);
            $this->MultiCell(0, 5, 'Merci d\'avoir choisi WorldVenture pour votre voyage. Ce document sert de confirmation de votre reservation. Veuillez le conserver precieusement.');
        }
    }

    // Get reservation ID from GET parameter
    $reservation_id = isset($_GET['reservation_id']) ? intval($_GET['reservation_id']) : 0;
    
    if ($reservation_id <= 0) {
        throw new Exception('ID de reservation invalide');
    }

    // Get database connection
    require_once(__DIR__ . '/../../controller/config.php');
    $db = config::getConnexion();

    // Get reservation and voyage details in a single query
    $sql = "SELECT r.*, v.titre, v.destination, v.date_depart, v.date_retour 
            FROM réservation r 
            LEFT JOIN voyage v ON r.id = v.id 
            WHERE r.id_reservation = :reservation_id";

    $query = $db->prepare($sql);
    $query->bindValue(':reservation_id', $reservation_id);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        throw new Exception('Reservation non trouvee (ID: ' . $reservation_id . ')');
    }

    // Format payment method
    $mode_paiement = '';
    switch ($result['mode_paiement']) {
        case 1:
            $mode_paiement = 'Carte bancaire';
            break;
        case 2:
            $mode_paiement = 'PayPal';
            break;
        case 3:
            $mode_paiement = 'Virement bancaire';
            break;
        case 4:
            $mode_paiement = 'Espèces';
            break;
        default:
            $mode_paiement = 'Non spécifié';
    }
    
    // Format data for PDF
    $data = [
        'Numero de reservation' => $reservation_id,
        'Destination' => $result['destination'],
        'Titre du voyage' => $result['titre'],
        'Date de depart' => $result['date_depart'],
        'Date de retour' => $result['date_retour'],
        'Prix' => $result['prix'] . ' TND',
        'Mode de paiement' => $mode_paiement,
        'Date de reservation' => $result['date_reservation'] ?? date('Y-m-d'),
        'Statut' => $result['statut_reservation'] ?? 'Confirmé'
    ];
    
    // Create PDF
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->AddReservationDetails($data);
    
    // Generate filename
    $filename = 'reservation_' . $reservation_id . '_' . time() . '.pdf';
    $filepath = PDF_DIR . '/' . $filename;
    
    // Save PDF
    $pdf->Output('F', $filepath);
    
    // Clear any output that might have been generated
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Return success response with web-accessible path
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'filename' => 'pdfs/' . $filename,
        'message' => 'PDF genere avec succes'
    ]);
    
} catch (Throwable $e) {
    // Get any output that was generated
    $output = ob_get_clean();
    
    // Clear all output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Return error response with detailed information
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la generation du PDF: ' . $e->getMessage(),
        'debug' => [
            'php_errors' => $phpErrors,
            'output' => $output,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}
?> 