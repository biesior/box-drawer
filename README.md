## `BoxDrawer` class

[![Donate](https://img.shields.io/static/v1?label=Donate&message=PayPal.me/biesior&color=brightgreen)](https://www.paypal.me/biesior/4.99EUR)
[![State](https://img.shields.io/static/v1?label=alpha&message=0.0.11&color=blue)](https://github.com/biesior/box-drawer/tree/0.0.11-alpha)
![Updated](https://img.shields.io/static/v1?label=upated&message=2020-08-18+02:16:21&color=lightgray)
[![License](https://img.shields.io/static/v1?label=license&message=GPL-3-or-later&color=yellowgreen)](https://en.wikipedia.org/wiki/GNU_General_Public_License#Version_3)

### Disclaimer

This code is in `alpha` state, please use it carefully. Visit the GitHub repository to check if newer `stable` state is available.

### Features

PHP class for creating using [BoxDrawing (Unicode block)](https://en.wikipedia.org/wiki/Box_Drawing_(Unicode_block)) for CLI scripts or HTML (data frame or table).

### Usage

Basic usage is:

#### In terminal
go to directory where downloaded the code i.e.

```
cd /www/project/box-drawer
```

and run sample usages like

```
php sample-usage.php
```

Follow onscreen instructions to select required sample, you'll see output like:

```
You need to use this script with show param like php sample-usage.php charts

available options:
boxes		samples for drawing boxes
charts		charts with BoxDrawing chcracters
colors		for showing example how to color your data with ANSI
data-coloring	BoxDrawer doesn't color your data except of first header if set,
                you need to do it yourself, you can use i.e. Ansi::colorize() method for this
```

### In PHP
```php
<?php

    use Vendor\Utility\BoxDrawer;
    require_once 'vendor/autoload.php';

    $boxDrawer = new BoxDrawer();
$boxDrawer->drawBoxesForLines('Hello world!');
```

For more samples refer to `sample - usage.php` and `sample_data`folder for sample usage;

### Contributors
- (c) 2020 Marcus Biesioroff biesior@gmail.com
- (c) 2020 Walter Francisco Núñez Cruz icarosnet@gmail.com
