<?php

namespace MageTest\Manager\Attributes\Provider;

use Symfony\Component\Yaml\Yaml;

class YamlProvider implements ProviderInterface
{
    private $file;

    public function readAttributes()
    {
       return array_pop($this->getYaml());
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
        return $yaml = Yaml::parse($this->file);
    }
}
