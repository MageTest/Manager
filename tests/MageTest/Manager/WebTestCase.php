<?php
namespace MageTest\Manager;

use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;
use Mage;
use MageTest\Manager\Attributes\Provider\YamlProvider;
use PHPUnit_Framework_TestCase;

abstract class WebTestCase extends PHPUnit_Framework_Testcase
{
    /**
     * @var \Behat\Mink\Mink
     */
    private $mink;

    /*
     * @var \MageTest\Manager\FixtureManager
     */
    public $manager;

    protected function setUp()
    {
        \Mage::init();
        $this->mink = new Mink(array(
            'goutte' => new Session(new GoutteDriver())
        ));
        $this->mink->setDefaultSessionName('goutte');
        $this->manager = new FixtureManager(new YamlProvider());
    }

    protected function tearDown()
    {
        $this->manager->clear();
        Mage::getSingleton("core/session")->unsetAll();
        Mage::unregister('controller');
    }

    /**
     * @param null|string $name
     *
     * @return Session
     */
    protected function getSession($name = null)
    {
        return $this->mink->getSession($name);
    }

    /**
     * @param null|string $name
     *
     * @return WebAssert
     */
    protected function assertSession($name = null)
    {
        return $this->mink->assertSession($name);
    }

    /**
     * @param $username
     * @param $pass
     *
     */
    protected function adminLogin($username, $pass)
    {
        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/admin');
        $session->getPage()->fillField('username', $username);
        $session->getPage()->fillField('login', $pass);
        $session->getPage()->pressButton('Login');
    }

    /**
     * @param $email
     * @param $pass
     */
    protected function customerLogin($email, $pass)
    {
        $session = $this->getSession();
        $session->visit(getenv('BASE_URL') . '/customer/account/login');
        $session->getPage()->fillField('Email Address', $email);
        $session->getPage()->fillField('Password', $pass);
        $session->getPage()->pressButton('Login');
    }
} 