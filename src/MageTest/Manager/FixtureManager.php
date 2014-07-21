<?php
/**
 * Created by PhpStorm.
 * User: jporter
 * Date: 7/18/14
 * Time: 5:06 PM
 */

namespace MageTest\Manager;

use MageTest\Manager\Builders\BuilderInterface;
use MageTest\Manager\Builders\CustomerBuilder;

class FixtureManager
{
    private $fixtures;

    public function __construct()
    {
        $this->fixtures = array();
    }

    public function create($name, BuilderInterface $builder)
    {
        if($this->hasFixture($name))
        {
            throw new \InvalidArgumentException("Fixture $name has already been set. Please use unique names.");
        }

        $model = $builder->build();
        $model->save();
        return $this->fixtures[$name] = $model;
    }

    public function get($name)
    {
        if(!$this->hasFixture($name))
        {
            throw new \InvalidArgumentException("Could not find a fixture: $name");
        }
        return $this->fixtures[$name];
    }

    public function clear()
    {
        foreach ($this->fixtures as $fixture) {
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
            $fixture->delete();
            \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);
        }
        $this->fixtures = array();
    }

    private function hasFixture($name) {
        return array_key_exists($name, $this->fixtures);
    }
}
