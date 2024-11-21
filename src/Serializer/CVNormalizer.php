<?php


namespace App\Serializer;

use App\Entity\CV;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

final class CVNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'CV_NORMALIZER_ALREADY_CALLED';

    public function __construct(private readonly StorageInterface $storage)
    {
    }

    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        $object->contentUrl = $this->storage->resolveUri($object, 'file');

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return !isset($context[self::ALREADY_CALLED]) && $data instanceof CV;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            CV::class => false,
        ];
    }
}
