<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

//        $mailbox = new \PhpImap\Mailbox(
//            '{imap.mailbox.org:993/imap/ssl}Inbox',
//            'dave@davedoeswork.com',
//            '5P9zL1sfdbFe6#d$AOEg2FHNIYEMEQ'
//        );

        $mailbox = new \PhpImap\Mailbox(
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
