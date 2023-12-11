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

namespace FH\Bundle\WebpackBundle\Templating;

use FH\WebpackStats\Exception\ChunkNotFoundException;
use FH\WebpackStats\Exception\PropertyNotFoundException;
use FH\WebpackStats\Parser\Parser;
use FH\WebpackStats\Stats;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class WebpackHelper
{
    private const string STATS_FILENAME = 'stats.json';
    private const string ASSETS_REGEX = '/^%s\.[0-9a-zA-Z]+\.%s$/';

    private array $stats = [];

    public function __construct(
        private Parser $statsParser,
        private string $webDir,
        private string $statsFilename = self::STATS_FILENAME
    ) {
    }

    /**
     * @throws \RuntimeException
     */
    public function getAssetUrl(string $path, string $name, string $extension = 'js'): string
    {
        $assetsPath = $this->normalizePath($this->webDir . DIRECTORY_SEPARATOR . $path);
        $stats = $this->getStats($assetsPath . DIRECTORY_SEPARATOR . $this->statsFilename);

        try {
            return rtrim($path, '/')  . '/' . $this->resolveAssetByChunkName($stats, $name, $extension);

        } catch (PropertyNotFoundException $e) {
            // No chunks defined in stats.json
        } catch (ChunkNotFoundException $e) {
            // Requested chunk does not exist
        }

        try {
            return rtrim($path, '/')  . '/' . $this->resolveAsset($stats, $name, $extension);

        } catch (PropertyNotFoundException $e) {
            // No assets defined in stats.json
        } catch (\RuntimeException $e) {
            // Asset could not be found
        }

        throw new \RuntimeException(
            sprintf('Asset with name "%s" could not be found in stats.json file', $name)
        );
    }

    public function getAssetsPath(string $path, string $name, string $extension = 'js'): string
    {
        $assetUrl = $this->getAssetUrl($path, $name, $extension);
        return rtrim($this->webDir, '/') . $assetUrl;
    }

    public function getName(): string
    {
        return 'fh_webpack';
    }

    /**
     * @throws PropertyNotFoundException
     * @throws ChunkNotFoundException
     */
    private function resolveAssetByChunkName(Stats $stats, string $name, string $extension): string
    {
        return (string) $stats
            ->getAssetsByChunkName()
            ->getAsset($name, sprintf('/\.%s$/', preg_quote($extension, '/')));
    }

    /**
     * @throws PropertyNotFoundException
     * @throws \RuntimeException
     */
    private function resolveAsset(Stats $stats, string $name, string $extension): string
    {
        $expression = sprintf(self::ASSETS_REGEX, preg_quote($name, '/'), preg_quote($extension, '/'));

        foreach ($stats->getAssets() as $asset) {
            if (preg_match($expression, (string) $asset) >= 1) {
                return (string) $asset;
            }
        }

        throw new \RuntimeException(sprintf('Asset with name %s could not be found in "assets" section of stats.json file', $name));
    }

    private function getStats(string $statsFile): Stats
    {
        if (isset($this->stats[$statsFile])) {
            return $this->stats[$statsFile];
        }

        $statsJson = file_get_contents($statsFile);

        return $this->stats[$statsFile] = $this->statsParser->parse($statsJson);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function normalizePath(string $path): string
    {
        $realPath = (new \SplFileInfo($path))->getRealPath();

        if (false === $realPath) {
            throw new \InvalidArgumentException('Path does not exist');
        }

        return $realPath;
    }
}
