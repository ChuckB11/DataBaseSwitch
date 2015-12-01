<?php

namespace Nucleus\DataBaseSwitchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('data_base_switch')->children()

            ->variableNode('dbname')
            ->defaultValue("")
            ->end()
            ->variableNode('code_courtier')
            ->defaultValue("")
            ->end()
            ->variableNode('host')
            ->defaultValue("")
            ->end()
            ->variableNode('user')
            ->defaultValue("")
            ->end()
            ->variableNode('password')
            ->defaultValue("")
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
