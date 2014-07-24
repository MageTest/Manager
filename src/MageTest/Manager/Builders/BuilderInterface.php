<?php
/**
 * Created by PhpStorm.
 * User: jporter
 * Date: 7/17/14
 * Time: 4:25 PM
 */

namespace MageTest\Manager\Builders;


interface BuilderInterface {
    /**
     * Build fixture model
     */
    public function build();

    /*
     * Return the Magento model relating to the fixture
     */
    public function defaultModelFactory();
} 