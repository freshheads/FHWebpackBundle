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
final class WebpackExtension extends AbstractExtension
{
    public function __construct(
        private readonly Packages $packages,
        private readonly WebpackHelper $webpackHelper,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('webpack_asset', [$this, 'getAssetUrl']),
            new TwigFunction('webpack_asset_contents', [$this, 'getAssetContents']),
        ];
    }

    public function getAssetUrl($path, $chunkName, $extension = 'js', $packageName = null): string
    {
        return
            $this->packages->getUrl(
                $this->webpackHelper->getAssetUrl($path, $chunkName, $extension),
                $packageName
            );
    }

    public function getAssetContents($path, $chunkName, $extension = 'js'): string
    {
        $assetsPath = $this->webpackHelper->getAssetsPath($path, $chunkName, $extension);

        if (!file_exists($assetsPath) || !is_readable($assetsPath)) {
            throw new \RuntimeException(
                sprintf('Asset with name "%s" could not be found or is not readable.', $chunkName)
            );
        }

        return file_get_contents($assetsPath);
    }
}
