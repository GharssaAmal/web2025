<?php
// Inclure les fichiers nécessaires
require_once('C:\xampp\htdocs\voyage\controller\voyageC.php');
require_once('C:\xampp\htdocs\voyage\model\voyage.php');

$voyageC = new VoyageC();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation des données
    $errors = [];
    
    // Fonction de nettoyage des données
    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Validation de l'ID
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        $errors[] = "ID invalide";
    } else {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
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
        if (isset($date_depart) && $date_retour < $date_depart) {
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
    
    // Si pas d'erreurs, procéder à la mise à jour
    if (empty($errors)) {
        try {
            $voyage = new Voyage(
                $id,
                $destination,
                $titre,
                $description,
                $date_depart,
                $date_retour,
                $prix,
                $place,
                $type
            );
            
            $voyageC = new VoyageC();
            $result = $voyageC->updateVoyage($voyage, $id);
            
            if ($result) {
                // Redirection avec message de succès
                header('Location: liste.php?success=1&message=' . urlencode('Voyage modifié avec succès'));
                exit();
            } else {
                $errors[] = "Erreur lors de la mise à jour du voyage";
            }
        } catch (Exception $e) {
            $errors[] = "Erreur lors de la mise à jour : " . $e->getMessage();
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



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WorldVenture | Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css" rel="stylesheet">
  <style>
    :root {
      --bleu-ocean: #0a4c8c;
      --bleu-ciel: #3e9bff;
      --bleu-clair: #e1f0ff;
      --vert-vif: #10b981;
      --orange-vif: #f59e0b;
      --rouge: #ef4444;
      --violet: #8b5cf6;
      --blanc-creme: #f8fafc;
      --texte-fonce: #1e293b;
      --texte-clair: #f8fafc;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    body {
      background-color: var(--blanc-creme);
      color: var(--texte-fonce);
      line-height: 1.6;
      font-size: 16px;
    }

    .admin-container {
      display: grid;
      grid-template-columns: 280px 1fr;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      background: linear-gradient(180deg, var(--bleu-ocean), #0d3a6a);
      color: var(--texte-clair);
      padding: 2rem 0;
      display: flex;
      flex-direction: column;
      height: 100vh;
      position: sticky;
      top: 0;
      box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    }

    .sidebar-header {
      padding: 0 2rem 2rem;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      text-align: center;
    }

    .admin-logo {
      width: 80%;
      max-width: 180px;
    
    }

    .sidebar-nav {
      flex-grow: 1;
      overflow-y: auto;
      padding: 0 1.5rem;
    }

    .sidebar-nav ul {
      list-style: none;
      margin-top: 1rem;
    }

    .sidebar-nav li a {
      display: flex;
      align-items: center;
      padding: 1rem 1.5rem;
      color: var(--texte-clair);
      text-decoration: none;
      font-size: 1.05rem;
      font-weight: 500;
      transition: all 0.3s;
      border-radius: 8px;
      margin: 0.25rem 0;
    }

    .sidebar-nav li a:hover,
    .sidebar-nav li.active a {
      background: rgba(255,255,255,0.15);
      transform: translateX(5px);
    }

    .sidebar-nav i {
      margin-right: 1rem;
      font-size: 1.1rem;
      width: 24px;
      text-align: center;
    }

    .sidebar-footer {
      padding: 1.5rem;
      border-top: 1px solid rgba(255,255,255,0.1);
      margin-top: auto;
    }

    .logout-btn {
      display: flex;
      align-items: center;
      width: 100%;
      padding: 0.8rem 1rem;
      background: rgba(255,255,255,0.1);
      color: var(--texte-clair);
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
    }

    .logout-btn:hover {
      background: rgba(255,255,255,0.2);
    }

    .logout-btn i {
      margin-right: 0.8rem;
    }

    /* Main Content */
    .main-content {
      padding: 2rem;
      background-color: var(--blanc-creme);
    }

    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2.5rem;
    }

    h1 {
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--bleu-ocean);
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    h1 i {
      color: var(--bleu-ciel);
    }

    h2 {
      font-size: 1.6rem;
      margin-bottom: 1.5rem;
      color: var(--bleu-ocean);
      font-weight: 600;
    }

    /* Grille de statistiques avec nouvelles couleurs */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }

    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(10, 76, 140, 0.08);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card.blue {
      border-left: 4px solid var(--bleu-ciel);
    }
    .stat-card.green {
      border-left: 4px solid var(--vert-vif);
    }
    .stat-card.orange {
      border-left: 4px solid var(--orange-vif);
    }
    .stat-card.violet {
      border-left: 4px solid var(--violet);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(10, 76, 140, 0.12);
    }

    .stat-card .stat-value {
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--bleu-ocean);
      margin-bottom: 0.25rem;
    }

    .stat-card .stat-label {
      color: #64748b;
      font-size: 0.95rem;
    }

    .stat-card .stat-change {
      display: flex;
      align-items: center;
      margin-top: 0.5rem;
      font-size: 0.9rem;
    }

    .stat-card .stat-change.positive {
      color: var(--vert-vif);
    }

    .stat-card .stat-change.negative {
      color: var(--rouge);
    }

    /* Graphiques */
    .chart-container {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(10, 76, 140, 0.08);
    }

    .chart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    /* Tableaux */
    .data-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(10, 76, 140, 0.08);
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }

    th {
      background: var(--bleu-clair);
      color: var(--bleu-ocean);
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      font-size: 0.95rem;
    }

    td {
      padding: 1rem;
      border-bottom: 1px solid #e2e8f0;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover td {
      background: var(--bleu-clair);
    }

    /* Badges avec nouvelles couleurs */
    .status-badge {
      display: inline-block;
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .status-badge.success {
      background: rgba(16, 185, 129, 0.1);
      color: var(--vert-vif);
    }

    .status-badge.warning {
      background: rgba(245, 158, 11, 0.1);
      color: var(--orange-vif);
    }

    /* Boutons */
    .btn {
      padding: 0.7rem 1.2rem;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: 500;
      font-size: 0.95rem;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-sm {
      padding: 0.5rem 0.9rem;
      font-size: 0.85rem;
    }

    .btn-primary {
      background: var(--bleu-ciel);
      color: white;
    }

    .btn-primary:hover {
      background: var(--bleu-ocean);
    }

    .btn-outline {
      background: transparent;
      border: 1px solid var(--bleu-ciel);
      color: var(--bleu-ciel);
    }

    .btn-outline:hover {
      background: var(--bleu-clair);
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .admin-container {
        grid-template-columns: 1fr;
      }
      
      .sidebar {
        display: none;
      }
      
      h1 {
        font-size: 1.8rem;
      }
      
      .stats-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width: 768px) {
      .stats-grid {
        grid-template-columns: 1fr;
      }
      
      .main-content {
        padding: 1.5rem;
      }
    }
    .status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-weight: bold;
    display: inline-block;
    margin: 5px;
}

.delete {
    background-color: #ff4d4d;
    color: white;
}

.success {
    background-color: #4CAF50;
    color: white;
}

.update {
    background-color: #007bff;
    color: white;
}

  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="admin-container">
    <!-- Sidebar modifiée -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <img src="back-office/images/logo-worldventure.png" alt="WorldVenture" class="admin-logo">
      </div>
      
      <nav class="sidebar-nav">
        <ul>
          <li class="active"><a href="#"><i class="fas fa-tachometer-alt"></i> Gestion des voyages </li>
           <li><a href="../back/index.html"><i class="fas fa-tags"></i> Offres</a></li>
          <li><a href="#"><i class="fas fa-users"></i> Blog</a></li>
          <li><a href="#"><i class="fas fa-comment-alt"></i> Réclamation</a></li>
          <li><a href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
        </ul>
      </nav>

      <div class="sidebar-footer">
        <button class="logout-btn">
          <i class="fas fa-sign-out-alt"></i> Déconnexion
        </button>
      </div>
    </aside>

   <!-- Main Content -->
<main class="main-content">
  <header class="admin-header">
    <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h1>
    <div class="admin-actions">
    <a href="liste.php" class="btn btn-primary">
  <i class="fas fa-sync-alt"></i> Retour à la liste
</a>

    </div>
  </header>

  <!-- Statistiques avec nouvelles couleurs -->

  <!-- End Navbar -->
  <div class="container-fluid py-2">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
             </div>
          </div>
          <div class="card-body px-0 pb-2">
            <ul class="list-group">
              <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                <div class="d-flex flex-column">
                  <h2 class="mb-3 text-sm">Mise a jour  du voyage :  </h6>
                  <?php
                                if (isset($_GET['id'])) {
                                    $oldvoyage = $voyageC->showVoyage($_GET['id']);
                                    ?>
                                </div>
                                <form action="" method="POST">
                            <div class="card-body p-2 pb-5">
                                <ul class="list-group">
                                    <li
                                        class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex flex-column">
                                            <tr>
                                                <h6 class="mb-1 text-dark font-weight-bold text-sm">
                                                    <td><label for="id">ID :</label></td>
                                                </h6>
                                                <span class="text-xs">
                                                    <td>
                                                        <input type="text" id="id" name="id"
                                                            value="<?php echo $_GET['id'] ?>" readonly />
                                                    </td>
                                                </span>
                                            </tr>
                                        </div>
                                    </li>
                                </ul>
                                <ul class="list-group">
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
        <div class="d-flex flex-column">
            <tr>
                <h6 class="mb-1 text-dark font-weight-bold text-sm">
                    <td><label for="titre">titre :</label></td>
                </h6>
                <span class="text-xs">
                    <td><input type="text" id="titre" name="titre" value="<?php echo $oldvoyage['titre']; ?>" /></td>
                </span>
            </tr>
        </div>
    </li>
</ul>
<ul class="list-group">
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
        <div class="d-flex flex-column">
            <tr>
                <h6 class="mb-1 text-dark font-weight-bold text-sm">
                    <td><label for="description">description :</label></td>
                </h6>
                <span class="text-xs">
                    <td><input type="text" id="description" name="description" value="<?php echo $oldvoyage['description']; ?>" /></td>
                </span>
            </tr>
        </div>
    </li>
</ul>
<ul class="list-group">
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
        <div class="d-flex flex-column">
            <h6 class="mb-1 text-dark font-weight-bold text-sm">
                <label for="destination">Destination :</label>
            </h6>
            <span class="text-xs">
                <select id="destination" name="destination">
                    <option value="Tunisie" <?php if ($oldvoyage['destination'] == 'Tunisie') echo 'selected'; ?>>Tunisie</option>
                    <option value="France" <?php if ($oldvoyage['destination'] == 'France') echo 'selected'; ?>>France</option>
                    <option value="Rome" <?php if ($oldvoyage['destination'] == 'Rome') echo 'selected'; ?>>Rome</option>
                    <option value="Espagne" <?php if ($oldvoyage['destination'] == 'Espagne') echo 'selected'; ?>>Espagne</option>
                    <option value="Japon" <?php if ($oldvoyage['destination'] == 'Japon') echo 'selected'; ?>>Japon</option>
                    <option value="Maroc" <?php if ($oldvoyage['destination'] == 'Maroc') echo 'selected'; ?>>Maroc</option>
                    <option value="Turkiye" <?php if ($oldvoyage['destination'] == 'Turkiye') echo 'selected'; ?>>Turkiye</option>
                    <option value="Allemagne" <?php if ($oldvoyage['destination'] == 'Allemagne') echo 'selected'; ?>>Allemagne</option>
                    <option value="Brésil" <?php if ($oldvoyage['destination'] == 'Brésil') echo 'selected'; ?>>Brésil</option>
                    <option value="Tchad" <?php if ($oldvoyage['destination'] == 'Tchad') echo 'selected'; ?>>Tchad</option>
                    <option value="Inde" <?php if ($oldvoyage['destination'] == 'Inde') echo 'selected'; ?>>Inde</option>
                    <option value="Maldives" <?php if ($oldvoyage['destination'] == 'Maldives') echo 'selected'; ?>>Maldives</option>
                    <option value="Russie" <?php if ($oldvoyage['destination'] == 'Russie') echo 'selected'; ?>>Russie</option>
                    <option value="Canada" <?php if ($oldvoyage['destination'] == 'Canada') echo 'selected'; ?>>Canada</option>
                    <option value="Etats-Unis" <?php if ($oldvoyage['destination'] == 'Etats-Unis') echo 'selected'; ?>>États-Unis</option>
                    <option value="Australie" <?php if ($oldvoyage['destination'] == 'Australie') echo 'selected'; ?>>Australie</option>
                    <option value="Thailande" <?php if ($oldvoyage['destination'] == 'Thailande') echo 'selected'; ?>>Thaïlande</option>
                    <option value="Norvège" <?php if ($oldvoyage['destination'] == 'Norvège') echo 'selected'; ?>>Norvège</option>
                </select>
            </span>
        </div>
    </li>
</ul>

<ul class="list-group">
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
        <div class="d-flex flex-column">
            <tr>
                <h6 class="mb-1 text-dark font-weight-bold text-sm">
                    <td><label for="prix">prix / personne  :</label></td>
                </h6>
                <span class="text-xs">
                    <td><input type="text" id="prix" name="prix" value="<?php echo $oldvoyage['prix']; ?>" /></td>
                </span>
            </tr>
        </div>
    </li>
</ul>
<ul class="list-group">
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
        <div class="d-flex flex-column">
            <tr>
                <h6 class="mb-1 text-dark font-weight-bold text-sm">
                    <td><label for="place">nombre du place disponible   :</label></td>
                </h6>
                <span class="text-xs">
                    <td><input type="text" id="place" name="place" value="<?php echo $oldvoyage['place']; ?>" /></td>
                </span>
            </tr>
        </div>
    </li>
</ul>
<ul class="list-group">
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
        <div class="d-flex flex-column">
            <tr>
                <h6 class="mb-1 text-dark font-weight-bold text-sm">
                    <td><label for="date_depart">Date depart  :</label></td>
                </h6>
                <span class="text-xs">
                    <td><input type="date" id="date_depart" name="date_depart" value="<?php echo $oldvoyage['date_depart']; ?>" /></td>
                </span>
            </tr>
        </div>
    </li>
</ul>
<ul class="list-group">
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
        <div class="d-flex flex-column">
            <tr>
                <h6 class="mb-1 text-dark font-weight-bold text-sm">
                    <td><label for="date_retour">Date retour  :</label></td>
                </h6>
                <span class="text-xs">
                    <td><input type="date" id="date_retour" name="date_retour" value="<?php echo $oldvoyage['date_retour']; ?>" /></td>
                </span>
            </tr>
        </div>
    </li>
</ul>
<ul class="list-group">
    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
        <div class="d-flex flex-column">
            <h6 class="mb-1 text-dark font-weight-bold text-sm">
                <label for="type">Type :</label>
            </h6>
            <span class="text-xs">
                <select id="type" name="type">
                    <option value="Culturel" <?php if ($oldvoyage['type'] == 'Culturel') echo 'selected'; ?>>Culturel</option>
                    <option value="Aventure" <?php if ($oldvoyage['type'] == 'Aventure') echo 'selected'; ?>>Aventure</option>
                    <option value="Balnéaire" <?php if ($oldvoyage['type'] == 'Balnéaire') echo 'selected'; ?>>Balnéaire</option>
                    <option value="Familial" <?php if ($oldvoyage['type'] == 'Familial') echo 'selected'; ?>>Familial</option>
                    <option value="Luxe" <?php if ($oldvoyage['type'] == 'Luxe') echo 'selected'; ?>>Luxe</option>
                    <option value="Écologique" <?php if ($oldvoyage['type'] == 'Écologique') echo 'selected'; ?>>Écologique</option>
                    <option value="Gastronomique" <?php if ($oldvoyage['type'] == 'Gastronomique') echo 'selected'; ?>>Gastronomique</option>
                </select>
            </span>
        </div>
    </li>
</ul>

                                </div>
                                <ul>
                                    <input class="btn btn-outline-primary btn-sm mb-0" type="submit" value="Update">
                                    <input class="btn btn-outline-primary btn-sm mb-0" type="reset" value="Reset">

                                </ul>
                                </form>
                                <?php }                                ?>


                            </div>
              </li>
             
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
  <script>
    // Graphique des réservations
    const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
    const reservationsChart = new Chart(reservationsCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
          label: 'Réservations 2023',
          data: [120, 190, 170, 220, 240, 280, 310, 290, 330, 380, 350, 400],
          borderColor: '#3e9bff',
          backgroundColor: 'rgba(62, 155, 255, 0.1)',
          borderWidth: 3,
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });

    // Gestion de la déconnexion
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Voulez-vous vraiment vous déconnecter ?')) {
        window.location.href = 'index.html';
      }
    });
  </script>
</body>
</html>
