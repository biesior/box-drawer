<?php

namespace Vendor\Utility;

/**
 * Class BoxDrawerCharts
 *
 * Contains extracted meyhods for showing some tips and charts about BoxDrawing characters.
 *
 * @author (c) 2020 Marcus Biesioroff <biesior@gmail.com>
 * @author (c) 2020 Walter Francisco Núñez Cruz <icarosnet@gmail.com>
 */
class BoxDrawerCharts
{
    /**
     * TODO improve phpdoc
     * @param  string       $variant
     * @throws \Exception
     */
    public static function renderAnsiColorsAndEffectsChart($variant = 'all')
    {
        $box = new BoxDrawer();

        echo "\e[42m".'ANSI colors and effects chart'."\e[0m"
        .PHP_EOL.PHP_EOL
        .'Just to remind, you can use ANSI escape chcaracter'
        .PHP_EOL.'as '
        .Ansi::colorize('\\x1b', Ansi::FOREGROUND_GREEN)
        .' or '.Ansi::colorize('\\e', Ansi::FOREGROUND_GREEN, Ansi::EFFECT_NORMAL)
        .' or '.Ansi::colorize('\\033', Ansi::FOREGROUND_GREEN, Ansi::EFFECT_NORMAL)
        .' or '.Ansi::colorize('chr(27)', Ansi::FOREGROUND_GREEN, Ansi::EFFECT_NORMAL).' in PHP, the choice is yours'
        .PHP_EOL.PHP_EOL.'see more '.Ansi::colorize('https://notes.burke.libbey.me/ansi-escape-codes/', Ansi::EFFECT_UNDERLINE)
        .PHP_EOL.PHP_EOL
        .'Basic colors according to '
        .Ansi::colorize(0, 4, 'https://en.wikipedia.org/wiki/ANSI_escape_code#3/4_bit').' are:'
            .PHP_EOL;

        if (in_array($variant, ['basic', 'all'])) {
            $data   = [];
            $data[] = ['Name', 'foreground', 'background'];
            $header = 'Basic Foreground colors: 30 .. 37 / Background colors: 40 .. 47';

            $colors = [
                ['f' => 30, 'b' => 40, 'name' => 'Black'],
                ['f' => 31, 'b' => 41, 'name' => 'Red'],
                ['f' => 32, 'b' => 42, 'name' => 'Green'],
                ['f' => 33, 'b' => 43, 'name' => 'Yellow'],
                ['f' => 34, 'b' => 44, 'name' => 'Blue'],
                ['f' => 35, 'b' => 45, 'name' => 'Magenta'],
                ['f' => 36, 'b' => 46, 'name' => 'Cyan'],
                ['f' => 37, 'b' => 47, 'name' => 'White']
            ];
            foreach ($colors as $color) {
                $name   = BoxDrawer::fillToLeft($color['name'], 16);
                $fore   = $color['f'];
                $back   = $color['b'];
                $data[] = [
                    sprintf("\e[0;%sm%s\e[0m", $fore, $name),
                    "\e[{$fore}m\\e[{$fore}m\e[0m \e[0;37mor \e[0;{$fore}m\\e[0;{$fore}m\e[0m \e[0;37mor \e[1;{$fore}m\\e[1;{$fore}m\e[0m",
                    "\e[{$back}m \\e[{$back}m \e[0m \e[0;37mor \e[0;{$back}m \\e[0;{$back}m \e[0m \e[0;37mor \e[1;{$back}m \\e[1;{$back}m \e[0m".'  '

                ];
            }

            $box->reset()
                ->setStyle(BoxDrawer::STYLE_BORDERED)
                ->setUseAnsiColors(true)
                ->setUseAnsiBackground(false)
                ->setHeaderLine(PHP_EOL.$header.PHP_EOL)
                ->drawBoxesMulticol($data);
        }
        if (in_array($variant, ['bright', 'all'])) {
            $data   = [];
            $data[] = ['Name', 'foreground', 'background'];
            $header = 'Bright Foreground colors: 90 .. 97 / Background colors: 100 .. 107';

            $colors = [
                ['f' => 90, 'b' => 100, 'name' => 'Bright Black'],
                ['f' => 91, 'b' => 101, 'name' => 'Bright Red'],
                ['f' => 92, 'b' => 102, 'name' => 'Bright Green'],
                ['f' => 93, 'b' => 103, 'name' => 'Bright Yellow'],
                ['f' => 94, 'b' => 104, 'name' => 'Bright Blue'],
                ['f' => 95, 'b' => 105, 'name' => 'Bright Magenta'],
                ['f' => 96, 'b' => 106, 'name' => 'Bright Cyan'],
                ['f' => 97, 'b' => 107, 'name' => 'Bright White']
            ];
            foreach ($colors as $color) {
                $name   = BoxDrawer::fillToLeft($color['name'], 16);
                $fore   = $color['f'];
                $back   = $color['b'];
                $data[] = [
                    sprintf("\e[0;%sm%s\e[0m", $fore, $name),
                    "\e[{$fore}m\\e[{$fore}m\e[0m \e[0;37mor \e[0;{$fore}m\\e[0;{$fore}m\e[0m \e[0;37mor \e[1;{$fore}m\\e[1;{$fore}m\e[0m",
                    "\e[{$back}m \\e[{$back}m \e[0m \e[0;37mor \e[0;{$back}m \\e[0;{$back}m \e[0m \e[0;37mor \e[1;{$back}m \\e[1;{$back}m \e[0m".'  '
                ];
            }

            $box->reset()
                ->setStyle(BoxDrawer::STYLE_BORDERED)
                ->setUseAnsiColors(true)
                ->setUseAnsiBackground(false)
                ->setHeaderLine(PHP_EOL.$header.PHP_EOL)
                ->drawBoxesMulticol($data);

        }
        if (in_array($variant, ['effects', 'all'])) {
            $data   = [];
            $data[] = ['Code', 'Name', 'Entity', 'Sample', 'Combined with red color', 'or with green, etc...'];
            $header = 'Preview for some effects'.PHP_EOL.
            'Note, that effects may be different or don\'t work at all depending on the used terminal'.PHP_EOL.PHP_EOL.
            'For more details visit: '.Ansi::colorize(0, 4, 'https://en.wikipedia.org/wiki/ANSI_escape_code#SGR_parameters');

            $colors = [
                ['code' => 0, 'name' => 'Reset / Normal '],
                ['code' => 1, 'name' => 'Bold or increased intensity'],
                ['code' => 2, 'name' => 'Faint or decreased intensity'],
                ['code' => 3, 'name' => 'Italic'],
                ['code' => 4, 'name' => 'Underline'],
                ['code' => 5, 'name' => 'Slow Blink'],
                ['code' => 6, 'name' => 'Rapid Blink'],
                ['code' => 7, 'name' => 'Reverse video'],
                ['code' => 8, 'name' => 'Conceal'],
                ['code' => 9, 'name' => 'Crossed-out'],
                ['code' => 10, 'name' => 'Primary (default) font'],
                ['code' => 11, 'name' => 'Alternative font 11'],
                ['code' => 12, 'name' => 'Alternative font 12'],
                ['code' => 13, 'name' => 'Alternative font 13'],
                ['code' => 14, 'name' => 'Alternative font 14'],
                ['code' => 15, 'name' => 'Alternative font 15'],
                ['code' => 16, 'name' => 'Alternative font 16'],
                ['code' => 17, 'name' => 'Alternative font 17'],
                ['code' => 18, 'name' => 'Alternative font 18'],
                ['code' => 19, 'name' => 'Alternative font 19'],
                ['code' => 20, 'name' => 'Fraktur / Rarely supported '],
                ['code' => 21, 'name' => 'Doubly underline or Bold off'],
                ['code' => 22, 'name' => 'Normal color or intensity'],
                ['code' => 23, 'name' => 'Not italic, not Fraktur'],
                ['code' => 24, 'name' => 'EffUnderline off'],
                ['code' => 25, 'name' => 'Blink off'],
                ['code' => 26, 'name' => 'Proportional spacing'],
                ['code' => 27, 'name' => 'Reverse/invert off'],
                ['code' => 28, 'name' => 'Reveal'],
                ['code' => 29, 'name' => 'Not crossed out']
//            ['code' => , 'name' => 'Effect '],

            ];
            foreach ($colors as $color) {
                $code = $color['code'];
                $name = $color['name'];

                $data[] = [
                    $code,
                    $name,
                    "\\e[{$code}m",
                    "\e[{$code}mText with code \\e[{$code}m\e[0m",
                    "\e[{$code};31mText with code \\e[{$code};31m\e[0m",
                    "\e[{$code};32mText with code \\e[{$code};32m\e[0m"
                ];
            }

            $box->reset()
                ->setStyle(BoxDrawer::STYLE_BORDERED)
                ->setUseAnsiColors(true)
                ->setUseAnsiBackground(false)
                ->setHeaderLine(PHP_EOL.$header.PHP_EOL)
                ->drawBoxesMulticol($data);

        }
    }

    /**
     * Renders list of Box drawing characters for reference with codes of HTML entities.
     *
     * @internal for developers only to check what's what if they forgot... again ;p
     * @see https://en.wikipedia.org/wiki/Box-drawing_character#Unicode
     * @see https://en.wikipedia.org/wiki/ANSI_escape_code#3/4_bit
     *
     * @param bool $asHtml
     */
    public static function renderEntityChart($asHtml = false)
    {

        header("content-type: text/html; charset=UTF-8");
        $mains = [250, 251, 252, 253, 254, 255, 256, 257];
        $subs  = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F'];
        if ($asHtml) {
            echo '<table>';
            foreach ($mains as $main) {
                foreach ($subs as $sub) {
                    $code = $main.$sub;

                    echo "
            <tr>
                <td><pre>&amp;#x{$code};</pre> </td>
                <td>&#x{$code};</td>
            </tr>";
                }
            }
            echo '</table>';
        } else {
            $data = [['Char', 'Entity']];
            foreach ($mains as $main) {
                foreach ($subs as $sub) {
                    $code   = $main.$sub;
                    $data[] = [
                        json_decode('"\u'.$code.'"'),
                        "&#x{$code};"

                    ];

                }
            }
            $box = new BoxDrawer();
            $box
            ->setStyle(1)
                ->setIsFirstLineHeader(true)
                ->setUseAnsiColors(true)
                ->drawBoxesMulticol($data);
        }
    }
}
