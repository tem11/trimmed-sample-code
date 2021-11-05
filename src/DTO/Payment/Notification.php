<?php

namespace App\DTO\Payment;

use App\Interfaces\DTO\JsonPayloadObject;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-immutable
 */
class Notification implements JsonPayloadObject
{
    public function __construct(
        #[Assert\NotBlank]
        #[Type('string')]
        private ?string $description,

        #[Assert\GreaterThan(value: 0)]
        #[Assert\NotBlank]
        #[Type('int')]
        private ?int $amount,

        #[Type('string')]
        #[Assert\NotBlank]
        private ?string $reference,

        #[Type('string')]
        #[Assert\NotBlank]
        private ?string $checksum,
    ) {}

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @return string|null
     */
    public function getChecksum(): ?string
    {
        return $this->checksum;
    }

}
