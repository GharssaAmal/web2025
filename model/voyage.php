<?php
class Voyage
{
    private ?int $id;
    private string $titre;
    private string $description;
    private string $date_depart;
    private string $date_retour;
    private string $prix;
    private string $place;
    private string $type;
    private string $destination;

    // Constructor
    public function __construct(
        ?int $id = null,
        string $destination,
        string $titre,
        string $description,
        string $date_depart,
        string $date_retour,
        string $prix,
        string $place,
        string $type
    ) {
        $this->id = $id;
        $this->destination=$destination;
        $this->titre = $titre;
        $this->description = $description;
        $this->date_depart = $date_depart;
        $this->date_retour = $date_retour;
        $this->prix = $prix;
        $this->place = $place;
        $this->type = $type;
    }

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }
    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDateDepart(): string
    {
        return $this->date_depart;
    }

    public function setDateDepart(string $date_depart): self
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    public function getDateRetour(): string
    {
        return $this->date_retour;
    }

    public function setDateRetour(string $date_retour): self
    {
        $this->date_retour = $date_retour;
        return $this;
    }

    public function getPrix(): string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getPlace(): string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
}
?>
