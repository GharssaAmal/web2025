<?php

require_once('C:\xampp\htdocs\voyage\controller\voyageC.php');

$voyageC = new voyageC();

// Suppression du paiement via l'ID passé en GET
if (isset($_GET['id'])) {
    $voyageC->deleteVoyage($_GET['id']);
    header('Location: liste.php');
    exit();
} else {
    echo "Erreur : ID de paiement non spécifié.";
}
?>
