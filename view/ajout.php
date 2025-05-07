<?php

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure les fichiers nécessaires
    require_once('../controller/VoyageC.php');
    require_once('../model/voyage.php');

    $voyageC = new VoyageC();

    // Validation des données
    $errors = [];
    
    // Fonction de nettoyage des données
    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    // Validation du titre
    if (empty($_POST['titre'])) {
        $errors[] = "Le titre est requis";
    } else {
        $titre = cleanInput($_POST['titre']);
    }
    
    // Validation de la destination
    if (empty($_POST['destination'])) {
        $errors[] = "La destination est requise";
    } else {
        $destination = cleanInput($_POST['destination']);
    }
    
    // Validation de la description
    if (empty($_POST['description'])) {
        $errors[] = "La description est requise";
    } else {
        $description = cleanInput($_POST['description']);
    }
    
    // Validation des dates
    if (empty($_POST['date_depart'])) {
        $errors[] = "La date de départ est requise";
    } else {
        $date_depart = $_POST['date_depart'];
    }
    
    if (empty($_POST['date_retour'])) {
        $errors[] = "La date de retour est requise";
    } else {
        $date_retour = $_POST['date_retour'];
        if ($date_retour < $date_depart) {
            $errors[] = "La date de retour doit être postérieure à la date de départ";
        }
    }
    
    // Validation du prix
    if (empty($_POST['prix'])) {
        $errors[] = "Le prix est requis";
    } else {
        $prix = filter_var($_POST['prix'], FILTER_VALIDATE_FLOAT);
        if ($prix === false || $prix < 0) {
            $errors[] = "Le prix doit être un nombre positif";
        }
    }
    
    // Validation du nombre de places
    if (empty($_POST['place'])) {
        $errors[] = "Le nombre de places est requis";
    } else {
        $place = filter_var($_POST['place'], FILTER_VALIDATE_INT);
        if ($place === false || $place < 1) {
            $errors[] = "Le nombre de places doit être un nombre entier positif";
        }
    }
    
    // Validation du type
    if (empty($_POST['type'])) {
        $errors[] = "Le type est requis";
    } else {
        $type = cleanInput($_POST['type']);
    }
    
    // Si pas d'erreurs, procéder à l'ajout
    if (empty($errors)) {
        try {
            $voyage = new Voyage(
                null,
                $destination,
                $titre,
                $description,
                $date_depart,
                $date_retour,
                $prix,
                $place,
                $type
            );
            
            $result = $voyageC->addVoyage($voyage);
            
            // Redirection avec message de succès
            header('Location: liste.php?success=1&message=' . urlencode('Voyage ajouté avec succès'));
            exit();
            
        } catch (Exception $e) {
            $errors[] = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
    
    // S'il y a des erreurs, rediriger avec les erreurs
    if (!empty($errors)) {
        $errorString = implode('|', $errors);
        header('Location: liste.php?error=1&messages=' . urlencode($errorString));
        exit();
    }
} else {
    // Si accès direct au fichier sans POST, rediriger vers la liste
    header('Location: liste.php');
    exit();
}
?>
