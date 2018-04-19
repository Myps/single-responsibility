<?php

namespace App\Service;

use App\Entity\Plant;
use Psr\Log\LoggerInterface;

class LazyManImportService implements ILazyManImportService
{
    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $remoteFile;

    /** @var string */
    private $localFile;

    /**
     * CarImportService constructor.
     *
     * @param string $remoteFile
     * @param string $localFile
     * @param LoggerInterface $logger
     */
    public function __construct(string $remoteFile, string $localFile, LoggerInterface $logger)
    {
        $this->remoteFile = $remoteFile;
        $this->localFile = $localFile;
        $this->logger = $logger;
    }

    public function run()
    {
        try {
            $this->downloadFile();
            $this->parse();
        } catch (\Exception $exception) {
            $this->logger->error("Parse failed: {$exception->getMessage()}.");
        }
    }

    /**
     * @throws \Exception
     */
    private function downloadFile()
    {
        $copied = copy($this->remoteFile, $this->localFile);
        if ($copied === false) {
            throw new \Exception("Downloading file {$this->remoteFile} failed.");
        }
    }

    /**
     * @throws \Exception
     */
    private function parse()
    {
        $xml = simplexml_load_file($this->localFile);
        if (!$xml) {
            throw new \Exception('Simplexml load file failed.');
        }

        $items = $xml->xpath('//CATALOG//PLANT');

        $entities = [];
        foreach ($items as $item) {
            $entity = new Plant(
                self::mapCommon($item->COMMON),
                self::mapBotanical($item->BOTANICAL),
                self::mapZone($item->ZONE),
                self::mapLight($item->LIGHT),
                self::mapPrice($item->PRICE),
                self::mapCurrency($item->PRICE),
                self::mapAvailability($item->AVAILABILITY)
            );
            $entities[] = $entity;
            // Persist entity
            // $this->entityManager->persist($entity)
        }
        // Save entities to database
        // $this->entityManager->flush();
    }

    private static function mapCommon($input): string
    {
        return (string)$input;
    }

    private static function mapBotanical($input): string
    {
        return (string)$input;
    }

    private static function mapZone($input): int
    {
        return (int)$input;
    }

    /**
     * @param $input
     * @return string
     * @throws \Exception
     */
    private static function mapLight($input): string
    {
        $input = (string)$input;

        switch ($input) {
            case 'Sun':
                return 'sun';
                break;
            case 'Sunny':
                return 'sun';
                break;
            case 'Mostly Sunny':
                return 'mostly_sun';
                break;
            case 'Sun or Shade':
                return 'no_matter';
                break;
            case 'Mostly Shady':
                return 'mostly_shady';
                break;
            case 'Shade':
                return 'shade';
                break;
            default:
                throw new \Exception("Light type {$input} is not mapped.");
        }
    }

    private static function mapPrice($input): float
    {
        preg_match('/^([^0-9])++(.*+)/', (string)$input, $matches);

        return (float) $matches[2];
    }

    private static function mapCurrency($input): string
    {
        preg_match('/^([^0-9])++(.*+)/', (string)$input, $matches);

        return $matches[1];
    }

    private static function mapAvailability($input): int
    {
        return (string)$input;
    }
}