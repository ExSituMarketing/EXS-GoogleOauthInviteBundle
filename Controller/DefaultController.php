<?php

namespace EXS\GoogleOauthInviteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EXSGoogleOauthInviteBundle:Default:index.html.twig');
    }
}
