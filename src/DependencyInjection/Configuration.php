<?php
namespace NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package NotificationBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('notification');

        $rootNode
            ->children()
                ->arrayNode('clients')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('from')
                                ->isRequired()
                            ->end()
                            ->scalarNode('reply_to')
                                ->isRequired()
                            ->end()
                            ->arrayNode('template')
                                ->children()
                                    ->scalarNode('path')
                                        ->isRequired()
                                    ->end()
                                    ->variableNode('vars')
                                    ->end()
                                    ->variableNode('css')
                                    ->end()
                                    ->arrayNode('image')
                                        ->isRequired()
                                        ->children()
                                            ->scalarNode('prefix')
                                                ->isRequired()
                                            ->end()
                                            ->scalarNode('path')
                                                ->isRequired()
                                            ->end()
                                        ->end()
                                    ->end()
                                    ->scalarNode('subject_name')
                                        ->defaultValue('subject.txt.twig')
                                    ->end()
                                    ->scalarNode('html_name')
                                        ->defaultValue('email.html.twig')
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('utm')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('utm_source')
                                        ->defaultValue('email')
                                    ->end()
                                    ->scalarNode('utm_medium')
                                        ->defaultValue('transaction')
                                    ->end()
                                    ->scalarNode('utm_campaign')
                                        ->defaultValue('default')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
