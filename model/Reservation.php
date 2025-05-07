<?php
class Reservation
{
    private ?int $id_reservation;
    private int $id;
    private float $prix;
    private string $mode_paiement;
    private string $statut_reservation;
    private string $date_reservation;

    // Constructor
    public function __construct(
        int $id,
        float $prix,
        string $mode_paiement,
        string $statut_reservation,
        string $date_reservation,
        ?int $id_reservation = null
    ) {
        $this->id_reservation = $id_reservation;
        $this->id = $id;
        $this->prix = $prix;
        $this->mode_paiement = $mode_paiement;
        $this->statut_reservation = $statut_reservation;
        $this->date_reservation = $date_reservation;
    }

    // Getters and Setters
    public function getIdReservation(): ?int
    {
        return $this->id_reservation;
    }

    public function setIdReservation(int $id_reservation): self
    {
        $this->id_reservation = $id_reservation;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getModePaiement(): string
    {
        return $this->mode_paiement;
    }

    public function setModePaiement(string $mode_paiement): self
    {
        $this->mode_paiement = $mode_paiement;
        return $this;
    }

    public function getStatutReservation(): string
    {
        return $this->statut_reservation;
    }

    public function setStatutReservation(string $statut_reservation): self
    {
        $this->statut_reservation = $statut_reservation;
        return $this;
    }

    public function getDateReservation(): string
    {
        return $this->date_reservation;
    }

    public function setDateReservation(string $date_reservation): self
    {
        $this->date_reservation = $date_reservation;
        return $this;
    }

    // Méthodes utilitaires pour le statut de réservation
    public function getStatutReservationLibelle(): string
    {
        return match($this->statut_reservation) {
            0 => 'En attente',
            1 => 'Confirmée',
            2 => 'Annulée',
            default => 'Statut inconnu',
        };
    }

    // Méthodes utilitaires pour le mode de paiement
    public function getModePaiementLibelle(): string
    {
        return match($this->mode_paiement) {
            1 => 'Carte bancaire',
            2 => 'PayPal',
            3 => 'Virement bancaire',
            4 => 'Espèces',
            default => 'Mode de paiement inconnu',
        };
    }
}
?>
