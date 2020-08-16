<?php

use Vendor\Utility\BoxDrawer;

require_once 'vendor/autoload.php';

require_once 'sample-data/sample-data.php';


// Crate BoxDrawer instance
$boxDrawer = new BoxDrawer();

$boxDrawer->drawBoxesForLines('Hello world!');

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

//$boxDrawer
//    ->reset()
//    ->setStyle(1)
//    ->setUseAnsiColors(true)
//    ->setIsFirstLineHeader(true)
//    ->drawBoxesMulticol($dbData);

//$boxDrawer
//    ->reset()
//    ->setStyle(BoxDrawer::STYLE_BORDERED)
//    ->setIsFirstLineHeader(true)
//    ->setRenderHtml(true)
//    ->drawBoxesForLines($flatData);

// Renders box with multiline text
//$boxDrawer
//    ->reset()
//    ->setStyle(BoxDrawer::STYLE_BORDERED)
//    ->setUseAnsiColors(true)
//    ->setIsFirstLineHeader(true)
//    ->setRenderHtml(false)
//    ->drawBoxesForLines($multiLineText);

//$boxDrawer
//    ->reset()
//    ->setStyle(BoxDrawer::STYLE_BORDERED)
//    ->setUseAnsiColors(true)
//    ->setIsFirstLineHeader(true)
//    ->setRenderHtml(false)
//    ->drawBoxesMulticol($dbData);

//$boxDrawer
//    ->setStyle(BoxDrawer::STYLE_NO_INLINES)
//    ->setUseAnsiColors(true)
//    ->setIsFirstLineHeader(true)
//    ->setRenderHtml(false)
//    ->drawBoxesMulticol($dbData);

//$boxDrawer
//    ->setStyle(BoxDrawer::STYLE_NO_BORDER)
//    ->setUseAnsiColors(true)
//    ->setIsFirstLineHeader(true)
//    ->setRenderHtml(false)
//    ->drawBoxesMulticol($dbData);

//$boxDrawer
//    ->reset()
//    ->setStyle(BoxDrawer::STYLE_BORDERED)
//    ->setIsFirstLineHeader(true)
//    ->setRenderAsHtml(false)
//    ->drawBoxesMulticol($walterData);

//$boxDrawer
//    ->setStyle(BoxDrawer::STYLE_NO_INLINES)
//    ->setIsFirstLineHeader(true)
//    ->setRenderHtml(false)
//    ->drawBoxesMulticol($dbData);

//$boxDrawer
//    ->setStyle(BoxDrawer::STYLE_NO_BORDER)
//    ->setUseAnsiColors(true)
//    ->setIsFirstLineHeader(true)
//    ->setRenderHtml(false)
//    ->drawBoxesMulticol($dbData);


//$boxDrawer->renderChart();


