<?php

namespace Shopsys\ProductFeed\HeurekaDeliveryBundle;

use Shopsys\ProductFeed\DeliveryFeedItemInterface;
use Shopsys\ProductFeed\DomainConfigInterface;
use Shopsys\ProductFeed\FeedConfigInterface;
use Shopsys\ProductFeed\FeedItemInterface;
use Shopsys\ProductFeed\FeedItemRepositoryInterface;

class HeurekaDeliveryFeedConfig implements FeedConfigInterface
{
    /**
     * @var \Shopsys\ProductFeed\DeliveryFeedItemRepositoryInterface
     */
    private $feedItemRepository;

    /**
     * @param \Shopsys\ProductFeed\FeedItemRepositoryInterface $feedItemRepository
     */
    public function __construct(FeedItemRepositoryInterface $feedItemRepository)
    {
        $this->feedItemRepository = $feedItemRepository;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Heureka - delivery';
    }

    /**
     * @return string
     */
    public function getFeedName()
    {
        return 'heureka_delivery';
    }

    /**
     * @return string
     */
    public function getTemplateFilepath()
    {
        return '@ShopsysProductFeedHeurekaDelivery/feed.xml.twig';
    }

    /**
     * @return \Shopsys\ProductFeed\FeedItemRepositoryInterface
     */
    public function getFeedItemRepository()
    {
        return $this->feedItemRepository;
    }

    /**
     * @param \Shopsys\ProductFeed\FeedItemInterface[] $items
     * @param \Shopsys\ProductFeed\DomainConfigInterface $domainConfig
     * @return \Shopsys\ProductFeed\FeedItemInterface[]
     */
    public function processItems(array $items, DomainConfigInterface $domainConfig)
    {
        return array_filter($items, function (FeedItemInterface $item) {
            return $item instanceof DeliveryFeedItemInterface;
        });
    }
}
