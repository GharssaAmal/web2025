<?php
require_once('config.php');

class VoyageC
{
    // Ajouter un voyage
    public function addVoyage($voyage)
    {
        $sql = "INSERT INTO voyage (titre,destination, description, date_depart, date_retour, prix, place, type)
                VALUES (:titre, :destination, :description, :date_depart, :date_retour, :prix, :place, :type)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $voyage->getTitre(),
                'destination' => $voyage->getDestination(),
                'description' => $voyage->getDescription(),
                'date_depart' => $voyage->getDateDepart(),
                'date_retour' => $voyage->getDateRetour(),
                'prix' => $voyage->getPrix(),
                'place' => $voyage->getPlace(),
                'type' => $voyage->getType(),
            ]);
            return "Voyage ajouté avec succès !";
        } catch (PDOException $e) {
            echo 'Erreur PDO : ' . $e->getMessage();
            return "Erreur lors de l'ajout du voyage.";
        } catch (Exception $e) {
            echo 'Erreur générale : ' . $e->getMessage();
            return "Erreur générale.";
        }
    }

    // Liste des voyages
    public function listVoyages()
    {
        $sql = "SELECT * FROM voyage";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Supprimer un voyage
    public function deleteVoyage($id)
    {
        $sql = "DELETE FROM voyage WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Afficher un voyage
    public function showVoyage($id)
    {
        $sql = "SELECT * FROM voyage WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            $voyage = $query->fetch(PDO::FETCH_ASSOC);
            
            if (!$voyage) {
                return false;
            }
            
            return $voyage;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'affichage du voyage : ' . $e->getMessage());
        }
    }

    // Mettre à jour un voyage
    public function updateVoyage($voyage, $id)
    {
        $sql = "UPDATE voyage SET
                    titre = :titre,
                    destination = :destination,
                    description = :description,
                    date_depart = :date_depart,
                    date_retour = :date_retour,
                    prix = :prix,
                    place = :place,
                    type = :type
                WHERE id = :id";

        $db = config::getConnexion();
        $query = $db->prepare($sql);

        $query->bindValue(':titre', $voyage->getTitre());
        $query->bindValue(':destination', $voyage->getDestination());
        $query->bindValue(':description', $voyage->getDescription());
        $query->bindValue(':date_depart', $voyage->getDateDepart());
        $query->bindValue(':date_retour', $voyage->getDateRetour());
        $query->bindValue(':prix', $voyage->getPrix());
        $query->bindValue(':place', $voyage->getPlace());
        $query->bindValue(':type', $voyage->getType());
        $query->bindValue(':id', $id);

        return $query->execute();
    }
}
?>
