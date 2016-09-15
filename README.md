FHWebpackBundle
===============

A Symfony bundle to create paths to webpack assets in your Twig templates.
This bundle uses the webpack statistics file to find the newest assets for a given webpack entry.


Installation
------------

Install with composer:

```bash
composer require freshheads/webpack-bundle
```

Add the bundle to your AppKernel:

```php
// app/AppKernel.php
// in AppKernel::registerBundles()
$bundles = [
    // ...
    new FH\Bundle\WebpackBundle\FHWebpackBundle(),
];
```


Configuration
-------------

All configuration options, with default values:

```yaml
fh_webpack:
    stats_filename: stats.json
    # web/document root, assets will be referenced from this path
    web_dir: '%kernel.root_dir%/../web'
```


Usage
-----

```jinja
<link rel="stylesheet" href="{{ webpack_asset('assets/frontend/build', 'app', 'css') }}" />
<script type="text/javascript" src="{{ webpack_asset('assets/frontend/build', 'app', 'js') }}"></script>
```
