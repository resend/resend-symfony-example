<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class EmailController extends AbstractController
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {

    }

    #[Route('/', name: 'app')]
    public function index(): Response
    {
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }

    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $mailer->send(
            (new Email())
                ->from('sender@example.com')
                ->to('recipient@example.com')
                ->subject('Time for Symfony Resend Mailer!')
                ->text('Sending emails is fun again!')
        );

        $this->addFlash('success', 'Email sent!');

        return $this->redirectToRoute('app');
    }

    #[Route('/email-statuses', name: 'email_statuses')]
    public function emailStatuses(
        #[Autowire(param: 'kernel.project_dir')] string $projectDir,
    ): Response
    {
        return $this->render('email/statuses.html.twig', [
            'emails' => json_decode(file_get_contents($projectDir.'/var/emails.json')),
        ]);
    }
}
