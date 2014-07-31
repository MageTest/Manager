<?php

namespace MageTest\Manager\Attributes\Provider;

use Symfony\Component\Yaml\Yaml;

class YamlProvider implements ProviderInterface
{
    private $yaml;

    public function readFile($file)
    {
        $this->yaml = Yaml::parse($file);
    }

    public function readAttributes()
    {
       return reset($this->yaml);
    }

    public function getFileType()
    {
        return '.yml';
    }

    public function getModelType()
    {
        $key = explode(' (', key($this->yaml));
        return $key[0];
    }

    public function getFixtureDependencies()
    {
        $beforeBracket = substr(key($this->yaml), strrpos(key($this->yaml), '(')  + 1);
        return explode(' ', substr( $beforeBracket, 0, strpos( $beforeBracket, ')')));
    }

    public function hasFixtureDependencies()
    {
        return (strpos(key($this->yaml), '(') === false) ? false : true;
    }
}
