<?php

namespace MageTest\Manager\Builders;

/**
 * Interface BuilderInterface
 * @package MageTest\Manager\Builders
 */
interface BuilderInterface {
    /*
     * Magento model type required in construct e.g catalog/product
     * @param $modelType
     */
    public function __construct($modelType);

    /**
     * Build fixture model
     */
    public function build();
} 