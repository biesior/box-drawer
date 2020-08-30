<?php

// For IDE, Important these disabled inspections should be checked before releasing MINOR or MAJOR versions!
/** @noinspection PhpUnhandledExceptionInspection */
declare(strict_types=1);

use BiesiorNunezTeam\BoxDrawer\BoxDrawer;
use BiesiorNunezTeam\BoxDrawer\Utility\{AnsiUtility, ChartUtility};

const TAB = "\t";
const SELF_SCRIPT = "php public/sample-usage.php";
require_once 'vendor/autoload.php';

require_once 'resources/sample-data.php';

function showSampleBoxes1()
{

    // Crate BoxDrawer instance
    $boxDrawer= new BoxDrawer();
    $boxDrawer
        ->drawBoxesForLines('Hello world!');
}

function showSampleBoxes2()
{
    $fakeRes = require 'resources/sample-data-fake-res.php';
    $boxDrawer = new BoxDrawer();
    $boxDrawer
        ->setHeaderLine('Basic sample')
        ->setStyle(BoxDrawer::STYLE_NO_BORDER)
        ->drawBoxesMulticol($fakeRes);
}

function showSampleBoxes3()
{
    $dbData = require 'resources/sample-data-db-data.php';
    $boxDrawer = new BoxDrawer();
    $boxDrawer
        ->setStyle(BoxDrawer::STYLE_BORDERED)
        ->setUseAnsiColors(true)
        ->setIsFirstLineHeader(true)
        ->drawBoxesMulticol($dbData);
}

function showSampleBoxes4()
{
    $multiLineText = require 'resources/sample-data-multiline-aligned-text.php';
    $boxDrawer = new BoxDrawer();
    $boxDrawer
        ->setStyle(BoxDrawer::STYLE_BORDERED)
        ->setUseAnsiColors(true)
        ->setIsFirstLineHeader(false)
        ->setMinimumWidth(72)
        ->drawBoxesForLines($multiLineText);
}

function showSampleBoxes5()
{
    $multiLineText = require 'resources/sample-data-multiline-aligned-text.php';
    $dbData = require 'resources/sample-data-db-data.php';
    $boxDrawer = new BoxDrawer();
    $boxDrawer
        ->setStyle(BoxDrawer::STYLE_NO_BORDER)
        ->setUseAnsiColors(true)
        ->setIsFirstLineHeader(false)
        ->setMinimumWidth(74)
        ->setCenterText(true)
        ->drawBoxesForLines($multiLineText);

    $boxDrawer->reset()
        ->setStyle(BoxDrawer::STYLE_BORDERED)
        ->setUseAnsiColors(true)
        ->setIsFirstLineHeader(true)
        ->drawBoxesMulticol($dbData);

    $boxDrawer->reset()
        ->setStyle(BoxDrawer::STYLE_NO_BORDER)
        ->setIsFirstLineHeader(false)
        ->setMinimumWidth(74)
        ->setCenterText(true)
        ->setUseAnsiColors(true)
        ->drawBoxesForLines('
Success! All data saved!
We can drink now!
Aloha!
');
}

/**
 * @return string
 */
function getCurrentEnv(): string
{
    return (php_sapi_name() == 'cli') ? 'cli' : 'web';
}

$whatToShow = null;
$variant = null;
if (getCurrentEnv() == 'cli') {
    if (count($argv) > 1) {
        $whatToShow = $argv[1];
        if (count($argv) > 2) {
            $variant = $argv[2];
        }

    }
} else {

    echo 'INFO: web context is not finished yet for samples!' . PHP_EOL;
    $whatToShow = $_GET['show'] ?? null;
}

switch ($whatToShow) {
    case 'boxes':
        showSampleBoxes($variant);
        break;
    case 'charts':
        showCharts();
        break;
    case 'colors':
        showColors($variant);
        break;
    case 'data-coloring':
        showColoring();
        break;
    case 'help':
    default:
        showHints();
        break;
}

function showHints()
{
    if (getCurrentEnv() == 'cli') {
        echo PHP_EOL . 'You need to use this script with param(s) run the help for more info ' . AnsiUtility::colorize(SELF_SCRIPT . ' help', AnsiUtility::FOREGROUND_GREEN) . PHP_EOL . PHP_EOL,
            'Available options:' . PHP_EOL . PHP_EOL .
            AnsiUtility::colorize(SELF_SCRIPT . ' boxes', AnsiUtility::FOREGROUND_GREEN) . TAB . TAB . 'Samples for drawing boxes' . PHP_EOL . PHP_EOL .
            AnsiUtility::colorize(SELF_SCRIPT . ' charts', AnsiUtility::FOREGROUND_GREEN) . TAB . TAB . 'Charts with BoxDrawing chcracters' . PHP_EOL . PHP_EOL .
            AnsiUtility::colorize(SELF_SCRIPT . ' colors', AnsiUtility::FOREGROUND_GREEN) . TAB . TAB . 'For showing example how to color your data with ANSI' . PHP_EOL . PHP_EOL .
            AnsiUtility::colorize(SELF_SCRIPT . ' data-coloring', AnsiUtility::FOREGROUND_GREEN) . TAB . 'BoxDrawer class doesn\'t color your data except of first header if set,
                                                you need to do it yourself, you can use i.e. Ansi::colorize() method.' . PHP_EOL;

    }
    echo PHP_EOL;
}


function showSampleBoxes($variant)
{
    if (getCurrentEnv() == 'cli') {
        switch ($variant) {
            case 1:
                showSampleBoxes1();
                break;
            case 2:
                showSampleBoxes2();
                break;
            case 3:
                showSampleBoxes3();
                break;
            case 4:
                showSampleBoxes4();
                break;
            case 5:
                showSampleBoxes5();
                break;

            case 'all':
                showSampleBoxes1();
                showSampleBoxes2();
                echo PHP_EOL;
                showSampleBoxes3();
                echo PHP_EOL;
                showSampleBoxes4();
                echo PHP_EOL;
                showSampleBoxes5();
                echo PHP_EOL;
                break;

            default:
                echo PHP_EOL . 'Choose detailed info for boxes i.e.  ' . AnsiUtility::colorize(SELF_SCRIPT . ' boxes 1', AnsiUtility::FOREGROUND_GREEN) . PHP_EOL . PHP_EOL,
                    'Available options:' . PHP_EOL . PHP_EOL .
                    AnsiUtility::colorize(SELF_SCRIPT . ' boxes all', AnsiUtility::FOREGROUND_GREEN) . TAB . '  For all below' . PHP_EOL . PHP_EOL .
                    AnsiUtility::colorize(SELF_SCRIPT . ' boxes 1', AnsiUtility::FOREGROUND_GREEN) . TAB . '  Basic box with single line of text' . PHP_EOL . PHP_EOL .
                    AnsiUtility::colorize(SELF_SCRIPT . ' boxes 2', AnsiUtility::FOREGROUND_GREEN) . TAB . '  Renders table with defaults settings for: ' . AnsiUtility::colorize('resources/sample-data-fake-res.php', AnsiUtility::FOREGROUND_CYAN) . PHP_EOL . PHP_EOL .
                    AnsiUtility::colorize(SELF_SCRIPT . ' boxes 3', AnsiUtility::FOREGROUND_GREEN) . TAB . '  Renders table for SQL data with some styling for: ' . AnsiUtility::colorize('resources/sample-data-db-data.php', AnsiUtility::FOREGROUND_CYAN) . PHP_EOL . PHP_EOL .
                    AnsiUtility::colorize(SELF_SCRIPT . ' boxes 4', AnsiUtility::FOREGROUND_GREEN) . TAB . '  Renders text with some alignments: ' . AnsiUtility::colorize('resources/sample-data-multiline-aligned-text.php', AnsiUtility::FOREGROUND_CYAN) . PHP_EOL . PHP_EOL .
                    AnsiUtility::colorize(SELF_SCRIPT . ' boxes 5', AnsiUtility::FOREGROUND_GREEN) . TAB . '  Renders chained boxes, combines data from samples ' . AnsiUtility::colorize('boxes 3', AnsiUtility::FOREGROUND_CYAN) . ' and ' . AnsiUtility::colorize('boxes 4', AnsiUtility::FOREGROUND_CYAN) . ' and custom text' . PHP_EOL;
                break;
        }
    }

    echo PHP_EOL;

}

function showCharts()
{
//    BoxDrawerCharts::renderAnsiColorsAndEffectsChart();
    ChartUtility::renderEntityChart();
}

function showColors($variant)
{
    switch ($variant) {
        case 'all':
        case 'basic':
        case 'bright':
        case 'effects':
            ChartUtility::renderAnsiColorsAndEffectsChart($variant);
            break;
        default:
            echo PHP_EOL . 'Choose detailed info for colors i.e. ' . AnsiUtility::colorize(SELF_SCRIPT . ' colors basic', AnsiUtility::FOREGROUND_GREEN) . PHP_EOL . PHP_EOL,
                'available options:' . PHP_EOL .
                AnsiUtility::colorize(SELF_SCRIPT . ' colors all', AnsiUtility::FOREGROUND_GREEN) . TAB . TAB . 'To display all below' . PHP_EOL .
                AnsiUtility::colorize(SELF_SCRIPT . ' colors basic', AnsiUtility::FOREGROUND_GREEN) . TAB . 'Display basic colors' . PHP_EOL .
                AnsiUtility::colorize(SELF_SCRIPT . ' colors bright', AnsiUtility::FOREGROUND_GREEN) . TAB . 'Display bright colors' . PHP_EOL .
                AnsiUtility::colorize(SELF_SCRIPT . ' colors effects', AnsiUtility::FOREGROUND_GREEN) . TAB . 'Display some effects' . PHP_EOL;
            break;
    }

}


function showColoring()
{
    // Coloring content
    echo AnsiUtility::colorize(
        'Value to colorize which should be green without additional effects',
        AnsiUtility::BACKGROUND_GREEN
    );
    echo PHP_EOL . PHP_EOL;
// With single effect as an integer
    echo AnsiUtility::colorize(
        'Value to colorize which should be magenta and underlined',
        AnsiUtility::FOREGROUND_MAGENTA,
        AnsiUtility::EFFECT_UNDERLINE
    );
    echo PHP_EOL . PHP_EOL;
// for multiple effects use array of integers as a $optionalEffects
    echo AnsiUtility::colorize(
        ' Value to colorize which should have red background, faint, italic, underlined and should slowly blink ',
        AnsiUtility::BACKGROUND_RED,
        [
            AnsiUtility::EFFECT_FAINT,
            AnsiUtility::EFFECT_ITALIC,
            AnsiUtility::EFFECT_UNDERLINE,
            AnsiUtility::EFFECT_SLOW_BLINK
        ]
    );
    echo PHP_EOL . PHP_EOL;
}
