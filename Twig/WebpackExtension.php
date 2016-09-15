<?php

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

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
class WebpackExtension extends \Twig_Extension
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

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('webpack_asset', [$this, 'getAssetUrl'])
        ];
    }

    public function getAssetUrl($path, $chunkName, $extension = 'js', $packageName = null)
    {
        return
            $this->packages->getUrl(
                $this->webpackHelper->getAssetUrl($path, $chunkName, $extension),
                $packageName
            );
    }

    public function getName()
    {
        return 'fh_webpack';
    }
}
