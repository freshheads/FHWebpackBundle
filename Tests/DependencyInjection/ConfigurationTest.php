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

use Symfony\Component\Config\Definition\Processor;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataForProcessedConfiguration
     */
    public function testProcessedConfiguration($configs, $expectedConfig)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $this->assertEquals($expectedConfig, $config);
    }

    public function dataForProcessedConfiguration()
    {
        return [
            [
                [
                    ['web_dir' => '/web']
                ],
                [
                    'stats_filename' => 'stats.json',
                    'web_dir' => '/web'
                ]
            ],
            [
                [
                    [
                        'web_dir' => '/web',
                        'stats_filename' => 'webpack-stats.json'
                    ]
                ],
                [
                    'stats_filename' => 'webpack-stats.json',
                    'web_dir' => '/web'
                ]
            ]
        ];
    }
}
