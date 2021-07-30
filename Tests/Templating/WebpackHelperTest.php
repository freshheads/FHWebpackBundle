<?php

declare(strict_types=1);

/*
 * This file is part of the Freshheads Webpack bundle.
 *
 * (c) Freshheads B.V. <info@freshheads.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FH\Bundle\WebpackBundle\Tests\Templating;

use FH\Bundle\WebpackBundle\Templating\WebpackHelper;
use FH\WebpackStats\Parser\StandardParser;
use PHPUnit\Framework\TestCase;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class WebpackHelperTest extends TestCase
{
    /**
     * @var WebpackHelper
     */
    private $helper;

    protected function setUp(): void
    {
        $this->helper = new WebpackHelper(new StandardParser(), __DIR__.'/web');
    }

    public function testAssetsByChunkNameIsFound(): void
    {
        $url = $this->helper->getAssetUrl('', 'app', 'js');

        $this->assertSame('/app.js', $url);
    }

    public function testAssetsWithForwardSlashIsFound(): void
    {
        $url = $this->helper->getAssetUrl('', 'img/icon-facebook', 'jpg');

        $this->assertSame('/img/icon-facebook.13fc22a6e0bfbe76b20cf09c284531d7.jpg', $url);
    }

    public function testAssetIsFound(): void
    {
        $url = $this->helper->getAssetUrl('', 'font', 'woff2');

        $this->assertSame('/font.90afa358faca7496fd211daa167dcb46.woff2', $url);
    }

    public function testAssetCouldNotBeFound(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->helper->getAssetUrl('', 'xuifysdiufysdifysdifysdi');
    }
}
