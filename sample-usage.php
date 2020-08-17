<?php

use Vendor\Utility\Ansi;
use Vendor\Utility\BoxDrawer;
use Vendor\Utility\BoxDrawerCharts;

const TAB = "\t";
require_once 'vendor/autoload.php';

require_once 'sample-data/sample-data.php';

function showSampleBoxes1()
{
    // Crate BoxDrawer instance
    $boxDrawer = new BoxDrawer();
    $boxDrawer->drawBoxesForLines('Hello world!');

}

function showSampleBoxes2()
{
    $fakeRes   = require 'sample-data/sample-data-fake-res.php';
    $boxDrawer = new BoxDrawer();
    $boxDrawer
        ->setHeaderLine('Basic sample')
        ->setStyle(BoxDrawer::STYLE_NO_BORDER)
        ->drawBoxesMulticol($fakeRes);
}

function showSampleBoxes3()
{
    $dbData    = require 'sample-data/sample-data-db-data.php';
    $boxDrawer = new BoxDrawer();
    $boxDrawer
        ->setStyle(BoxDrawer::STYLE_BORDERED)
        ->setUseAnsiColors(true)
        ->setIsFirstLineHeader(true)
        ->drawBoxesMulticol($dbData);
}

function showSampleBoxes4()
{
    $multiLineText = require 'sample-data/sample-data-multiline-aligned-text.php';
    $boxDrawer     = new BoxDrawer();
    $boxDrawer
        ->setStyle(BoxDrawer::STYLE_BORDERED)
        ->setUseAnsiColors(true)
        ->setIsFirstLineHeader(false)
        ->setMinimumWidth(72)
        ->drawBoxesForLines($multiLineText);
}

function showSampleBoxes5()
{
    $multiLineText = require 'sample-data/sample-data-multiline-aligned-text.php';
    $dbData        = require 'sample-data/sample-data-db-data.php';
    $boxDrawer     = new BoxDrawer();
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
              ->setUseAnsiColors(true)
              ->setIsFirstLineHeader(false)
              ->setMinimumWidth(74)
              ->setCenterText(true)
              ->drawBoxesForLines('
Success! All data saved!
We can drink now!
Aloha!
'); // TODO as Walter suggested auto center would be required here
}

/**
 * @return string
 */
function getCurrentEnv(): string
{
    return (php_sapi_name() == 'cli') ? 'cli' : 'web';
}

$whatToShow = null;
$variant    = null;
if (getCurrentEnv() == 'cli') {
    if (count($argv) > 1) {
        $whatToShow = $argv[1];
        if (count($argv) > 2) {
            $variant = $argv[2];
        }

    }
} else {

    echo 'INFO: web context is not finished yet for samples!'.PHP_EOL;
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
    default:
        showHints();
        break;
}

function showHints(): void
{
    if (getCurrentEnv() == 'cli') {
        echo PHP_EOL.'You need to use this script with show param like '.Ansi::colorize('php sample-usage.php charts', Ansi::FOREGROUND_GREEN).PHP_EOL.PHP_EOL,
        'available options:'.PHP_EOL.
        Ansi::colorize('boxes', Ansi::FOREGROUND_GREEN).TAB.TAB.'samples for drawing boxes'.PHP_EOL.
        Ansi::colorize('charts', Ansi::FOREGROUND_GREEN).TAB.TAB.'charts with BoxDrawing chcracters'.PHP_EOL.
        Ansi::colorize('colors', Ansi::FOREGROUND_GREEN).TAB.TAB.'for showing example how to color your data with ANSI'.PHP_EOL.
        Ansi::colorize('data-coloring', Ansi::FOREGROUND_GREEN).TAB.'BoxDrawer doesn\'t color your data except of first header if set,
                you need to do it yourself, you can use i.e. Ansi::colorize() method for this'.PHP_EOL;

    }
    echo PHP_EOL;
}

function showHintsForBoxes($env): void
{
    if (getCurrentEnv() == 'cli') {
        echo PHP_EOL.'You need to use this script with show param like '.Ansi::colorize('php sample-usage.php charts', Ansi::FOREGROUND_GREEN).PHP_EOL.PHP_EOL,
        'available options:'.PHP_EOL.
        Ansi::colorize('boxes 1', Ansi::FOREGROUND_GREEN).TAB.'Samples for drawing boxes '.PHP_EOL.
        Ansi::colorize('boxes 2', Ansi::FOREGROUND_GREEN).TAB.'Charts with BoxDrawing characters'.PHP_EOL.
        Ansi::colorize('boxes 3', Ansi::FOREGROUND_GREEN).TAB.'For showing example how to color your data with ANSI'.PHP_EOL;

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
                echo PHP_EOL.'Choose the box sample to display like '.Ansi::colorize('php sample-usage.php boxes 1', Ansi::FOREGROUND_GREEN).PHP_EOL.PHP_EOL,
                'available options:'.PHP_EOL.
                Ansi::colorize('boxes all', Ansi::FOREGROUND_GREEN).' For all below'.PHP_EOL.
                Ansi::colorize('boxes 1', Ansi::FOREGROUND_GREEN).TAB.'  Basic box with single line of text'.PHP_EOL.
                Ansi::colorize('boxes 2', Ansi::FOREGROUND_GREEN).TAB.'  Renders table with defaults settings for: '.Ansi::colorize('sample-data/sample-data-fake-res.php', Ansi::FOREGROUND_CYAN).PHP_EOL.
                Ansi::colorize('boxes 3', Ansi::FOREGROUND_GREEN).TAB.'  Renders table for SQL data with some styling for: '.Ansi::colorize('sample-data/sample-data-db-data.php', Ansi::FOREGROUND_CYAN).PHP_EOL.
                Ansi::colorize('boxes 4', Ansi::FOREGROUND_GREEN).TAB.'  Renders text with some alignments: '.Ansi::colorize('sample-data/sample-data-multiline-aligned-text.php', Ansi::FOREGROUND_CYAN).PHP_EOL.
                Ansi::colorize('boxes 5', Ansi::FOREGROUND_GREEN).TAB.'  Renders chained boxes, combines data from samples '.Ansi::colorize('boxes 3', Ansi::FOREGROUND_CYAN).' and '.Ansi::colorize('boxes 4', Ansi::FOREGROUND_CYAN).' and custom text'.PHP_EOL;
                break;
        }
    }

    echo PHP_EOL;

}

function showCharts(): void
{
//    BoxDrawerCharts::renderAnsiColorsAndEffectsChart();
    BoxDrawerCharts::renderEntityChart();
}

function showColors($variant): void
{
    switch ($variant) {
        case 'all':
        case 'basic':
        case 'bright':
        case 'effects':
            BoxDrawerCharts::renderAnsiColorsAndEffectsChart($variant);
            break;
        default:
            echo PHP_EOL.'Choose the box sample to display like '.Ansi::colorize('php sample-usage.php boxes 1', Ansi::FOREGROUND_GREEN).PHP_EOL.PHP_EOL,
            'available options:'.PHP_EOL.
            Ansi::colorize('colors all', Ansi::FOREGROUND_GREEN).TAB.'To display all below'.PHP_EOL.
            Ansi::colorize('colors basic', Ansi::FOREGROUND_GREEN).TAB.'Display basic colors'.PHP_EOL.
            Ansi::colorize('colors bright', Ansi::FOREGROUND_GREEN).TAB.'Display bright colors'.PHP_EOL.
            Ansi::colorize('colors effects', Ansi::FOREGROUND_GREEN).TAB.'Display some effects'.PHP_EOL;
            break;
    }

}

function showColorsBasic()
{
    echo 'Basic colors';
    echo PHP_EOL;
}

function showColoring()
{
    // Coloring content
    echo Ansi::colorize(
        'Value to colorize which should be green without additional effects',
        Ansi::BACKGROUND_GREEN
    );
    echo PHP_EOL.PHP_EOL;
// With single effect as an integer
    echo Ansi::colorize(
        'Value to colorize which should be magenta and underlined',
        Ansi::FOREGROUND_MAGENTA,
        Ansi::EFFECT_UNDERLINE
    );
    echo PHP_EOL.PHP_EOL;
// for multiple effects use array of integers as a $optionalEffects
    echo Ansi::colorize(
        ' Value to colorize which should have red background, faint, italic, underlined and should slowly blink ',
        Ansi::BACKGROUND_RED,
        [
            Ansi::EFFECT_FAINT,
            Ansi::EFFECT_ITALIC,
            Ansi::EFFECT_UNDERLINE,
            Ansi::EFFECT_SLOW_BLINK
        ]
    );
    echo PHP_EOL.PHP_EOL;
}

/**
 * TODO extract into separate functions and delete
 *
 * @param $fakeRes
 */
function showSampleBoxes999($fakeRes): void
{

    // Crate BoxDrawer instance
    $boxDrawer = new BoxDrawer();

//    global $fakeRes;

//    $boxDrawer->drawBoxesForLines('Hello world!');

// Render with no settings
    //$boxDrawer
    //    ->setHeaderLine('Basic sample')
    //    ->setStyle(BoxDrawer::STYLE_NO_BORDER)
    //    ->drawBoxesMulticol($fakeRes);

// Renders table from flat array
    //$boxDrawer
    //    ->reset()
    //    ->setStyle(BoxDrawer::STYLE_BORDERED)
    //    ->setUseAnsiColors(true)
    //    ->setIsFirstLineHeader(false)
    //    ->setHeaderLine('Renders table from flat array')
    //    ->drawBoxesMulticol($flatArrays);

// Renders box with multiline text
    //$boxDrawer
    //    ->reset()
    //    ->setStyle(BoxDrawer::STYLE_NO_BORDER)
    //    ->setUseAnsiColors(true)
    //    ->setIsFirstLineHeader(false)
    //    ->setRenderAsHtml(false)
    //    ->setUseAnsiBackground(true)
    //    ->setMinimumWidth(74)
    //    ->drawBoxesForLines($multilineAligned);

// Renders box with multiline text
    //$boxDrawer
    //    ->reset()
    //    ->setStyle(BoxDrawer::STYLE_BORDERED)
    //    ->setUseAnsiColors(true)
    //    ->setIsFirstLineHeader(true)
    //    ->setRenderHtml(false)
    //    ->drawBoxesForLines($multiLineText);

}
