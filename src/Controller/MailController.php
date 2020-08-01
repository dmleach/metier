<?php

namespace App\Controller;

use PhpImap\Mailbox;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Used for route annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MailController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function index()
    {
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }

    /**
     * @Route("/mail/list", name="mail_list")
     */
    public function list()
    {
        $systemMessage = '';

        $mailbox = new Mailbox(
            $this->getParameter('imap.path'),
            $this->getParameter('imap.username'),
            $this->getParameter('imap.password')
        );

        try {
            $mailIds = $mailbox->searchMailbox('ALL');
        } catch (\PhpImap\Exceptions\ConnectionException $e) {
            $systemMessage = 'IMAP connection failed: ' . $e->getMessage();
        }

        return $this->render('mail/list.html.twig', [
            'controllerName' => 'MailController',
            'systemMessage' => $systemMessage,
            'mailIds' => $mailIds,
            'mail' => $mailbox->getMail($mailIds[24], false),
        ]);
    }
}
