<?php



// Include the necessary userC.php file
require_once('C:\xampp\htdocs\voyage\controller\voyageC.php');
require_once('C:\xampp\htdocs\voyage\controller\ReservationC.php');

// Create an instance of UserC class
$voyage = new voyageC();
$reservationC = new ReservationC();

// Fetch the list of users
$tab = $voyage->listVoyages();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WorldVenture | Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .table-responsive {
      overflow-x: auto;
      margin: 0 -1.5rem;
      padding: 0 1.5rem;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin: 1rem 0;
    }

    th {
      background: var(--bleu-clair);
      color: var(--bleu-ocean);
      padding: 1rem;
      text-align: left;
      font-weight: 600;
      font-size: 0.95rem;
      white-space: nowrap;
      border-bottom: 2px solid var(--bleu-ocean);
    }

    td {
      padding: 1rem;
      border-bottom: 1px solid #e2e8f0;
      font-size: 0.9rem;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover td {
      background: var(--bleu-clair);
      transition: background-color 0.3s ease;
    }

    /* Badges et boutons d'action */
    .status-badge {
      padding: 8px 12px;
      border-radius: 20px;
      font-weight: 500;
      display: inline-block;
      margin: 3px;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .status-badge a {
      color: inherit;
      text-decoration: none;
    }

    .delete {
      background-color: #ff4d4d;
      color: white;
    }

    .delete:hover {
      background-color: #ff3333;
      transform: translateY(-2px);
    }

    .success {
      background-color: #4CAF50;
      color: white;
    }

    .success:hover {
      background-color: #45a049;
      transform: translateY(-2px);
    }

    .update {
      background-color: #007bff;
      color: white;
    }

    .update:hover {
      background-color: #0056b3;
      transform: translateY(-2px);
    }

    /* Bouton Ajouter */
    .add-btn {
      background-color: var(--vert-vif);
      color: white;
      padding: 0.8rem 1.5rem;
      border-radius: 8px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .add-btn:hover {
      background-color: #0d946a;
      transform: translateY(-2px);
    }

    .add-btn i {
      font-size: 1.1rem;
    }

    /* Amélioration responsive */
    @media (max-width: 1024px) {
      .table-responsive {
        margin: 0 -1rem;
        padding: 0 1rem;
      }

      td, th {
        padding: 0.75rem;
      }
    }

    @media (max-width: 768px) {
      .card-header {
        flex-direction: column;
        gap: 1rem;
      }

      .add-btn {
        width: 100%;
        justify-content: center;
      }
    }

    /* Modal Styles */
    .modal-header {
      background: var(--bleu-ocean);
      color: white;
      border-radius: 8px 8px 0 0;
    }

    .modal-title {
      font-weight: 600;
    }

    .modal .close {
      color: white;
    }

    .modal-body {
      padding: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-weight: 500;
      color: var(--bleu-ocean);
      margin-bottom: 0.5rem;
    }

    .form-control {
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      padding: 0.75rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--bleu-ciel);
      box-shadow: 0 0 0 3px rgba(62, 155, 255, 0.1);
    }

    .modal-footer {
      padding: 1rem 2rem;
      border-top: 1px solid #e2e8f0;
    }

    .btn-modal {
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-modal-submit {
      background: var(--vert-vif);
      color: white;
    }

    .btn-modal-submit:hover {
      background: #0d946a;
      transform: translateY(-2px);
    }

    .btn-modal-cancel {
      background: #e2e8f0;
      color: var(--texte-fonce);
    }

    .btn-modal-cancel:hover {
      background: #cbd5e1;
    }

    /* Styles pour les badges de statut */
    .status-badge.info {
      background-color: #0dcaf0;
      color: white;
    }
    .status-badge.warning {
      background-color: #ffc107;
      color: black;
    }
    .status-badge.danger {
      background-color: #dc3545;
      color: white;
    }
    
    /* Style pour le tableau des réservations */
    #reservationsTable {
      width: 100%;
      margin-bottom: 1rem;
    }
    
    #reservationsTable th,
    #reservationsTable td {
      padding: 0.75rem;
      vertical-align: middle;
    }
    
    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
      margin: 0 0.2rem;
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
          <button class="btn btn-primary">
            <i class="fas fa-sync-alt"></i> Actualiser
          </button>
        </div>
      </header>

      <?php
      // Affichage des messages de succès
      if (isset($_GET['success']) && isset($_GET['message'])) {
          echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  ' . htmlspecialchars(urldecode($_GET['message'])) . '
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
      }
      
      // Affichage des messages d'erreur
      if (isset($_GET['error']) && isset($_GET['messages'])) {
          $messages = explode('|', urldecode($_GET['messages']));
          echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <ul class="mb-0">';
          foreach ($messages as $message) {
              echo '<li>' . htmlspecialchars($message) . '</li>';
          }
          echo '</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
      }
      ?>

      <!-- Dernières activités -->
      <div class="data-card">
        <div class="card-header">
          <h2>Liste des voyages</h2>
          <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#addVoyageModal">
            <i class="fas fa-plus"></i> Ajouter un voyage
          </button>
        </div>
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Destination</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Date de départ</th>
                <th>Date de retour</th>
                <th>Prix/personne</th>
                <th>Places</th>
                <th>Type</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tab as $voyage) { ?>
                <tr data-voyage-id="<?= $voyage['id']; ?>">
                  <td><?= htmlspecialchars($voyage['id']); ?></td>
                  <td><?= htmlspecialchars($voyage['destination']); ?></td>
                  <td><?= htmlspecialchars($voyage['titre']); ?></td>
                  <td><?= htmlspecialchars($voyage['description']); ?></td>
                  <td><?= htmlspecialchars($voyage['date_depart']); ?></td>
                  <td><?= htmlspecialchars($voyage['date_retour']); ?></td>
                  <td><?= htmlspecialchars($voyage['prix']); ?> €</td>
                  <td><?= htmlspecialchars($voyage['place']); ?></td>
                  <td><?= htmlspecialchars($voyage['type']); ?></td>
                  <td>
                    <span class="status-badge update">
                      <a href="#" onclick="openEditModal(<?= htmlspecialchars(json_encode($voyage)); ?>)">
                        <i class="fas fa-edit"></i> Modifier
                      </a>
                    </span>
                    <span class="status-badge delete">
                      <a href="delete.php?id=<?= $voyage['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce voyage ?');">
                        <i class="fas fa-trash"></i> Supprimer
                      </a>
                    </span>
                    <span class="status-badge info">
                      <a href="#" onclick="openReservationsModal(<?= $voyage['id']; ?>)">
                        <i class="fas fa-calendar-check"></i> Réservations
                      </a>
                    </span>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal Ajout Voyage -->
  <div class="modal fade" id="addVoyageModal" tabindex="-1" aria-labelledby="addVoyageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addVoyageModalLabel">Ajouter un nouveau voyage</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="ajout.php" method="POST">
          <div class="modal-body">
            <div class="form-group">
              <label for="titre" class="form-label">Titre</label>
              <input type="text" class="form-control" id="titre" name="titre" required>
            </div>
            <div class="form-group">
              <label for="destination" class="form-label">Destination</label>
              <select class="form-control" id="destination" name="destination" required>
                <option value="">Sélectionnez une destination</option>
                <option value="Tunisie">Tunisie</option>
                <option value="France">France</option>
                <option value="Rome">Rome</option>
                <option value="Espagne">Espagne</option>
                <option value="Japon">Japon</option>
                <option value="Maroc">Maroc</option>
                <option value="Turkiye">Turkiye</option>
                <option value="Allemagne">Allemagne</option>
                <option value="Brésil">Brésil</option>
                <option value="Tchad">Tchad</option>
                <option value="Inde">Inde</option>
                <option value="Maldives">Maldives</option>
                <option value="Russie">Russie</option>
                <option value="Canada">Canada</option>
                <option value="Etats-Unis">États-Unis</option>
                <option value="Australie">Australie</option>
                <option value="Thailande">Thaïlande</option>
                <option value="Norvège">Norvège</option>
              </select>
            </div>
            <div class="form-group">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
              <label for="date_depart" class="form-label">Date de départ</label>
              <input type="date" class="form-control" id="date_depart" name="date_depart" required>
            </div>
            <div class="form-group">
              <label for="date_retour" class="form-label">Date de retour</label>
              <input type="date" class="form-control" id="date_retour" name="date_retour" required>
            </div>
            <div class="form-group">
              <label for="prix" class="form-label">Prix par personne (€)</label>
              <input type="number" class="form-control" id="prix" name="prix" required>
            </div>
            <div class="form-group">
              <label for="place" class="form-label">Nombre de places</label>
              <input type="number" class="form-control" id="place" name="place" required>
            </div>
            <div class="form-group">
              <label for="type" class="form-label">Type de voyage</label>
              <select class="form-control" id="type" name="type" required>
                <option value="">Sélectionnez un type</option>
                <option value="Culturel">Culturel</option>
                <option value="Aventure">Aventure</option>
                <option value="Balnéaire">Balnéaire</option>
                <option value="Familial">Familial</option>
                <option value="Luxe">Luxe</option>
                <option value="Écologique">Écologique</option>
                <option value="Gastronomique">Gastronomique</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-modal btn-modal-cancel" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-modal btn-modal-submit">Ajouter le voyage</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Modification Voyage -->
  <div class="modal fade" id="editVoyageModal" tabindex="-1" aria-labelledby="editVoyageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editVoyageModalLabel">Modifier le voyage</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="update.php" method="POST">
          <input type="hidden" id="edit_id" name="id">
          <div class="modal-body">
            <div class="form-group">
              <label for="edit_titre" class="form-label">Titre</label>
              <input type="text" class="form-control" id="edit_titre" name="titre" required>
            </div>
            <div class="form-group">
              <label for="edit_destination" class="form-label">Destination</label>
              <select class="form-control" id="edit_destination" name="destination" required>
                <option value="">Sélectionnez une destination</option>
                <option value="Tunisie">Tunisie</option>
                <option value="France">France</option>
                <option value="Rome">Rome</option>
                <option value="Espagne">Espagne</option>
                <option value="Japon">Japon</option>
                <option value="Maroc">Maroc</option>
                <option value="Turkiye">Turkiye</option>
                <option value="Allemagne">Allemagne</option>
                <option value="Brésil">Brésil</option>
                <option value="Tchad">Tchad</option>
                <option value="Inde">Inde</option>
                <option value="Maldives">Maldives</option>
                <option value="Russie">Russie</option>
                <option value="Canada">Canada</option>
                <option value="Etats-Unis">États-Unis</option>
                <option value="Australie">Australie</option>
                <option value="Thailande">Thaïlande</option>
                <option value="Norvège">Norvège</option>
              </select>
            </div>
            <div class="form-group">
              <label for="edit_description" class="form-label">Description</label>
              <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
              <label for="edit_date_depart" class="form-label">Date de départ</label>
              <input type="date" class="form-control" id="edit_date_depart" name="date_depart" required>
            </div>
            <div class="form-group">
              <label for="edit_date_retour" class="form-label">Date de retour</label>
              <input type="date" class="form-control" id="edit_date_retour" name="date_retour" required>
            </div>
            <div class="form-group">
              <label for="edit_prix" class="form-label">Prix par personne (€)</label>
              <input type="number" class="form-control" id="edit_prix" name="prix" required>
            </div>
            <div class="form-group">
              <label for="edit_place" class="form-label">Nombre de places</label>
              <input type="number" class="form-control" id="edit_place" name="place" required>
            </div>
            <div class="form-group">
              <label for="edit_type" class="form-label">Type de voyage</label>
              <select class="form-control" id="edit_type" name="type" required>
                <option value="">Sélectionnez un type</option>
                <option value="Culturel">Culturel</option>
                <option value="Aventure">Aventure</option>
                <option value="Balnéaire">Balnéaire</option>
                <option value="Familial">Familial</option>
                <option value="Luxe">Luxe</option>
                <option value="Écologique">Écologique</option>
                <option value="Gastronomique">Gastronomique</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-modal btn-modal-cancel" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-modal btn-modal-submit">Enregistrer les modifications</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Réservations -->
  <div class="modal fade" id="reservationsModal" tabindex="-1" aria-labelledby="reservationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="reservationsModalLabel">Réservations du voyage</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table id="reservationsTable">
              <thead>
                <tr>
                  <th>ID Réservation</th>
                  <th>Prix</th>
                  <th>Mode de paiement</th>
                  <th>Statut</th>
                  <th>Date réservation</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <!-- Les réservations seront chargées ici dynamiquement -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Modification Statut Réservation -->
  <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editStatusModalLabel">Modifier le statut de la réservation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editStatusForm">
          <div class="modal-body">
            <input type="hidden" id="reservation_id" name="reservation_id">
            <div class="form-group">
              <label for="statut_reservation" class="form-label">Statut</label>
              <select class="form-control" id="statut_reservation" name="statut_reservation" required>
                <option value="En attente">En attente</option>
                <option value="Confirmée">Confirmée</option>
                <option value="Annulée">Annulée</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-modal btn-modal-cancel" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-modal btn-modal-submit">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
  <script>
    // Fonction pour ouvrir le modal de modification avec les données du voyage
    function openEditModal(voyage) {
      document.getElementById('edit_id').value = voyage.id;
      document.getElementById('edit_titre').value = voyage.titre;
      document.getElementById('edit_destination').value = voyage.destination;
      document.getElementById('edit_description').value = voyage.description;
      document.getElementById('edit_date_depart').value = voyage.date_depart;
      document.getElementById('edit_date_retour').value = voyage.date_retour;
      document.getElementById('edit_prix').value = voyage.prix;
      document.getElementById('edit_place').value = voyage.place;
      document.getElementById('edit_type').value = voyage.type;

      var editModal = new bootstrap.Modal(document.getElementById('editVoyageModal'));
      editModal.show();
    }

    // Validation des dates
    document.addEventListener('DOMContentLoaded', function() {
      // Pour le formulaire d'ajout
      const dateDepart = document.getElementById('date_depart');
      const dateRetour = document.getElementById('date_retour');

      dateDepart.addEventListener('change', function() {
        dateRetour.min = this.value;
      });

      // Pour le formulaire de modification
      const editDateDepart = document.getElementById('edit_date_depart');
      const editDateRetour = document.getElementById('edit_date_retour');

      editDateDepart.addEventListener('change', function() {
        editDateRetour.min = this.value;
      });
    });

    // Gestion de la déconnexion
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Voulez-vous vraiment vous déconnecter ?')) {
        window.location.href = 'index.html';
      }
    });

    // Fonction pour ouvrir le modal des réservations
    async function openReservationsModal(voyageId) {
      currentVoyageId = voyageId;
      const modal = new bootstrap.Modal(document.getElementById('reservationsModal'));
      await loadReservations(voyageId);
      modal.show();
    }

    // Fonction pour charger les réservations
    async function loadReservations(voyageId) {
      const tbody = document.querySelector('#reservationsTable tbody');
      tbody.innerHTML = '<tr><td colspan="6">Chargement...</td></tr>';
      
      try {
        const response = await fetch(`get_reservations.php?voyage_id=${voyageId}`);
        const reservations = await response.json();
        
        tbody.innerHTML = reservations.map(reservation => `
          <tr id="reservation-${reservation.id_reservation}">
            <td>${reservation.id_reservation}</td>
            <td>${reservation.prix} €</td>
            <td>${getModePaiementLibelle(reservation.mode_paiement)}</td>
            <td>
              <span class="status-badge ${getStatusClass(reservation.statut_reservation)}">
                ${reservation.statut_reservation}
              </span>
            </td>
            <td>${formatDate(reservation.date_reservation)}</td>
            <td>
              <button class="btn btn-sm btn-primary" onclick="openEditStatusModal(${reservation.id_reservation}, '${reservation.statut_reservation}')">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-sm btn-danger" onclick="deleteReservation(${reservation.id_reservation})">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        `).join('');
      } catch (error) {
        tbody.innerHTML = '<tr><td colspan="6">Erreur lors du chargement des réservations</td></tr>';
      }
    }

    // Fonction pour mettre à jour une ligne de réservation
    function updateReservationRow(reservationId, newStatus) {
      const row = document.querySelector(`#reservation-${reservationId}`);
      if (row) {
        const statusCell = row.querySelector('td:nth-child(4)');
        statusCell.innerHTML = `
          <span class="status-badge ${getStatusClass(newStatus)}">
            ${newStatus}
          </span>
        `;
      }
    }

    // Fonction pour supprimer une ligne de réservation
    function removeReservationRow(reservationId) {
      const row = document.querySelector(`#reservation-${reservationId}`);
      if (row) {
        row.remove();
        // Vérifier si le tableau est vide
        const tbody = document.querySelector('#reservationsTable tbody');
        if (tbody.children.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6">Aucune réservation</td></tr>';
        }
      }
    }

    // Fonction pour ouvrir le modal de modification du statut
    function openEditStatusModal(reservationId, currentStatus) {
      const modal = new bootstrap.Modal(document.getElementById('editStatusModal'));
      document.getElementById('reservation_id').value = reservationId;
      document.getElementById('statut_reservation').value = currentStatus;
      modal.show();
    }

    // Fonction pour supprimer une réservation
    async function deleteReservation(reservationId) {
      if (!confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
        return;
      }

      try {
        const response = await fetch('delete_reservation.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ id_reservation: reservationId })
        });

        const result = await response.json();
        if (result.success) {
          // Supprimer immédiatement la ligne du tableau
          removeReservationRow(reservationId);
          // Afficher un message de succès
          showAlert('success', 'Réservation supprimée avec succès');
        } else {
          showAlert('error', 'Erreur lors de la suppression de la réservation');
        }
      } catch (error) {
        showAlert('error', 'Erreur lors de la suppression de la réservation');
      }
    }

    // Fonction pour afficher les alertes
    function showAlert(type, message) {
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
      alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      `;
      document.querySelector('.main-content').insertBefore(alertDiv, document.querySelector('.data-card'));
      
      // Auto-fermeture après 3 secondes
      setTimeout(() => {
        alertDiv.remove();
      }, 3000);
    }

    // Gestion du formulaire de modification du statut
    document.getElementById('editStatusForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = {
        id_reservation: document.getElementById('reservation_id').value,
        statut_reservation: document.getElementById('statut_reservation').value
      };

      try {
        const response = await fetch('update_reservation_status.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData)
        });

        const result = await response.json();
        if (result.success) {
          // Mettre à jour immédiatement le statut dans le tableau
          updateReservationRow(formData.id_reservation, formData.statut_reservation);
          
          // Mettre à jour le nombre de places si nécessaire
          if (result.places_updated) {
            updatePlacesCount(result.voyage_id, result.places_remaining);
          }
          
          // Fermer le modal
          bootstrap.Modal.getInstance(document.getElementById('editStatusModal')).hide();
          // Afficher un message de succès
          showAlert('success', 'Statut mis à jour avec succès');
        } else {
          showAlert('error', 'Erreur lors de la modification du statut');
        }
      } catch (error) {
        showAlert('error', 'Erreur lors de la modification du statut');
      }
    });

    // Fonction pour mettre à jour le nombre de places affiché
    function updatePlacesCount(voyageId, newCount) {
      const placeCell = document.querySelector(`tr[data-voyage-id="${voyageId}"] td:nth-child(8)`);
      if (placeCell) {
        placeCell.textContent = newCount;
      }
    }

    // Fonctions utilitaires
    function getModePaiementLibelle(mode) {
      const modes = {
        '1': 'Carte bancaire',
        '2': 'PayPal',
        '3': 'Virement bancaire',
        '4': 'Espèces'
      };
      return modes[mode] || mode;
    }

    function getStatusClass(status) {
      switch(status) {
        case 'Confirmée': return 'success';
        case 'En attente': return 'warning';
        case 'Annulée': return 'danger';
        default: return 'secondary';
      }
    }

    function formatDate(dateString) {
      return new Date(dateString).toLocaleString('fr-FR');
    }

    let currentVoyageId = null;
  </script>
</body>
</html>
