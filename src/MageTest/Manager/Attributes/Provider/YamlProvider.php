<?php

namespace MageTest\Manager\Attributes\Provider;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlProvider
 * @package MageTest\Manager\Attributes\Provider
 */
class YamlProvider implements ProviderInterface
{
    /**
     * @var
     */
    private $yaml;

    /**
     * @param $file
     */
    public function readFile($file)
    {
        $this->yaml = Yaml::parse($file);
    }

    /**
     * @return mixed
     */
    public function readAttributes()
    {
        return reset($this->yaml);
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return '.yml';
    }

    /**
     * @return mixed
     */
    public function getModelType()
    {
        $key = explode(' (', key($this->yaml));
        return $key[0];
    }

    /**
     * @return array
     */
    public function getFixtureDependencies()
    {
        $fixtureKey = key($this->yaml);
        $beforeBracket = substr($fixtureKey, strrpos($fixtureKey, '(')  + 1);
        return explode(' ', substr( $beforeBracket, 0, strpos( $beforeBracket, ')')));
    }

    /**
     * @return bool
     */
    public function hasFixtureDependencies()
    {
        return (strpos(key($this->yaml), '(') === false) ? false : true;
    }
}