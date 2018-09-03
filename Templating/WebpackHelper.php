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

use FH\WebpackStats\Exception\ChunkNotFoundException;
use FH\WebpackStats\Exception\PropertyNotFoundException;
use FH\WebpackStats\Parser\Parser;
use FH\WebpackStats\Stats;
use Symfony\Component\Templating\Helper\Helper;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
class WebpackHelper extends Helper
{
    /**
     * @var string
     */
    const STATS_FILENAME = 'stats.json';

    const ASSETS_REGEX = '/^%s\.[0-9a-zA-Z]+\.%s$/';

    /**
     * @var string
     */
    private $webDir;

    /**
     * @var string
     */
    private $statsFilename;

    /**
     * @var array
     */
    private $stats = [];

    /**
     * @var Parser
     */
    private $statsParser;

    /**
     * @param Parser $statsParser
     * @param string $webDir path to web directory on the filesystem.
     * @param string $statsFilename
     */
    public function __construct(Parser $statsParser, $webDir, $statsFilename = self::STATS_FILENAME)
    {
        $this->webDir = $webDir;
        $this->statsFilename = $statsFilename;
        $this->statsParser = $statsParser;
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $extension
     * @return string
     * @throws \RuntimeException
     */
    public function getAssetUrl($path, $name, $extension = 'js')
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

    public function getName()
    {
        return 'fh_webpack';
    }

    /**
     * @param Stats $stats
     * @param string $name
     * @param string $extension
     * @return string
     *
     * @throws StatsException
     */
    private function resolveAssetByChunkName(Stats $stats, $name, $extension)
    {
        return $stats
            ->getAssetsByChunkName()
            ->getAsset($name, sprintf('/\.%s$/', preg_quote($extension, '/')));
    }

    /**
     * @param Stats $stats
     * @param string $path
     * @param string $name
     * @param string $extension
     * @return string
     *
     * @throws StatsException
     * @throws \RuntimeException
     */
    private function resolveAsset(Stats $stats, $name, $extension)
    {
        $expression = sprintf(self::ASSETS_REGEX, preg_quote($name, '/'), preg_quote($extension, '/'));

        foreach ($stats->getAssets() as $asset) {
            if (preg_match($expression, $asset) >= 1) {
                return (string) $asset;
            }
        }

        throw new \RuntimeException(sprintf('Asset with name %s could not be found in "assets" section of stats.json file', $name));
    }

    /**
     * @param string $statsFile
     * @return Stats
     */
    private function getStats($statsFile)
    {
        if (isset($this->stats[$statsFile])) {
            return $this->stats[$statsFile];
        }

        $statsJson = file_get_contents($statsFile);

        return $this->stats[$statsFile] = $this->statsParser->parse($statsJson);
    }

    /**
     * @param string $path
     * @return string
     */
    private function normalizePath($path)
    {
        $realPath = (new \SplFileInfo($path))->getRealPath();

        if (false === $realPath) {
            throw new \InvalidArgumentException('Path does not exist');
        }

        return $realPath;
    }
}
