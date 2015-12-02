# DataBaseSwitch
Create a service that allow to switch DB dynamically using the Symfony2 EventManager

#This bundle do :
- Get the server HTTP host (ex: http://test-db-switch/app_dev.php/  =>  test-db-switch )
- Replace his - and . by some _ ( $db_name = str_replace(["-","."], "_", $_SERVER['HTTP_HOST']); )
- Overwrite dynamically the default database_name by the new one (here: test_db_switch)
- Make the connection to the new database_name before the first page loaded (using kernel.request)

#Installation
Ready in 5 steps

#Step 1 : Configuration
You'll need to prepare your app/config/config.yml to work with this bundle by creating
"default" and "customer" connections and entities managers which will be overwrited.
You will also need to map your Bundle in the entities managers

(if you have a db_password you'll have to write it in the parameters.yml, this bundle only change the db_name)

Here an example :

doctrine:

    dbal:
        default_connection:       default
        connections:
            default:
                dbname:           "%database_name%"
                user:             "%database_user%"
                password:         "%database_password%"
                host:             "%database_host%"
                driver:   pdo_mysql
                charset:  UTF8
            customer:
                dbname:
                user:       root
                password:   null
                host:       localhost
                driver:   pdo_mysql
                charset:  UTF8

    orm:
        default_entity_manager:   default
        entity_managers:
            default:
                auto_mapping: false
                connection:       default
                mappings:
                    YourAmazingBundle: ~
            customer:
                auto_mapping: false
                connection:       customer
                mappings:
                    YourAmazingBundle: ~
                    
                    
#Step 2 : Create the service
Still in the app/config, open your services.yml.
You'll need to create a service for your listener :
  
    services:
        data_base_switch.switcher.listener:
            class: ContactBundle\Switcher\SwitchListener
            arguments: [@data_base_switch.switcher.exec]
            tags:
            - { name: kernel.event_listener, event: kernel.request, method: processSwitch, priority: 255 }


#Step 3 : Composer.json
Just add the DBSwitchBundle in your composer.json and do an update

"require": {
        ...
        "dbswitch/databaseswitch": "master-dev"
    },

then,

php composer update


#Step 4 : Creating the Bundle Listener
You'll need to create a Listener in your Bundle that will Execute the method from the DBSwitchBundle as soon as possible.

In src/YourAmazingBundle/ :
- create a new "Switcher" folder (the name is not important if you change it in the namespaces)
- create "SwitchListener.php" with that code :


    <?php
    namespace YourAmazingBundle\Switcher;                                       <=== local namespace, Be carefull
    
    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\HttpKernel\Event\GetResponseEvent;
    use Symfony\Component\HttpKernel\HttpKernelInterface;
    use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
    use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
    use Nucleus\DataBaseSwitchBundle\Switcher\SwitchExec;                       <=== Don't forget this use
    
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

#Step 5 : Add the DBSwitchBundle in your AppKernel.php
... just add the DBSwitchBundle in your Kernel bundles :

$bundles = array(
            ... ,
            new Nucleus\DataBaseSwitchBundle\DataBaseSwitchBundle()
        );
        
#Job's done !
(if you have any issue, try to clear the cache when the bundle is setted up, then reload the page)
