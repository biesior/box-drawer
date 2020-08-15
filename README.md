## `BoxDrawer` class 

[![Donate](https://img.shields.io/static/v1?label=Donate&message=PayPal.me/biesior&color=brightgreen)](https://www.paypal.me/biesior/4.99EUR)
[![Stable](https://img.shields.io/static/v1?label=alpha&message=0.0.4&color=blue)](https://github.com/biesior/box-drawer/tree/0.0.4-alpha)
[![License](https://img.shields.io/static/v1?label=license&message=GPL-3-or-later&color=yellowgreen)](https://en.wikipedia.org/wiki/GNU_General_Public_License#Version_3)

### Disclaimer

This code is in `alpha` state, please use it carefully. Visit the GitHub repository to check if newer `stable` state is available.

### Features 

PHP class for creating using [BoxDrawing (Unicode block)](https://en.wikipedia.org/wiki/Box_Drawing_(Unicode_block)) for CLI scripts or HTML.

### Usage

Basic usage is:

```php
<?php

use Vendor\Utility\BoxDrawer;
require_once 'vendor/autoload.php';

$boxDrawer = new BoxDrawer();
$boxDrawer->drawBoxesForLines('Hello world!');
```

For more samples refer to `box-drawing-sample-usage.php` and `sample_data` folder for sample usage;

### Contributors
- (c) 2020 Marcus Biesioroff biesior@gmail.com
- (c) 2020 Walter Francisco Núñez Cruz
