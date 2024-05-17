<?php

namespace App\Webhook;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\Event\Mailer\MailerDeliveryEvent;
use Symfony\Component\RemoteEvent\Event\Mailer\MailerEngagementEvent;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('mailer_resend')]
readonly class ResendWebhookListener implements ConsumerInterface
{
    public function __construct(
        #[Autowire(param: 'kernel.project_dir')] private string $projectDir,
    ) {
    }

    public function consume(RemoteEvent $event): void
    {
        if ($event instanceof MailerDeliveryEvent) {
            $this->handleMailDelivery($event);
        } elseif ($event instanceof MailerEngagementEvent) {
            $this->handleMailEngagement($event);
        } else {
            // This is not an email event
            return;
        }
    }

    private function handleMailDelivery(MailerDeliveryEvent $event): void
    {
        $email = [
            'id' => $event->getId(),
            'date' => $event->getDate()->format('Y-m-d H:i:s'),
            'to' => $event->getRecipientEmail(),
            'status' => $event->getPayload()['type'],
        ];

        $emails = json_decode(file_get_contents($this->projectDir . '/var/emails.json'), true);
        $emails[] = $email;
        file_put_contents($this->projectDir . '/var/emails.json', json_encode($emails, JSON_PRETTY_PRINT));
    }

    private function handleMailEngagement(MailerEngagementEvent $event): void
    {
        $email = [
            'id' => $event->getId(),
            'date' => $event->getDate()->format('Y-m-d H:i:s'),
            'to' => $event->getRecipientEmail(),
            'status' => $event->getPayload()['event'],
        ];

        $emails = json_decode(file_get_contents($this->projectDir . '/var/emails.json'), true);
        $emails[] = $email;
        file_put_contents($this->projectDir . '/var/emails.json', json_encode($emails, JSON_PRETTY_PRINT));
    }
}
