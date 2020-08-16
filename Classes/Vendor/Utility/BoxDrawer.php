<?php

namespace Vendor\Utility;

/**
 * Class BoxDrawer
 *
 * @see https://en.wikipedia.org/wiki/Box-drawing_character#Unicode
 *
 * @author (c) 2020 Marcus Biesioroff <biesior@gmail.com>
 *         (c) 2020 Walter Francisco Núñez Cruz
 *
 */
class BoxDrawer
{

    /**
     * TODO Add text's soft wrap to keep max width in `drawBoxesForLines()` method (not applicable for `drawBoxesMulticol()`
     * TODO option for numeric columns to right align like in Excel
     * TODO find better names for `drawBoxesForLines()` and `drawBoxesMulticol()` methods probably something like `renderText()` and `renderArray()`
     */

    const STYLE_BORDERED = 1;
    const STYLE_NO_INLINES = 2;
    const STYLE_NO_BORDER = 3;
//    const STYLE_ROUNDED = 4; // time consuming to be done later if any...



    /**
     * Predefined style.
     *
     * @see BoxDrawer::setStyle()
     * @var int
     */
    protected $style = 1;

    /**
     * If `true` first line will be underlined (if in style) and highlighted by ANSI color
     *
     * @see BoxDrawer::setIsFirstLineHeader()
     * @var bool
     */
    protected $isFirstLineHeader = true;

    /**
     * If set to `true` box will be wrapped with `pre` tag to display in HTML
     *
     * @see BoxDrawer::setRenderAsHtml()
     * @var bool
     */
    protected $renderAsHtml = false;

    /**
     * If set to `true` ANSI text colors will be used in the console, need to  be `false` for HTML output
     *
     * @see BoxDrawer::setUseAnsiColors()
     * @var bool
     */
    protected $useAnsiColors = false;

    /**
     * - If set to `true` and  if ANSI colors are enabled whole box will use the background
     * - Need to  be `false` for HTML output
     *
     * @see BoxDrawer::setUseAnsiBackground()
     * @see BoxDrawer::$useAnsiColors
     *
     * @var bool
     */
    protected $useAnsiBackground = false;

    /**
     * - Minimum width of rendered box. If content is narrower than that empty spaces will be added.
     * - To be used with styles using some borders and/or ANSI background
     *
     * @see BoxDrawer::setMinimumWidth()
     *
     * @var int
     */
    protected $minimumWidth = 0;

    /**
     * A header line to display after rendered box
     *
     * @see BoxDrawer::setHeaderLine()
     * @var string|null
     */
    protected $headerLine = null;

    /**
     * A footer line to display after rendered box
     *
     * @see BoxDrawer::setFooterLine()
     * @var string|null
     */
    protected $footerLine = null;

    /**
     * If set to `true` numeric values in tables will be right aligned
     *
     * Not applicable for {@see BoxDrawer::drawBoxesForLines()}
     *
     * @var bool
     */
    protected $rightAlignNumericValues = true;


    // Special chars
    private $tl, $tw, $ts, $tr;
    private $bl, $bw, $bv, $bs, $br;
    private $cl, $cv, $cs, $cr;
    private $hl, $hv, $hs, $hr;
    private $eol, $gv;


    /**
     * @var array
     */
    private $columnSizes = [];

    /**
     * Sets different predefined styles.
     *
     * Current options:
     * - `BoxDrawer::STYLE_BORDERED`
     *
     *    (1) In structured arrays renders table with border and inner lines
     * - `BoxDrawer::STYLE_NO_INLINES`
     *
     *    (2) same as (1) but without inner lines
     * - `BoxDrawer::STYLE_NO_BORDER`
     *
     *    (3) data only, no borders or inner lines
     *
     * @param mixed $style
     *
     * @return BoxDrawer
     * @see BoxDrawer::$style
     */
    public function setStyle($style)
    {

        /**
         * Naming key: (need to be fixed!)
         *
         * - First letter
         *   t = top
         *   b = bottom
         *   g = general
         *   h = header
         *   c = common
         *
         * - Second leter
         *   l =  left
         *   w = wall (border)
         *   s = separator wall
         *   r = right
         *
         */
        $this->tl = html_entity_decode('╔', ENT_NOQUOTES, 'UTF-8'); // top left corner
        $this->tw = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // top horizontal wall
        $this->ts = html_entity_decode('╤', ENT_NOQUOTES, 'UTF-8'); // top column separator
        $this->tr = html_entity_decode('╗', ENT_NOQUOTES, 'UTF-8'); // top right corner

        $this->bl = html_entity_decode('╚', ENT_NOQUOTES, 'UTF-8'); // bottom left corner
        $this->bw = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall
        $this->bv = html_entity_decode('│', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall
        $this->bs = html_entity_decode('╧', ENT_NOQUOTES, 'UTF-8'); // top column separator
        $this->br = html_entity_decode('╝', ENT_NOQUOTES, 'UTF-8'); // bottom right corner

        $this->gv = html_entity_decode('║', ENT_NOQUOTES, 'UTF-8');  // general vertical wall

        $this->hl = html_entity_decode('╟', ENT_NOQUOTES, 'UTF-8');  // header left wall
        $this->hv = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8');  // header horizontal wall
        $this->hs = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8');  // header horizontal wall
        $this->hr = html_entity_decode('╢', ENT_NOQUOTES, 'UTF-8');  // header right wall

        $this->cl = html_entity_decode('╟', ENT_NOQUOTES, 'UTF-8');  // common left wall
        $this->cv = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8');  // common horizontal wall
        $this->cs = html_entity_decode('+', ENT_NOQUOTES, 'UTF-8');  // common column separator
        $this->cs = html_entity_decode('╪', ENT_NOQUOTES, 'UTF-8');  // common column separator
        $this->cr = html_entity_decode('╢', ENT_NOQUOTES, 'UTF-8');  // common right wall

        $this->eol = PHP_EOL;
        switch (intval($style)) {

            case 2:
                $this->ts = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // top column separator
                $this->bs = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // top column separator
                $this->cv = html_entity_decode('', ENT_NOQUOTES, 'UTF-8');  // common horizontal wall
                $this->cs = html_entity_decode('', ENT_NOQUOTES, 'UTF-8');  // common column separator
                $this->bv = html_entity_decode(' ', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall
                $this->cr = html_entity_decode('', ENT_NOQUOTES, 'UTF-8');  // common right wall
                $this->cl = '';
                $this->eol = '';
                break;
            case 3:
                $this->tl = ''; // top left corner
                $this->tw = ''; // top horizontal wall
                $this->ts = ''; // top column separator
                $this->tr = ''; // top right corner

                $this->bl = ''; // bottom left corner
                $this->bw = ''; // bottom horizontal wall
                $this->bv = ''; // bottom horizontal wall
                $this->bs = ''; // top column separator
                $this->br = ''; // bottom right corner

                $this->gv = '';  // general vertical wall

                $this->hl = '';  // header right wall

                $this->cl = '';  // common left wall
                $this->cv = '';  // common horizontal wall
                $this->cs = '';  // common column separator
                $this->cs = '';  // common column separator
                $this->cr = '';  // common right wall

                $this->eol = '';
                break;
            case 4:
                $this->tl = html_entity_decode('┌', ENT_NOQUOTES, 'UTF-8'); // top left corner
                $this->tw = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8'); // top horizontal wall
                $this->ts = html_entity_decode('┯', ENT_NOQUOTES, 'UTF-8'); // top column separator
                $this->tr = html_entity_decode('╮', ENT_NOQUOTES, 'UTF-8'); // top right corner

                $this->bl = html_entity_decode('╰', ENT_NOQUOTES, 'UTF-8'); // bottom left corner
                $this->bw = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall
                $this->bv = html_entity_decode('│', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall
                $this->bs = html_entity_decode('╧', ENT_NOQUOTES, 'UTF-8'); // top column separator
                $this->br = html_entity_decode('╯', ENT_NOQUOTES, 'UTF-8'); // bottom right corner

                $this->gv = html_entity_decode('┊', ENT_NOQUOTES, 'UTF-8');  // general vertical wall

                $this->hl = html_entity_decode('╟', ENT_NOQUOTES, 'UTF-8');  // header left wall
                $this->hv = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8');  // header horizontal wall
                $this->hs = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8');  // header horizontal wall
                $this->hr = html_entity_decode('╢', ENT_NOQUOTES, 'UTF-8');  // header right wall

                $this->cl = html_entity_decode('┝', ENT_NOQUOTES, 'UTF-8');  // common left wall
                $this->cv = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8');  // common horizontal wall
                $this->cs = html_entity_decode('+', ENT_NOQUOTES, 'UTF-8');  // common column separator
                $this->cs = html_entity_decode('╪', ENT_NOQUOTES, 'UTF-8');  // common column separator
                $this->cr = html_entity_decode('╢', ENT_NOQUOTES, 'UTF-8');  // common right wall
                break;
            case 1:
            default:

                break;
        }

        $this->style = $style;
        return $this;
    }

    /**
     * If `true` first line will be underlined (if in style) and highlighted by ANSI color
     * - default: `false`
     *
     * @param bool $isFirstLineHeader
     *
     * @return BoxDrawer
     * @see BoxDrawer::$isFirstLineHeader
     */
    public function setIsFirstLineHeader(bool $isFirstLineHeader): BoxDrawer
    {
        $this->isFirstLineHeader = $isFirstLineHeader;
        return $this;
    }

    /**
     * If set to `true` box will be wrapped with `pre` tag to display in HTML
     * - default: `false`
     *
     * @param bool $renderAsHtml
     *
     * @return BoxDrawer
     * @see BoxDrawer::$renderAsHtml
     *
     */
    public function setRenderAsHtml(bool $renderAsHtml): BoxDrawer
    {
        $this->renderAsHtml = $renderAsHtml;
        return $this;
    }

    /**
     * - Set if ANSI background should be added for whole box
     * - default: `false`
     *
     * @param bool $useAnsiBackground
     *
     * @return BoxDrawer
     */
    public function setUseAnsiBackground(bool $useAnsiBackground): BoxDrawer
    {
        $this->useAnsiBackground = $useAnsiBackground;
        return $this;
    }

    /**
     * If set to `true` ANSI text colors will be used in the console, need to  be `false` for HTML output <br>
     * - default: `false`
     *
     * @param bool $useAnsiColors
     *
     * @return BoxDrawer
     * @see BoxDrawer::$useAnsiColors
     */
    public function setUseAnsiColors(bool $useAnsiColors): BoxDrawer
    {
        $this->useAnsiColors = $useAnsiColors;
        return $this;
    }

    /**
     * Minimum width of the box, for free text. If content is narrower than that empty spaces will be added.
     * To be used with styles using some borders and/or ANSI background
     * - default: `0`
     *
     * @param int $minimumWidth
     *
     * @return BoxDrawer
     */
    public function setMinimumWidth(int $minimumWidth): BoxDrawer
    {
        $this->minimumWidth = $minimumWidth;
        return $this;
    }

    /**
     * Sets the header line to display before rendered box
     *
     * @param string|null $headerLine
     *
     * @return BoxDrawer
     * @see BoxDrawer::$headerLine
     */
    public function setHeaderLine($headerLine)
    {
        $this->headerLine = $headerLine;
        return $this;
    }

    /**
     * Sets the footer line to display after rendered box
     *
     * @param string|null $footerLine
     *
     * @return BoxDrawer
     * @see BoxDrawer::$footerLine
     */
    public function setFooterLine($footerLine)
    {
        $this->footerLine = $footerLine;
        return $this;
    }

    /**
     * If set to `true` numeric values in tables will be right aligned
     * - default: `true`
     *
     * @param bool $rightAlignNumericValues
     *
     * @return BoxDrawer
     */
    public function setRightAlignNumericValues(bool $rightAlignNumericValues): BoxDrawer
    {
        $this->rightAlignNumericValues = $rightAlignNumericValues;
        return $this;
    }

    // --- Public methods

    /**
     * Resets several things, so there's no need to initialize new object for rendering next boxes.
     * - column sizes
     * - minimumWidth
     * - ANSI color and background
     * - titleLine and footerLine
     *
     * @return $this
     */
    public function reset(): BoxDrawer
    {
        $this->columnSizes = [];
        $this->minimumWidth = 0;
        $this->useAnsiColors = false;
        $this->useAnsiBackground = false;
        $this->headerLine = null;
        return $this;
    }

    /**
     * Renders box with array of strings or multiline string
     *
     * @param mixed $lines
     * @param bool  $isFirstLineHeader
     * @param bool  $wrapInPre
     */
    public function drawBoxesForLines($lines, $isFirstLineHeader = false, $wrapInPre = false)
    {
        if (!is_array($lines)) {
            $lines = explode(PHP_EOL, $lines);
        }

        $longest = 0;
        foreach ($lines as $line) {
            $len = $this->stringLength($line);
            if ($len > $longest) {
                $longest = $len;
            }
        }

        if ($longest < $this->minimumWidth) {
            $longest = $this->minimumWidth;
        }
        $preStart = $wrapInPre ? "<pre style='font-family: monospace'>" . PHP_EOL : '';
        $preEnd = $wrapInPre ? PHP_EOL . "</pre>" : '';
        echo $preStart;
        if (!is_null($this->headerLine)) {
            echo $this->ansiTextHighlight($this->headerLine);
        }
        echo $this->ansiBackgroundHighlight($this->tl . str_repeat($this->tw, $longest + 2) . $this->tr . PHP_EOL);
        $i = 0;
        foreach ($lines as $line) {
            $addEmpty = '';

            $len = $this->stringLength($line);
            if ($len < $longest) {
                $addEmpty = str_repeat(' ', $longest - $len);
            }
            if ($isFirstLineHeader && $i == 0) {
                $line = $this->ansiTextHighlight($line);
            }

            echo $this->ansiBackgroundHighlight($this->gv . ' ' . $line . $addEmpty . ' ' . $this->gv);

            if ($this->isFirstLineHeader && $i == 0) {
                echo $this->ansiBackgroundHighlight(PHP_EOL . $this->cl . str_repeat($this->hs, $longest + 2) . $this->cr . PHP_EOL);
            } else {
                echo PHP_EOL;
            }
            $i++;
        }

        echo $this->ansiBackgroundHighlight($this->bl . str_repeat($this->tw, $longest + 2) . $this->br) . $preEnd . PHP_EOL;
    }


    /**
     * Renders box from associative array (ie. assoc fetch from DB) or flat array
     *
     * @param array $rows
     */
    public function drawBoxesMulticol(array $rows)
    {

        $allRowsIndex = 0;

        $startLine = '';
        $lines = '';
        $endLine = '';

        $outRows = [];

        foreach ($rows as $row) {

            $subIndex = 0;
            foreach ($row as $key => $value) {
                $this->columnSizes[$subIndex] = 0;
                if ($this->isAssoc($row)) {
                    if ($allRowsIndex == 0) {
                        $outRows[0][] = $key;
                    }
                }
                $subIndex++;
            }
            $outRows[] = array_values($row);


            $lines = '';
            foreach ($outRows as $outRow) {
                $i = 0;
                foreach ($outRow as $value) {
                    $valLen = $this->stringLength($value);
                    if ($valLen > $this->columnSizes[$i]) {
                        $this->columnSizes[$i] = $valLen;
                    }
                    $i++;
                }
            }
            $outRowI = 0;
            foreach ($outRows as $outRow) {

                $lines .= $this->gv;

                if ($outRowI == 0) {
                    $startLine = $this->tl;
                    $y = 0;
                    foreach ($this->columnSizes as $rowsIndex => $columnSize) {
                        $startLine .= str_repeat($this->tw, $columnSize + 2);
                        if ($y < count($this->columnSizes) - 1)
                            $startLine .= $this->ts;
                        $y++;
                    }
                } elseif ($outRowI == count($outRows) - 1) {
                    $endLine = $this->bl;
                    $y = 0;
                    foreach ($this->columnSizes as $rowsIndex => $columnSize) {
                        $endLine .= str_repeat($this->tw, $columnSize + 2);
                        if ($y < count($this->columnSizes) - 1)
                            $endLine .= $this->bs;
                        $y++;
                    }
                } else {
                    if ($this->style == 3) {
                        $lines .= '';
                    }
                }


                $i = 0;
                foreach ($outRow as $value) {
                    $columnSize = $this->columnSizes[$i];
                    $preValue = " $value ";
                    if ($this->stringLength($value) < $columnSize) {
                        $spacesToAdd = str_repeat(' ', ($columnSize - $this->stringLength($preValue)) + 2);
                        $preValue = ($this->rightAlignNumericValues && is_numeric($value))
                            ? $spacesToAdd . $preValue
                            : $preValue . $spacesToAdd;
                    }
                    if ($this->isFirstLineHeader && $outRowI == 0) {
                        $preValue = $this->ansiTextHighlight($preValue);
                    }
                    if ($i < count($this->columnSizes) - 1) {
                        $preValue = $preValue . $this->bv;
                    }

                    $lines .= $preValue;
                    $i++;
                }
                $lines .= $this->gv;

                $i = 0;
                $outRowI++;
                if ($outRowI < count($outRows)) {
                    $lines .= $this->eol . $this->cl;
                    foreach ($this->columnSizes as $columnSize) {
                        $lines .= str_repeat($this->cv, $columnSize + 2);
                        if ($i < count($this->columnSizes) - 1) {
                            $lines .= $this->cs;
                        }
                        $i++;
                    }
                    $lines .= $this->cr;
                }
                $lines .= PHP_EOL;

            }
            $startLine .= $this->tr . PHP_EOL;
            $endLine .= $this->br . PHP_EOL;


            $allRowsIndex++;
        }

        $preStart = $this->renderAsHtml ? "<pre style='font-family: monospace'>" . PHP_EOL : '';
        $preEnd = $this->renderAsHtml ? PHP_EOL . "</pre>" . PHP_EOL : '';


        echo $preStart;

        if (!is_null($this->headerLine)) {
            echo $this->ansiTextHighlight($this->headerLine) . PHP_EOL;
        }


        echo ($this->style == 3) ? null : $startLine;
        echo $lines;
        echo ($this->style == 3) ? null : $endLine;


        echo $preEnd;


    }

    /**
     * Renders list of Box drawing characters for reference with codes of HTML entities.
     *
     * @see https://en.wikipedia.org/wiki/Box-drawing_character#Unicode
     *
     * @internal for developers only to check what's what if they forgot... again ;p
     */
    public function renderChart()
    {
        $mains = [250, 251, 252, 253, 254, 255, 256, 257];
        $subs = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F'];
        echo '<table>';
        foreach ($mains as $main) {
            foreach ($subs as $sub) {
                $code = $main . $sub;
                echo "
            <tr>
                <td><pre>&amp;#x{$code};</pre> </td>
                <td>&#x{$code};</td>
            </tr>";
            }
        }
        echo '</table>';
    }


    /**
     * Try to determine if an array is associative
     *
     * @param array $arr Array to check
     *
     * @return bool
     */
    protected function isAssoc(array $arr): bool
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Returns string's length
     *
     * @param string $variable
     * @param bool   $removeAnsi
     *
     * @return bool|false|int
     */
    protected function stringLength($variable, $removeAnsi = true)
    {
        if ($removeAnsi) {
            $variable = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $variable);
        }
        return mb_strlen($variable);
    }

    /**
     * Wraps text in ANSI color if allowed
     * - green
     *
     * @param string $text
     *
     * @return string
     * @see https://bixense.com/clicolors/
     */
    protected function ansiTextHighlight($text)
    {
        return $this->useAnsiColors
            ? "\x1b[1;32m{$text}\x1b[0m"
            : $text;
    }

    /**
     * Wraps text in ANSI background color if allowed
     * - green
     *
     * @param string $text
     *
     * @return string
     * @see https://bixense.com/clicolors/
     */
    protected function ansiBackgroundHighlight($text)
    {
        return $this->useAnsiColors && $this->useAnsiBackground
            ? "\x1b[0;42m{$text}\x1b[0m"
            : $text;
    }



    /**
     * Wraps text in ANSI color if allowed
     * - red
     *
     * @param string $text
     *
     * @return string
     * @see https://bixense.com/clicolors/
     */
    protected function ansiTextWarning($text)
    {
        return $this->useAnsiColors
            ? "\x1b[1;31m{$text}\x1b[0m"
            : $text;
    }

    /**
     * Wraps text in ANSI color if allowed
     * - light gray
     *
     * @param string $text
     *
     * @return string
     * @see https://bixense.com/clicolors/
     */
    protected function ansiTextDimmed($text)
    {
        return $this->useAnsiColors
            ? "\x1b[0;30m{$text}\x1b[0m"
            : $text;
    }

    // -- Internal ie. for debugging

    /**
     * Wrapper for `var_dump`
     *
     * @param mixed $expression
     *
     * @see var_dump()
     * @internal For debug purposes only
     */
    private static function dump($expression)
    {
        echo '<pre>' . PHP_EOL;
        var_dump($expression);
        echo PHP_EOL . '</pre>';
    }

    /**
     * Wrapper for `print_r`
     *
     * @param mixed       $expression
     * @param string|null $title
     * @param bool|null   $return
     *
     * @internal For debug purposes only
     */
    private static function printer($expression, string $title = null, bool $return = null)
    {
        if (!is_null($title)) {
            echo "<b>$title:</b>" . PHP_EOL;
        }
        echo '<pre>' . PHP_EOL;
        print_r($expression, $return);
        echo PHP_EOL . '</pre>';
    }

}





