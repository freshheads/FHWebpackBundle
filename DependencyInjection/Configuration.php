<?php

/*
 * This file is part of the Freshheads Webpack bundle.
 *
 * (c) Freshheads B.V. <info@freshheads.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FH\Bundle\WebpackBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use function method_exists;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
class Configuration implements ConfigurationInterface
{
    private const ROOT_NAME = 'fh_webpack';
    
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NAME);

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root(self::ROOT_NAME);
        }
        
        $rootNode
            ->children()
                ->scalarNode('stats_filename')
                    ->defaultValue('stats.json')
                    ->example('stats.json')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('web_dir')
                    ->example('%kernel.root_dir%/../web')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
