## `BoxDrawer` class

[![Donate](https://img.shields.io/static/v1?label=Donate&message=paypal.me/biesior&color=brightgreen)](https://www.paypal.me/biesior/4.99EUR)
[![Donate](https://img.shields.io/static/v1?label=Donate&message=paypal.me/wnunez86&color=brightgreen)](https://www.paypal.me/wnunez86/4.99USD)
[![State](https://img.shields.io/static/v1?label=candidate&message=1.0.0-rc1&color=blue 'Latest known version')](https://github.com/biesior/box-drawer/tree/v1.0.0-rc1) <!-- __SEMANTIC_VERSION_LINE__ -->
![Updated](https://img.shields.io/static/v1?label=upated&message=2020-08-30+19:48:50&color=lightgray 'Latest known update date') <!-- __SEMANTIC_UPDATED_LINE__ -->
[![License](https://img.shields.io/static/v1?label=license&message=GPL-3-or-later&color=yellowgreen)](https://en.wikipedia.org/wiki/GNU_General_Public_License#Version_3)

<!-- 
### Disclaimer

This code is in `alpha` state, please use it carefully. Visit the GitHub repository to check if newer `stable` state is available.
-->

### Features

PHP class for creating using [BoxDrawing (Unicode block)](https://en.wikipedia.org/wiki/Box_Drawing_(Unicode_block)) for CLI scripts or HTML (data frame or table).

### Usage

Basic usage is:


### In PHP
```php
<?php

    use BiesiorNunezTeam\BoxDrawer\BoxDrawer;
    require_once 'vendor/autoload.php';
    
    // ... your other code

    $boxDrawer = new BoxDrawer();
    $boxDrawer->drawBoxesForLines('Hello world!');

    // ... your other code

    //EOF
```

### In terminal
Go to directory where you downloaded the code i.e.:



```
cd /www/project/box-drawer
```

and run sample usages like

```
php public/sample-usage.php
```

Follow onscreen instructions to select required sample, you'll see output like:

```
You need to use this script with param(s) run the help for more info php public/sample-usage.php help

Available options:

php public/sample-usage.php boxes		Samples for drawing boxes

php public/sample-usage.php charts		Charts with BoxDrawing chcracters

php public/sample-usage.php colors		For showing example how to color your data with ANSI

php public/sample-usage.php data-coloring	BoxDrawer class doesn't color your data except of first header if set,
                                                you need to do it yourself, you can use i.e. Ansi::colorize() method.
```


For more samples refer to `public/sample-usage.php` file and/or `resources` folder.;

### Contributors
- (c) 2020 Marcus Biesioroff biesior@gmail.com
- (c) 2020 Walter Francisco Núñez Cruz icarosnet@gmail.com
