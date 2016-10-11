<?php

namespace EmcVoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('EmcVoteBundle:Default:index.html.twig');
    }
    /**
     * @Route("/api/tmpl/vote-popup.html")
     */
    public function votePopupAction() {
        return $this->render('EmcVoteBundle:Default:popup.html.twig');
    }
    /**
     * @Route("/api/tmpl/not-voted-popup.html")
     */
    public function notVotedPopupAction() {
        return $this->render('EmcVoteBundle:Default:notvoted.html.twig');
    }
}
