<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatuberprufenController extends AbstractController{
      /**
     * @Route("/", name="main")
     */

    public function index(){
        return $this->render(view:'index.html.twig');
    }

}