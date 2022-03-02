CHANGELOG
===================

This changelog references the relevant changes (bug and security fixes).

2.x.x
-----

  * Added support for Symfony 6.x
  * Removed deprecated `fh_webpack.twig_extension` service alias
  * Removed deprecated `fh_webpack.webpack_helper` service alias
  * Removed deprecated `fh_webpack.stats_parser` service alias
  * Dropped support for Symfony 4.x
  * Dropped support for PHP version prior to 7.4

1.4.0
-----

  * Drops support for Symfony versions below 4.4
  * Allows php version 7.2 and higher
  * Runs tests against Symfony's phpunit bridge
  * Updates namespace of test classes to match psr-4 standards
  * Marks bundle config classes as final in docblocks; will be final in next major

1.3.0
-----

  * Dropped support for php 5.x
  * Updated travis config
  * Dropped support for Symfony 2.x
  * Dropped support for Twig 1.x
  * Updated testsuite to phpunit ^8.4 
  * Updated phpunit config to [best practices](https://thephp.cc/dates/2019/11/symfonycon/phpunit-best-practices) (slide 52)
  * Added compatibility for Symfony 5.x
  * Added compatibility for Twig 3.x
  * Replaced parameter kernel.root_dir with kernel.project_dir

1.2.0
-----

  * Support resolving of assets (besides assets by chunkname) from stats.json

1.1.0
-----

  * Added compatibility for Symfony 4.x
