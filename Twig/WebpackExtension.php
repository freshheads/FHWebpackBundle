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

namespace FH\Bundle\WebpackBundle\Twig;

use FH\Bundle\WebpackBundle\Templating\WebpackHelper;
use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
class WebpackExtension extends AbstractExtension
{
    /**
     * @var Packages
     */
    private $packages;

    /**
     * @var WebpackHelper
     */
    private $webpackHelper;

    public function __construct(Packages $packages, WebpackHelper $webpackHelper)
    {
        $this->packages = $packages;
        $this->webpackHelper = $webpackHelper;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('webpack_asset', [$this, 'getAssetUrl']),
        ];
    }

    public function getAssetUrl(string $path, string $chunkName, string $extension = 'js', string $packageName = null): string
    {
        return
            $this->packages->getUrl(
                $this->webpackHelper->getAssetUrl($path, $chunkName, $extension),
                $packageName
            );
    }
}
