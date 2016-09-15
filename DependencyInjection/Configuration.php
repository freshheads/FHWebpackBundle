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

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fh_webpack');
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
