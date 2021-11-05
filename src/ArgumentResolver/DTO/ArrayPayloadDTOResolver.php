<?php

namespace App\ArgumentResolver\DTO;

use App\Exceptions\DeserializationFailedException;
use App\Interfaces\DTO\JsonPayloadArray;
use Throwable;

/**
 * Specific ArgumentResolver to handle automatic resolving of DTOList objects that implement JsonPayloadArray
 */
class ArrayPayloadDTOResolver extends AbstractDTOResolver
{

    /**
     * @psalm-pure
     * @return class-string
     */
    protected function getInterfaceName(): string
    {
        return JsonPayloadArray::class;
    }

    /**
     * @param class-string $className
     * @param string $payload
     *
     * @return object[]|object
     * @throws DeserializationFailedException
     */
    protected function parseTargetDTO(string $className, string $payload): array|object
    {
        $postedArray = json_decode($payload);
        if (!is_array($postedArray)) {
            throw new DeserializationFailedException('Payload can`t be decoded to a proper array');
        }

        try {
            /** @var JsonPayloadArray $deserializeResult */
            $objects = $this->serializer->deserialize(
                $payload,
                'array<' . $className::getTargetClass() . '>',
                'json'
            );

            return new $className($objects);
        } catch (Throwable $error) {
            $this->logger->critical('Error occurred during deserialization of request payload', [
                'message' => $error->getMessage(),
                'payload' => $payload
            ]);

            throw new DeserializationFailedException('Can not parse provided array');
        }
    }

}
