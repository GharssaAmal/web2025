<?php
require_once('config.php');
require_once('C:\xampp\htdocs\voyage\model\Reservation.php');
require_once('C:\xampp\htdocs\voyage\controller\voyageC.php');

class ReservationC
{
    // Ajouter une réservation
    public function addReservation($reservation)
    {
        $sql = "INSERT INTO réservation (id, prix, mode_paiement, statut_reservation, date_reservation)
                VALUES (:id, :prix, :mode_paiement, :statut_reservation, :date_reservation)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $reservation->getId(),
                'prix' => $reservation->getPrix(),
                'mode_paiement' => $reservation->getModePaiement(),
                'statut_reservation' => $reservation->getStatutReservation(),
                'date_reservation' => $reservation->getDateReservation()
            ]);
            return "Réservation effectuée avec succès !";
        } catch (PDOException $e) {
            echo 'Erreur PDO : ' . $e->getMessage();
            return "Erreur lors de la réservation.";
        }
    }

    // Liste des réservations avec informations du voyage
    public function listReservations()
    {
        $sql = "SELECT r.*, v.titre, v.destination, v.date_depart, v.date_retour 
                FROM réservation r 
                LEFT JOIN voyage v ON r.id = v.id";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Obtenir les réservations d'un voyage spécifique
    public function getReservationsByVoyage($voyage_id)
    {
        $sql = "SELECT r.*, v.titre, v.destination, v.date_depart, v.date_retour 
                FROM réservation r 
                LEFT JOIN voyage v ON r.id = v.id 
                WHERE r.id = :voyage_id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':voyage_id', $voyage_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Supprimer une réservation
    public function deleteReservation($id_reservation)
    {
        $sql = "DELETE FROM réservation WHERE id_reservation = :id_reservation";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id_reservation', $id_reservation);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Afficher une réservation
    public function showReservation($id_reservation)
    {
        $sql = "SELECT r.*, v.titre, v.destination, v.date_depart, v.date_retour 
                FROM réservation r 
                LEFT JOIN voyage v ON r.id = v.id 
                WHERE r.id_reservation = :id_reservation";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_reservation', $id_reservation);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'affichage de la réservation : ' . $e->getMessage());
        }
    }

    // Mettre à jour une réservation
    public function updateReservation($reservation)
    {
        $sql = "UPDATE réservation SET 
                    id = :id,
                    prix = :prix,
                    mode_paiement = :mode_paiement,
                    statut_reservation = :statut_reservation,
                    date_reservation = :date_reservation
                WHERE id_reservation = :id_reservation";

        $db = config::getConnexion();
        try {
            // Récupérer l'ancien statut
            $oldStatus = $this->getReservationStatus($reservation->getIdReservation());
            
            // Mettre à jour la réservation
            $query = $db->prepare($sql);
            $result = $query->execute([
                'id_reservation' => $reservation->getIdReservation(),
                'id' => $reservation->getId(),
                'prix' => $reservation->getPrix(),
                'mode_paiement' => $reservation->getModePaiement(),
                'statut_reservation' => $reservation->getStatutReservation(),
                'date_reservation' => $reservation->getDateReservation()
            ]);

            // Si la mise à jour est réussie et le statut a changé
            if ($result && $oldStatus !== $reservation->getStatutReservation()) {
                $this->updatePlacesDisponibles($reservation->getId(), $oldStatus, $reservation->getStatutReservation());
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    // Obtenir le statut actuel d'une réservation
    private function getReservationStatus($id_reservation)
    {
        $sql = "SELECT statut_reservation FROM réservation WHERE id_reservation = :id_reservation";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_reservation' => $id_reservation]);
            $result = $query->fetch();
            return $result ? $result['statut_reservation'] : null;
        } catch (Exception $e) {
            return null;
        }
    }

    // Mettre à jour le nombre de places disponibles
    private function updatePlacesDisponibles($voyage_id, $oldStatus, $newStatus)
    {
        $voyageC = new voyageC();
        $voyage = $voyageC->showVoyage($voyage_id);
        
        if (!$voyage) {
            return false;
        }

        $places = $voyage['place'];

        // Si la réservation passe à "Confirmée"
        if ($newStatus === 'Confirmée' && $oldStatus !== 'Confirmée') {
            $places--;
        }
        // Si la réservation passe de "Confirmée" à un autre statut
        else if ($oldStatus === 'Confirmée' && $newStatus !== 'Confirmée') {
            $places++;
        }

        // Mettre à jour le nombre de places
        if ($places >= 0) {
            $sql = "UPDATE voyage SET place = :place WHERE id = :id";
            $db = config::getConnexion();
            try {
                $query = $db->prepare($sql);
                return $query->execute([
                    'place' => $places,
                    'id' => $voyage_id
                ]);
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    // Vérifier la disponibilité des places
    public function checkDisponibilite($voyage_id)
    {
        $sql = "SELECT v.place - COUNT(r.id_reservation) as places_disponibles 
                FROM voyage v 
                LEFT JOIN réservation r ON v.id = r.id 
                WHERE v.id = :voyage_id 
                GROUP BY v.id, v.place";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':voyage_id', $voyage_id);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['places_disponibles'] : 0;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la vérification de disponibilité : ' . $e->getMessage());
        }
    }
}
?>
