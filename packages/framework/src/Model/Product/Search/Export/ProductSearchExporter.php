<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Product\Search\Export;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Component\Console\ProgressBarFactory;
use Shopsys\FrameworkBundle\Component\Doctrine\SqlLoggerFacade;
use Shopsys\FrameworkBundle\Model\Product\Search\ProductElasticsearchConverter;
use Shopsys\FrameworkBundle\Model\Product\Search\ProductElasticsearchRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class ProductSearchExporter
{
    protected const BATCH_SIZE = 100;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Search\Export\ProductSearchExportWithFilterRepository
     */
    protected $productSearchExportWithFilterRepository;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Search\ProductElasticsearchRepository
     */
    protected $productElasticsearchRepository;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Search\ProductElasticsearchConverter
     */
    protected $productElasticsearchConverter;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Console\ProgressBarFactory
     */
    protected $progressBarFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Doctrine\SqlLoggerFacade
     */
    protected $sqlLoggerFacade;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Search\Export\ProductSearchExportWithFilterRepository $productSearchExportWithFilterRepository
     * @param \Shopsys\FrameworkBundle\Model\Product\Search\ProductElasticsearchRepository $productElasticsearchRepository
     * @param \Shopsys\FrameworkBundle\Model\Product\Search\ProductElasticsearchConverter $productElasticsearchConverter
     * @param \Shopsys\FrameworkBundle\Component\Console\ProgressBarFactory $progressBarFactory
     * @param \Shopsys\FrameworkBundle\Component\Doctrine\SqlLoggerFacade $sqlLoggerFacade
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(
        ProductSearchExportWithFilterRepository $productSearchExportWithFilterRepository,
        ProductElasticsearchRepository $productElasticsearchRepository,
        ProductElasticsearchConverter $productElasticsearchConverter,
        ProgressBarFactory $progressBarFactory,
        SqlLoggerFacade $sqlLoggerFacade,
        EntityManagerInterface $entityManager
    ) {
        $this->productSearchExportWithFilterRepository = $productSearchExportWithFilterRepository;
        $this->productElasticsearchRepository = $productElasticsearchRepository;
        $this->productElasticsearchConverter = $productElasticsearchConverter;
        $this->progressBarFactory = $progressBarFactory;
        $this->sqlLoggerFacade = $sqlLoggerFacade;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $domainId
     * @param string $locale
     * @param \Symfony\Component\Console\Style\SymfonyStyle $symfonyStyleIo
     */
    public function exportWithOutput(int $domainId, string $locale, SymfonyStyle $symfonyStyleIo): void
    {
        $this->sqlLoggerFacade->temporarilyDisableLogging();

        $startFrom = 0;
        $exportedIds = [];
        $totalCount = $this->productSearchExportWithFilterRepository->getProductTotalCountForDomain($domainId);

        $progressBar = $this->progressBarFactory->create($symfonyStyleIo, $totalCount);

        do {
            $progressBar->setProgress(min($startFrom, $totalCount));

            $batchExportedIds = $this->exportBatch($domainId, $locale, $startFrom);
            $exportedIds = array_merge($exportedIds, $batchExportedIds);

            $startFrom += static::BATCH_SIZE;

            $this->entityManager->clear();
        } while (!empty($batchExportedIds));

        $progressBar->finish();

        $this->removeNotUpdated($domainId, $exportedIds);

        $this->sqlLoggerFacade->reenableLogging();
    }

    /**
     * @param int $domainId
     * @param string $locale
     * @param int[] $productIds
     */
    public function exportIds(int $domainId, string $locale, array $productIds): void
    {
        $productsData = $this->productSearchExportWithFilterRepository->getProductsDataForIds($domainId, $locale, $productIds);
        if (count($productsData) === 0) {
            $this->productElasticsearchRepository->delete($domainId, $productIds);

            return;
        }

        $this->exportProductsData($domainId, $productsData);
        $exportedIds = $this->productElasticsearchConverter->extractIds($productsData);

        $idsToDelete = array_diff($productIds, $exportedIds);

        if ($idsToDelete !== []) {
            $this->productElasticsearchRepository->delete($domainId, $idsToDelete);
        }
    }

    /**
     * @param int $domainId
     * @param array $productsData
     */
    protected function exportProductsData(int $domainId, array $productsData): void
    {
        $data = $this->productElasticsearchConverter->convertExportBulk($productsData);
        $this->productElasticsearchRepository->bulkUpdate($domainId, $data);
    }

    /**
     * @param int $domainId
     * @param string $locale
     * @param int $startFrom
     * @return int[]
     */
    protected function exportBatch(int $domainId, string $locale, int $startFrom): array
    {
        $productsData = $this->productSearchExportWithFilterRepository->getProductsData($domainId, $locale, $startFrom, static::BATCH_SIZE);
        if (count($productsData) === 0) {
            return [];
        }

        $data = $this->productElasticsearchConverter->convertExportBulk($productsData);
        $this->productElasticsearchRepository->bulkUpdate($domainId, $data);

        return $this->productElasticsearchConverter->extractIds($productsData);
    }

    /**
     * @param int $domainId
     * @param int[] $exportedIds
     */
    protected function removeNotUpdated(int $domainId, array $exportedIds): void
    {
        $this->productElasticsearchRepository->deleteNotPresent($domainId, $exportedIds);
    }
}
