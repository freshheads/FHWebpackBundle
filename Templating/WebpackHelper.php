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
     */
    public function getAssetUrl($path, $name, $extension = 'js')
    {
        $assetsPath = $this->normalizePath($this->webDir . DIRECTORY_SEPARATOR . $path);
        $stats = $this->getStats($assetsPath . DIRECTORY_SEPARATOR . $this->statsFilename);

        return rtrim($path, '/')  . '/' . $stats
            ->getAssetsByChunkName()
            ->getAsset($name, sprintf('/\.%s$/', preg_quote($extension)));
    }

    public function getName()
    {
        return 'fh_webpack';
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
