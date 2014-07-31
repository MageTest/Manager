<?php
/**
 * Created by PhpStorm.
 * User: jporter
 * Date: 7/17/14
 * Time: 4:25 PM
 */

namespace MageTest\Manager\Builders;


interface BuilderInterface {
    public function __construct($modelType);
    /**
     * Build fixture model
     */
    public function build();
} 