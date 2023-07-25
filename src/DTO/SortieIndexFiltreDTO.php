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




}