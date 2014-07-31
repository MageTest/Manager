<?php
/**
 * Created by PhpStorm.
 * User: jporter
 * Date: 7/24/14
 * Time: 4:01 PM
 */

namespace MageTest\Manager\Attributes\Provider;


interface ProviderInterface
{
    /*
     * Reads file from provider returning attributes for magento model creation
     */
    public function readAttributes();
    /*
     * Returns magento model required for fixture
     */
    public function getModelType();

    /*
     * Reads fixture attributes from file
     */
    public function readFile($file);
}