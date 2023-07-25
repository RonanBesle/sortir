<?php

namespace App\DTO;


use App\Entity\Campus;

class SortieIndexFiltreDTO
{
    /**
     * @var Campus
     */
    public $campusRecherche;


    /**
     * @var string
     */
    public $nomRecherche;

    /**
     * @var boolean
     */
    public $organisateurBoolean = false;

    /**
     * @var boolean
     */
    public $inscritBoolean = false;

    /**
     * @var boolean
     */
    public $notInscritBoolean = false;

    /**
     * @var boolean
     */
    public $ulterieurBoolean = false;

    /**
     * @var \DateTime
     */
    public $dateHeureDebutRecherche;

    /**
     * @var \DateTime
     */
    public $dateMin;

    /**
     * @var \DateTime
     */
    public $dateMax;

    public function __construct()
    {
        // Récupérer la date du jour
        $this->dateHeureDebutRecherche = new \DateTime();
    }

}