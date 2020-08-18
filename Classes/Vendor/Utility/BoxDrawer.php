<?php

namespace Vendor\Utility;

/**
 * Class BoxDrawer
 *
 * @author (c) 2020 Marcus Biesioroff <biesior@gmail.com>
 * @author (c) 2020 Walter Francisco Núñez Cruz <icarosnet@gmail.com>
 *
 * @see https://en.wikipedia.org/wiki/Box-drawing_character#Unicode
 */
class BoxDrawer
{
    /**
     * TODO find better names for `drawBoxesForLines()` and `drawBoxesMulticol()` methods probably something like `renderText()` and `renderArray()`
     */

    const STYLE_BORDERED = 1;

    const STYLE_NO_BORDER = 3;

    const STYLE_NO_INLINES = 2;

    /**
     * If set to `true` text values will be centered
     * - default `false`
     *
     * Not applicable for {@see BoxDrawer::drawBoxesMulticol()}
     *
     * @var bool
     * @see BoxDrawer::setCenterText()
     */
    protected $centerText = false;

    //    const STYLE_ROUNDED = 4; // time consuming to be done later if any...

    /**
     * If `true` some debug will be displayed
     * - default `false`
     * - should be always `false` except of development
     *
     * @var bool
     * @see BoxDrawer::setDebugMode()
     */
    protected $debugMode = false;

    /**
     * Set to true to make sure that {@see BoxDrawer::finalDebug()} displays only once
     *
     * @var bool
     *
     * @internal
     */
    protected $finalDebugDisplayed = false;

    /**
     * A footer line to display after rendered box
     *
     * @var string|null
     * @see BoxDrawer::setFooterLine()
     */
    protected $footerLine = null;

    /**
     * A header line to display after rendered box
     *
     * @var string|null
     * @see BoxDrawer::setHeaderLine()
     */
    protected $headerLine = null;

    /**
     * Set to true to make sure that {@see BoxDrawer::initialDebug()} displays only once
     *
     * @var bool
     *
     * @internal
     */

    protected $initialDebugDisplayed = false;

    /**
     * If `true` first line will be underlined (if in style) and highlighted by ANSI color
     *
     * @var bool
     * @see BoxDrawer::setIsFirstLineHeader()
     */
    protected $isFirstLineHeader = true;

    /**
     * - Minimum width of rendered box. If content is narrower than that empty spaces will be added.
     * - To be used with styles using some borders and/or ANSI background
     *
     * @var int
     * @see BoxDrawer::setMinimumWidth()
     */
    protected $minimumWidth = 0;

    /**
     * If set to `true` box will be wrapped with `pre` tag to display in HTML
     *
     * @var bool
     * @see BoxDrawer::setRenderAsHtml()
     */
    protected $renderAsHtml = false;

    /**
     * If set to `true` numeric values in tables will be right aligned
     *
     * Not applicable for {@see BoxDrawer::drawBoxesForLines()}
     *
     * @var bool
     */
    protected $rightAlignNumericValues = true;

    /**
     * Predefined style.
     *
     * @var int
     * @see BoxDrawer::setStyle()
     */
    protected $style = 1;

    /**
     * - If set to `true` and  if ANSI colors are enabled whole box will use the background
     * - Need to  be `false` for HTML output
     *
     * @var bool
     * @see BoxDrawer::setUseAnsiBackground()
     * @see BoxDrawer::$useAnsiColors
     */
    protected $useAnsiBackground = false;

    /**
     * If set to `true` ANSI text colors will be used in the console, need to  be `false` for HTML output
     *
     * @var bool
     * @see BoxDrawer::setUseAnsiColors()
     */
    protected $useAnsiColors = false;

    private $bl, $bw, $bv, $bs, $br;

    private $cl, $cv, $cs, $cr;

    /**
     * @var array
     */
    private $columnSizes = [];

    private $eol, $gv;

    private $hl, $hv, $hs, $hr;

    // Special chars
    private $tl, $tw, $ts, $tr;

    /**
     * BoxDrawer constructor.
     */
    public function __construct()
    {
        $this->reset();
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

        self::initialDebug();

        if (!is_array($lines)) {
            $lines = explode(PHP_EOL, $lines);
        }

        $longest = max(array_map(function ($el) {
            return BoxDrawer::stringLength($el);
        }, $lines));

        if ($longest < $this->minimumWidth) {
            $longest = $this->minimumWidth;
        }
        $preStart = $wrapInPre ? "<pre style='font-family: monospace'>".PHP_EOL : '';
        $preEnd   = $wrapInPre ? PHP_EOL."</pre>" : '';
        echo $preStart;
        if (!null === $this->headerLine) {
            echo $this->ansiTextHighlight($this->headerLine);
        }

        echo $this->ansiBackgroundHighlight($this->tl.self::fillToLeft('', $longest + 2, $this->tw).$this->tr.PHP_EOL);
        $i = 0;
        foreach ($lines as $line) {
            $addEmpty = '';

            $len = BoxDrawer::stringLength($line);
            if ($len < $longest) {
                $addEmpty = str_repeat(' ', $longest - $len);
            }

            if ($this->centerText) {
                $line = trim($line);
            }

            if ($isFirstLineHeader && 0 == $i) {
                $line = $this->ansiTextHighlight($line);
            }

            if ($this->centerText) {
                $line = self::fillToCenter($line, $longest, ' ');
            } else {
                $line = self::fillToLeft($line, $longest, ' ');
            }
            echo $this->ansiBackgroundHighlight($this->gv.' '.$line.' '.$this->gv);

            if ($this->isFirstLineHeader && 0 == $i) {
                echo $this->ansiBackgroundHighlight(PHP_EOL.$this->cl.str_repeat($this->hs, $longest + 2).$this->cr.PHP_EOL);
            } else {
                echo PHP_EOL;
            }
            ++$i;
        }

        echo $this->ansiBackgroundHighlight($this->bl.str_repeat($this->tw, $longest + 2).$this->br).$preEnd.PHP_EOL;
        return $this;
    }

    /**
     * Renders box from associative array (ie. assoc fetch from DB) or flat array
     *
     * @param array $rows
     */
    public function drawBoxesMulticol(array $rows)
    {

        self::initialDebug();

        $allRowsIndex = 0;

        $startLine = '';
        $lines     = '';
        $endLine   = '';

        $outRows = [];

        foreach ($rows as $row) {

            $subIndex = 0;
            foreach ($row as $key => $value) {
                $this->columnSizes[$subIndex] = 0;
                if ($this->isAssoc($row)) {
                    if (0 == $allRowsIndex) {
                        $outRows[0][] = $key;
                    }
                }
                ++$subIndex;
            }
            $outRows[] = array_values($row);

            $lines = '';
            foreach ($outRows as $outRow) {
                $i = 0;
                foreach ($outRow as $value) {
                    $valLen = self::stringLength($value);
                    if ($valLen > $this->columnSizes[$i]) {
                        $this->columnSizes[$i] = $valLen;
                    }
                    ++$i;
                }
            }
            $outRowI = 0;
            foreach ($outRows as $outRow) {

                $lines .= $this->gv;

                if (0 == $outRowI) {
                    $startLine = $this->tl;
                    $y         = 0;
                    foreach ($this->columnSizes as $rowsIndex => $columnSize) {

                        $startLine .= self::fillToLeft('', $columnSize + 2, $this->tw);
                        if ($y < count($this->columnSizes) - 1) {
                            $startLine .= $this->ts;
                        }

                        ++$y;
                    }
                } elseif (count($outRows) - 1 == $outRowI) {
                    $endLine = $this->bl;
                    $y       = 0;
                    foreach ($this->columnSizes as $rowsIndex => $columnSize) {
                        $endLine .= self::fillToLeft('', $columnSize + 2, $this->tw);
                        if ($y < count($this->columnSizes) - 1) {
                            $endLine .= $this->bs;
                        }

                        ++$y;
                    }
                } else {
                    if (3 == $this->style) {
                        $lines .= '';
                    }
                }

                $i = 0;
                foreach ($outRow as $value) {
                    $columnSize = $this->columnSizes[$i];
                    $preValue   = " $value ";
                    if (self::stringLength($value) < $columnSize) {
                        $preValue = ($this->rightAlignNumericValues && is_numeric($value))
                            ? self::fillToRight($preValue, $columnSize + 2)
                            : self::fillToLeft($preValue, $columnSize + 2);
                    }
                    if ($this->isFirstLineHeader && 0 == $outRowI) {
                        $preValue = $this->ansiTextHighlight($preValue);
                    }
                    if ($i < count($this->columnSizes) - 1) {
                        $preValue = $preValue.$this->bv;
                    }

                    $lines .= $preValue;
                    ++$i;
                }
                $lines .= $this->gv;

                $i = 0;
                ++$outRowI;
                if ($outRowI < count($outRows)) {
                    $lines .= $this->eol.$this->cl;
                    foreach ($this->columnSizes as $columnSize) {
                        $lines .= self::fillToLeft('', $columnSize + 2, $this->cv);
                        if ($i < count($this->columnSizes) - 1) {
                            $lines .= $this->cs;
                        }
                        ++$i;
                    }
                    $lines .= $this->cr;
                }
                $lines .= PHP_EOL;

            }
            $startLine .= $this->tr.PHP_EOL;
            $endLine .= $this->br.PHP_EOL;

            ++$allRowsIndex;
        }

        $preStart = $this->renderAsHtml ? "<pre style='font-family: monospace'>".PHP_EOL : '';
        $preEnd   = $this->renderAsHtml ? PHP_EOL."</pre>".PHP_EOL : '';

        echo $preStart;

        if (!null === $this->headerLine) {
            echo $this->ansiTextHighlight($this->headerLine).PHP_EOL;
        }

        echo (3 == $this->style) ? null : $startLine;
        echo $lines;
        echo (3 == $this->style) ? null : $endLine;

        echo $preEnd;

        self::finalDebug();

        return $this;
    }

    public static function fillToCenter($value, $minLen, $withChar = ' '): string
    {
        $value = str_pad($value, $minLen, ' ', STR_PAD_BOTH);
        return $value;

    }

    /**
     *  TODO improve phpdoc
     *
     * @param  string   $value
     * @param  integer  $minLen
     * @param  string   $withChar
     * @return string
     */
    public static function fillToLeft($value, $minLen, $withChar = ' '): string
    {
        $len = self::stringLength($value);
        if ($len < $minLen) {
            $diff = $minLen - $len;
            return ($value.str_repeat($withChar, $diff));
        } else {
            return $value;
        }
    }

    /**
     * TODO improve phpdoc
     *
     * @param  string   $value
     * @param  integer  $minLen
     * @param  string   $withChar
     * @return string
     */
    public static function fillToRight($value, int $minLen, string $withChar = ' '): string
    {
        $len = self::stringLength($value);
        if ($len < $minLen) {
            $diff = $minLen - $len;
            return (str_repeat($withChar, $diff).$value);
        } else {
            return $value;
        }
    }

    /**
     * Is called in the class constructor.
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
        $this->debugMode             = false;
        $this->initialDebugDisplayed = false;
        $this->finalDebugDisplayed   = false;
        $this->style                 = self::STYLE_BORDERED;
        $this->columnSizes           = [];
        $this->minimumWidth          = 0;
        $this->useAnsiColors         = false;
        $this->useAnsiBackground     = false;
        $this->headerLine            = null;
        $this->centerText            = false;
        return $this;
    }

    /**
     * If set to `true` text values will be centered
     * - default `false`
     *
     * Not applicable for {@see BoxDrawer::drawBoxesMulticol()}
     *
     * @var    bool
     * @see BoxDrawer::$centerText
     *
     * @return BoxDrawer
     */
    public function setCenterText(bool $centerText): BoxDrawer
    {
        $this->centerText = $centerText;
        return $this;
    }

    /**
     * If `true` some debug will be displayed
     * - default `false`
     * - should be always `false` except of development
     *
     * @var bool
     * @see BoxDrawer::$debugMode
     */
    public function setDebugMode(bool $debugMode): BoxDrawer
    {
        $this->debugMode = $debugMode;
        return $this;
    }

    /**
     * Sets the footer line to display after rendered box
     *
     * @see BoxDrawer::$footerLine
     *
     * @param  string|null $footerLine
     * @return BoxDrawer
     */
    public function setFooterLine($footerLine)
    {
        $this->footerLine = $footerLine;
        return $this;
    }

    /**
     * Sets the header line to display before rendered box
     *
     * @see BoxDrawer::$headerLine
     *
     * @param  string|null $headerLine
     * @return BoxDrawer
     */
    public function setHeaderLine($headerLine)
    {
        $this->headerLine = $headerLine;
        return $this;
    }

    /**
     * If `true` first line will be underlined (if in style) and highlighted by ANSI color
     * - default: `false`
     *
     * @see BoxDrawer::$isFirstLineHeader
     *
     * @param  bool        $isFirstLineHeader
     * @return BoxDrawer
     */
    public function setIsFirstLineHeader(bool $isFirstLineHeader): BoxDrawer
    {
        $this->isFirstLineHeader = $isFirstLineHeader;
        return $this;
    }

    /**
     * Minimum width of the box, for free text. If content is narrower than that empty spaces will be added.
     * To be used with styles using some borders and/or ANSI background
     * - default: `0`
     *
     * @param  int         $minimumWidth
     * @return BoxDrawer
     */
    public function setMinimumWidth(int $minimumWidth): BoxDrawer
    {
        $this->minimumWidth = $minimumWidth;
        return $this;
    }

    /**
     * If set to `true` box will be wrapped with `pre` tag to display in HTML
     * - default: `false`
     *
     * @see BoxDrawer::$renderAsHtml
     *
     * @param  bool        $renderAsHtml
     * @return BoxDrawer
     */
    public function setRenderAsHtml(bool $renderAsHtml): BoxDrawer
    {
        $this->renderAsHtml = $renderAsHtml;
        return $this;
    }

    /**
     * If set to `true` numeric values in tables will be right aligned
     * - default: `true`
     *
     * @param  bool        $rightAlignNumericValues
     * @return BoxDrawer
     */
    public function setRightAlignNumericValues(bool $rightAlignNumericValues): BoxDrawer
    {
        $this->rightAlignNumericValues = $rightAlignNumericValues;
        return $this;
    }

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
     * @see BoxDrawer::$style
     *
     * @param  mixed        $style
     * @return BoxDrawer
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
        $this->tl = html_entity_decode('╔', ENT_NOQUOTES, 'UTF-8'); // ***************************
        $this->tw = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // *** Setters and getters ***
        $this->ts = html_entity_decode('╤', ENT_NOQUOTES, 'UTF-8'); // ***************************
        $this->tr = html_entity_decode('╗', ENT_NOQUOTES, 'UTF-8'); // top left corner

        $this->bl = html_entity_decode('╚', ENT_NOQUOTES, 'UTF-8'); // top horizontal wall
        $this->bw = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // top column separator
        $this->bv = html_entity_decode('│', ENT_NOQUOTES, 'UTF-8'); // top right corner
        $this->bs = html_entity_decode('╧', ENT_NOQUOTES, 'UTF-8'); // bottom left corner
        $this->br = html_entity_decode('╝', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall

        $this->gv = html_entity_decode('║', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall

        $this->hl = html_entity_decode('╟', ENT_NOQUOTES, 'UTF-8'); // top column separator
        $this->hv = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8'); // bottom right corner
        $this->hs = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8'); // general vertical wall
        $this->hr = html_entity_decode('╢', ENT_NOQUOTES, 'UTF-8'); // header left wall

        $this->cl = html_entity_decode('╟', ENT_NOQUOTES, 'UTF-8'); // header horizontal wall
        $this->cv = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8'); // header horizontal wall
        $this->cs = html_entity_decode('+', ENT_NOQUOTES, 'UTF-8');   // header right wall
        $this->cs = html_entity_decode('╪', ENT_NOQUOTES, 'UTF-8'); // common left wall
        $this->cr = html_entity_decode('╢', ENT_NOQUOTES, 'UTF-8'); // common horizontal wall

        $this->eol = PHP_EOL;
        switch (intval($style)) {

            case 2:
                $this->ts  = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // common column separator
                $this->bs  = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // common column separator
                $this->cv  = html_entity_decode('', ENT_NOQUOTES, 'UTF-8');    // common right wall
                $this->cs  = html_entity_decode('', ENT_NOQUOTES, 'UTF-8');    // top column separator
                $this->bv  = html_entity_decode(' ', ENT_NOQUOTES, 'UTF-8');   // top column separator
                $this->cr  = html_entity_decode('', ENT_NOQUOTES, 'UTF-8');    // common horizontal wall
                $this->cl  = '';
                $this->eol = '';
                break;
            case 3:
                $this->tl = ''; // common column separator
                $this->tw = ''; // bottom horizontal wall
                $this->ts = ''; // common right wall
                $this->tr = ''; // top left corner

                $this->bl = ''; // top horizontal wall
                $this->bw = ''; // top column separator
                $this->bv = ''; // top right corner
                $this->bs = ''; // bottom left corner
                $this->br = ''; // bottom horizontal wall

                $this->gv = ''; // bottom horizontal wall

                $this->hl = ''; // top column separator

                $this->cl = ''; // bottom right corner
                $this->cv = ''; // general vertical wall
                $this->cs = ''; // header right wall
                $this->cs = ''; // common left wall
                $this->cr = ''; // common horizontal wall

                $this->eol = '';
                break;
            case 4:
                $this->tl = html_entity_decode('┌', ENT_NOQUOTES, 'UTF-8'); // common column separator
                $this->tw = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8'); // common column separator
                $this->ts = html_entity_decode('┯', ENT_NOQUOTES, 'UTF-8'); // common right wall
                $this->tr = html_entity_decode('╮', ENT_NOQUOTES, 'UTF-8'); // top left corner

                $this->bl = html_entity_decode('╰', ENT_NOQUOTES, 'UTF-8'); // top horizontal wall
                $this->bw = html_entity_decode('═', ENT_NOQUOTES, 'UTF-8'); // top column separator
                $this->bv = html_entity_decode('│', ENT_NOQUOTES, 'UTF-8'); // top right corner
                $this->bs = html_entity_decode('╧', ENT_NOQUOTES, 'UTF-8'); // bottom left corner
                $this->br = html_entity_decode('╯', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall

                $this->gv = html_entity_decode('┊', ENT_NOQUOTES, 'UTF-8'); // bottom horizontal wall

                $this->hl = html_entity_decode('╟', ENT_NOQUOTES, 'UTF-8'); // top column separator
                $this->hv = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8'); // bottom right corner
                $this->hs = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8'); // general vertical wall
                $this->hr = html_entity_decode('╢', ENT_NOQUOTES, 'UTF-8'); // header left wall

                $this->cl = html_entity_decode('┝', ENT_NOQUOTES, 'UTF-8'); // header horizontal wall
                $this->cv = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8'); // header horizontal wall
                $this->cs = html_entity_decode('+', ENT_NOQUOTES, 'UTF-8');   // header right wall
                $this->cs = html_entity_decode('╪', ENT_NOQUOTES, 'UTF-8'); // common left wall
                $this->cr = html_entity_decode('╢', ENT_NOQUOTES, 'UTF-8'); // common horizontal wall
                break;
            case 1:
            default:

                break;
        }

        $this->style = $style;
        return $this;
    }

    /**
     * - Set if ANSI background should be added for whole box
     * - default: `false`
     *
     * @param  bool        $useAnsiBackground
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
     * @see BoxDrawer::$useAnsiColors
     *
     * @param  bool        $useAnsiColors
     * @return BoxDrawer
     */
    public function setUseAnsiColors(bool $useAnsiColors): BoxDrawer
    {
        $this->useAnsiColors = $useAnsiColors;
        return $this;
    }

    /**
     * Returns string's length
     *
     * @param  string           $variable
     * @param  bool             $removeAnsi
     * @return bool|false|int
     */
    public static function stringLength($variable, $removeAnsi = true)
    {
        if ($removeAnsi) {
            $variable = preg_replace('#\\e[[][^A-Za-z]*[A-Za-z]#', '', $variable);
        }
        return mb_strlen($variable);
    }

    /**
     * Wraps text in ANSI background color if allowed
     * - green
     *
     * @see https://bixense.com/clicolors/
     *
     * @param  string   $text
     * @return string
     */
    protected function ansiBackgroundHighlight($text)
    {
        return $this->ansiColor($text, Ansi::BACKGROUND_GREEN, Ansi::EFFECT_NORMAL);
    }

    protected function ansiColor($value, int $code, int $effect, $force = false)
    {
        return ($force || $this->useAnsiColors)
            ? Ansi::colorize($value, $code, $effect)
            : $value;
    }

    protected function ansiReset($value, $force = false)
    {
        return ($force || $this->useAnsiColors) ? Ansi::reset($value) : $value;
    }

    /**
     * Wraps text in ANSI color if allowed
     * - light `0;30` gray
     *
     * @see https://bixense.com/clicolors/
     *
     * @param  string   $text
     * @return string
     */
    protected function ansiTextDimmed($text)
    {
        return $this->ansiColor($text, Ansi::FOREGROUND_WHITE, Ansi::EFFECT_FAINT);
    }

    /**
     * Wraps text in ANSI color if allowed
     * - green
     *
     * @see https://bixense.com/clicolors/
     *
     * @param  string   $text
     * @return string
     */
    protected function ansiTextHighlight($text)
    {
        return $this->ansiColor($text, Ansi::FOREGROUND_GREEN, ANSI::EFFECT_BOLD);
    }

    /**
     * Wraps text in ANSI color if allowed
     * - blue
     *
     * @see https://bixense.com/clicolors/
     *
     * @param  string   $text
     * @return string
     */
    protected function ansiTextValue($text)
    {
        return $this->ansiColor($text, Ansi::FOREGROUND_BLUE, Ansi::EFFECT_NORMAL);
    }

    /**
     * Wraps text in ANSI color if allowed
     * - red
     *
     * @see https://bixense.com/clicolors/
     *
     * @param  string   $text
     * @return string
     */
    protected function ansiTextWarning($text)
    {
        return $this->ansiColor($text, Ansi::FOREGROUND_RED, Ansi::EFFECT_BOLD);
    }

    /**
     * Try to determine if an array is associative
     *
     * @param  array  $arr Array to check
     * @return bool
     */
    protected function isAssoc(array $arr): bool
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    // common column separator
    // common column separator
    // common right wall

    /**
     * Self description for _settersAndGetters
     *
     * @return string
     */
    private function _gettersAndSetters(): string
    {
        return self::describeMethodItself('_gettersAndSetters');
    }

    /**
     * Self description for _helperMethods
     *
     * @return string
     */
    private function _helperMethods(): string
    {
        return self::describeMethodItself('_helperMethods');
    }

    /**
     * Self description for _publicMethods
     *
     * @return string
     */
    final private function _publicMethods(): string
    {
        return self::describeMethodItself('_publicMethods');
    }

    /**
     * TODO improve phpdoc
     *
     * $value
     * @param  bool
     * @return string
     */
    private function boolAsString(bool $value): string
    {
        return ($value)
            ? $this->ansiColor('true', 32, 0)
            : $this->ansiColor('false', 31, 0);
    }

    /**
     * TODO improve phpdoc
     *
     * @param  $methodName
     * @throws \ReflectionException
     * @return string
     */
    private function describeMethodItself($methodName)
    {
        $r    = new \ReflectionMethod(BoxDrawer::class, $methodName);
        $file = $r->getFileName();
        $line = $r->getStartLine();
        return sprintf('%s() starts at %s:%d', $methodName, $file, $line);
    }

    // -- Internal ie. for debugging

    /**
     * Wrapper for `var_dump`
     *
     * @internal For debug purposes only
     * @see var_dump()
     *
     * @param mixed $expression
     */
    private static function dump($expression)
    {
        echo '<pre>'.PHP_EOL;
        var_dump($expression);
        echo PHP_EOL.'</pre>';
    }

    /**
     * Displays inline initial debug just for clarity
     */
    private function finalDebug()
    {
        if ($this->debugMode && !$this->finalDebugDisplayed) {
            $this->initialDebugDisplayed = true;

            $box = new BoxDrawer();
            $box
            ->setStyle(BoxDrawer::STYLE_BORDERED)
                ->setUseAnsiColors(true)
                ->setHeaderLine('Final debug:')
                ->setRightAlignNumericValues(false)
                ->drawBoxesMulticol([
                    ['Property', 'Value'],
                    [$this->ansiTextDimmed('$this->style'), $this->ansiTextValue($this->style)],
                    [$this->ansiTextDimmed('$this->debugMode'), $this->boolAsString($this->debugMode)],
                    [$this->ansiTextDimmed('$this->isFirstLineHeader'), $this->boolAsString($this->isFirstLineHeader)],
                    [$this->ansiTextDimmed('$this->renderAsHtml'), $this->boolAsString($this->renderAsHtml)],
                    [$this->ansiTextDimmed('$this->useAnsiColors'), $this->boolAsString($this->useAnsiColors)],
                    [$this->ansiTextDimmed('$this->useAnsiBackground'), $this->boolAsString($this->useAnsiBackground)],
                    [$this->ansiTextDimmed('$this->minimumWidth'), $this->ansiTextValue($this->minimumWidth)],
                    [$this->ansiTextDimmed('$this->headerLine'), $this->boolAsString($this->headerLine)]
                ]);
        }
    }

    /**
     * Displays inline initial debug just for clarity
     */
    private function initialDebug()
    {
        if ($this->debugMode && !$this->initialDebugDisplayed) {
            $this->initialDebugDisplayed = true;

            $box = new BoxDrawer();
            $box
            ->setStyle(BoxDrawer::STYLE_BORDERED)
                ->setUseAnsiColors(true)
                ->setHeaderLine('Initial debug:')
                ->drawBoxesMulticol([
                    ['row', 'debug'],
                    [1, $this->ansiTextValue($this->describeMethodItself('__construct'))],
                    [2, $this->ansiTextValue($this->_publicMethods())],
                    [3, $this->ansiTextValue($this->_gettersAndSetters())],
                    [4, $this->ansiTextValue($this->_helperMethods())]
                ]);
        }
    }

    /**
     * Wrapper for `print_r`
     *
     * @internal For debug purposes only
     * @param mixed       $expression
     * @param string|null $title
     * @param bool|null   $return
     */
    private static function printer($expression, string $title = null, bool $return = null)
    {
        if (!null === $title) {
            echo "<b>$title:</b>".PHP_EOL;
        }
        echo '<pre>'.PHP_EOL;
        print_r($expression, $return);
        echo PHP_EOL.'</pre>';
    }
}
