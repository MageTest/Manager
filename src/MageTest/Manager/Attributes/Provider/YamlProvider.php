<?php

namespace MageTest\Manager\Attributes\Provider;

use Mage;
use Symfony\Component\Yaml\Parser;

class YamlProvider implements ProviderInterface
{
    private $yaml;
    private $file;

    public function __construct($file)
    {
        $this->yaml = new Parser();
        $this->file = $file;
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

    private function getYaml()
    {
        return $this->yaml->parse(file_get_contents($this->file));
    }
}
