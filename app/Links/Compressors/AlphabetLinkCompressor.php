<?php

namespace App\Links\Compressors;

use App\Links\Exceptions\ErrorSavingModel;
use App\Links\Exceptions\InvalidCompressingLink;
use App\Links\Exceptions\WrongFactoryAttributes;
use App\Links\Generators\LinkHashGenerator;
use App\Links\Repositories\CompressedLinkRepository;
use App\Links\Factories\CompressedLinkFactory;

/**
 * Class AlphabetLinkCompressor
 * @package App\Links\Compressors
 */
class AlphabetLinkCompressor implements LinkCompressor
{

    /**
     * @var CompressedLinkRepository
     */
    private $compressedLinkRepository;

    /**
     * @var CompressedLinkFactory
     */
    private $compressedLinkFactory;

    /**
     * @var LinkHashGenerator
     */
    private $hashGenerator;

    public function __construct(
        CompressedLinkRepository $compressedLinkRepository,
        CompressedLinkFactory $compressedLinkFactory,
        LinkHashGenerator $hashGenerator
    )
    {
        $this->compressedLinkRepository = $compressedLinkRepository;
        $this->compressedLinkFactory = $compressedLinkFactory;
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * @param string $fullLink
     * @return string
     * @throws InvalidCompressingLink
     */
    public function build(string $fullLink): string
    {
        try {
            $compressedLink = $this->compressedLinkFactory->make(['link' => $fullLink]);
            $this->compressedLinkRepository->save($compressedLink);
        } catch (WrongFactoryAttributes|ErrorSavingModel $e) {
            throw new InvalidCompressingLink();
        }

        $hash = $this->hashGenerator->getHashByNumber($compressedLink->getKey());

        return $this->buildCompressedUrl($hash);
    }

    private function buildCompressedUrl(string $hash): string
    {
        return config('compressor.domain') . '/' . $hash;
    }
}
