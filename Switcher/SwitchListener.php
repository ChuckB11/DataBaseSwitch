<?php

namespace Nucleus\DataBaseSwitchBundle\Switcher;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;


class SwitchListener
{
    // Notre processeur
    protected $switchExec;

    protected $parameters;

    protected $container;

    public function __construct(SwitchExec $switchExec)
    {
        $this->switchExec = $switchExec;
    }

    public function processSwitch(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        // $db_name = domaine avec str replace -,. -> _
        $db_name = str_replace(["-","."], "_", $_SERVER['HTTP_HOST']);

        $infoDatabase = array(
            'dbname'       => $db_name
        );
        $connection = 'default_connection';

        $response = $this->switchExec->switchDatabase($infoDatabase, $connection);

    }
}