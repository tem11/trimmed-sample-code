<?php

namespace App\DTO\Payment;

use App\Interfaces\DTO\JsonPayloadArray;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-immutable
 */
class NotificationList implements JsonPayloadArray
{
    public function __construct(
        #[Assert\NotBlank]
        #[Type('Array<' . Notification::class . '>')]
        #[Assert\Valid]
        private array $notifications,
    ) {}

    public static function getTargetClass(): string
    {
        return Notification::class;
    }

    /**
     * @return Notification[]
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }


}
