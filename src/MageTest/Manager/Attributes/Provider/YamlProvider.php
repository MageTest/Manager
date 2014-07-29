<?php

namespace MageTest\Manager\Attributes\Provider;

use Mage;
use Symfony\Component\Yaml\Parser;

class YamlProvider implements ProviderInterface
{
    private $yaml;
    private $file;

    public function __construct()
    {
        $this->yaml = new Parser();
    }

    public function readAttributes()
    {
       $yaml = $this->getYaml();
       $array = array_pop($yaml);
       return array_merge($array['required'], $array['defaults']);
    }

    public function getModelType()
    {
        return key($this->getYaml());
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    private function getYaml()
    {
        return $this->yaml->parse(file_get_contents($this->file));
    }
}
