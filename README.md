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

### Register the bundle
```php
// config/bundles.php
return [
    // ...
    FH\Bundle\WebpackBundle\FHWebpackBundle::class => [ 'all' => true ]
];
```

Configuration
-------------

All configuration options, with default values:

```yaml
fh_webpack:
    stats_filename: stats.json
    # web/document root, assets will be referenced from this path
    web_dir: '%kernel.project_dir%/public'
```


Usage
-----

### Link webpack files

```jinja
<link rel="stylesheet" href="{{ webpack_asset('assets/frontend/build', 'app', 'css') }}" />
<script type="text/javascript" src="{{ webpack_asset('assets/frontend/build', 'app', 'js') }}"></script>
```

### Dump the contents of a webpack file

```jinja
<style type="text/css">
    {{ webpack_asset_contents('assets/frontend/build', 'email', 'css')|raw }}
</style>
```

Requirements
------------

This library works with PHP 7.4 and up.
