<?php

namespace App\Controller;

use App\DTO\Payment\Notification;
use App\DTO\Payment\NotificationList;
use App\Exceptions\Payments\ReportingException;
use App\Exceptions\Payments\StorageException;
use App\Managers\NotificationManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** TRIMMED DUE TO NDA */
class PaymentsController extends AbstractController
{
    public function __construct(
        private NotificationManager $notificationManager,
        private LoggerInterface $logger
    ) {}

    #[Route('/payments', name: 'payment_report', methods: ['POST'])]
    public function report(Notification $notification): Response
    {
        try {
            $this->notificationManager->store($notification);
        } catch (StorageException|ReportingException $exception) {
            $this->logger->error(
                'Error happened when system attempted to store the notification',
                [
                    'exception_message' => $exception->getMessage(),
                    'exception_class' => $exception::class,
                    'notification_reference' => $notification->getReference()
                ]
            );

            return new Response(null, Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return new Response(null, Response::HTTP_ACCEPTED);
    }

    #[Route('/payments/batch', name: 'batch_payment_report', methods: ['POST'])]
    public function reportBatch(NotificationList $notificationList): Response
    {
        try {
            $this->notificationManager->groupStore($notificationList->getNotifications());
        } catch (StorageException|ReportingException $exception) {
            $this->logger->error(
                'Error happened when system attempted to store the notification',
                [
                    'exception_message' => $exception->getMessage(),
                    'exception_class' => $exception::class,
                ]
            );

            return new Response(null, Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return new Response(null, Response::HTTP_ACCEPTED);
    }
}
