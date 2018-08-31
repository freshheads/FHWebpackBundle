<?php

/*
 * This file is part of the Freshheads Webpack bundle.
 *
 * (c) Freshheads B.V. <info@freshheads.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FH\Bundle\WebpackBundle\Templating;

use FH\WebpackStats\Parser\StandardParser;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class WebpackHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WebpackHelper
     */
    private $helper;

    protected function setUp()
    {
        $this->helper = new WebpackHelper(new StandardParser(), __DIR__ . '/web');
    }

    public function testAssetsByChunkNameIsFound()
    {
        $url = $this->helper->getAssetUrl('', 'app', 'js');

        $this->assertEquals('/app.js', $url);
    }

    public function testAssetsWithForwardSlashIsFound()
    {
        $url = $this->helper->getAssetUrl('', 'img/icon-facebook', 'jpg');

        $this->assertEquals('/img/icon-facebook.13fc22a6e0bfbe76b20cf09c284531d7.jpg', $url);
    }

    public function testAssetIsFound()
    {
        $url = $this->helper->getAssetUrl('', 'font', 'woff2');

        $this->assertEquals('/font.90afa358faca7496fd211daa167dcb46.woff2', $url);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAssetCouldNotBeFound()
    {
        $this->helper->getAssetUrl('', 'xuifysdiufysdifysdifysdi');
    }
}
