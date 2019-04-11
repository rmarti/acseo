<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;

class QuestionListe
{
    protected $questions;

    public function __construct()
    {
      $this->questions = new ArrayCollection();
    }

    public function getQuestions()
    {
      return $this->questions;
    }
}

?>
