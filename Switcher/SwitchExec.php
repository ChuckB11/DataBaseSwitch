<?php

namespace Nucleus\DataBaseSwitchBundle\Switcher;


use Nucleus\DataBaseSwitchBundle\DependencyInjection\DataBaseSwitchExtension;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;


class SwitchExec
{
    protected $container;

    public function __construct($kernel)
    {
        $this->container = $kernel->getContainer();
    }

    public function switchDatabase($infoDatabase, $connection_name = 'default_connection') {

        $connection = $this->container->get(sprintf('doctrine.dbal.'.$connection_name));

        $refConn = new \ReflectionObject($connection);
        $refParams = $refConn->getProperty('_params');
        $refParams->setAccessible('public'); //we have to change it for a moment
        $params = $refParams->getValue($connection);

        foreach($infoDatabase as $key => $info){
            $params[$key] = $info;
        }

        $refParams->setAccessible('private');
        $refParams->setValue($connection, $params);

        $connection = $this->container->get(sprintf('doctrine.dbal.'.$connection_name));

    }
}