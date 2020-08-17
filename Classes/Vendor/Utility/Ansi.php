<?php


namespace Vendor\Utility;


class Ansi
{

    const EFFECT_NORMAL = 0;
    const EFFECT_BOLD = 1;
    const EFFECT_FAINT = 2;
    const EFFECT_ITALIC = 3;
    const EFFECT_UNDERLINE = 4;
    const EFFECT_SLOW_BLINK = 5;
    const EFFECT_RAPID_BLINK = 6;
    const EFFECT_REVERSE_VIDEI = 7;
    const EFFECT_CONCEAL = 8;
    const EFFECT_CROSSED_OUT = 9;

    // Foreground colors set 1
    const FOREGROUND_BLACK = 30;
    const FOREGROUND_RED = 31;
    const FOREGROUND_GREEN = 32;
    const FOREGROUND_YELLOW = 33;
    const FOREGROUND_BLUE = 34;
    const FOREGROUND_MAGENTA = 35;
    const FOREGROUND_CYAN = 36;
    const FOREGROUND_WHITE = 37;

    // Background colors set 1
    const BACKGROUND_BLACK = 40;
    const BACKGROUND_RED = 41;
    const BACKGROUND_GREEN = 42;
    const BACKGROUND_YELLOW = 43;
    const BACKGROUND_BLUE = 44;
    const BACKGROUND_MAGENTA = 45;
    const BACKGROUND_CYAN = 46;
    const BACKGROUND_WHITE = 47;

    // Foreground colors set 2
    const FOREGROUND_BRIGHT_BLACK = 90;
    const FOREGROUND_BRIGHT_RED = 91;
    const FOREGROUND_BRIGHT_GREEN = 92;
    const FOREGROUND_BRIGHT_YELLOW = 93;
    const FOREGROUND_BRIGHT_BLUE = 94;
    const FOREGROUND_BRIGHT_MAGENTA = 95;
    const FOREGROUND_BRIGHT_CYAN = 96;
    const FOREGROUND_BRIGHT_WHITE = 97;

    // Background colors set 2
    const BACKGROUND_BRIGHT_BLACK = 100;
    const BACKGROUND_BRIGHT_RED = 101;
    const BACKGROUND_BRIGHT_GREEN = 102;
    const BACKGROUND_BRIGHT_YELLOW = 103;
    const BACKGROUND_BRIGHT_BLUE = 104;
    const BACKGROUND_BRIGHT_MAGENTA = 105;
    const BACKGROUND_BRIGHT_CYAN = 106;
    const BACKGROUND_BRIGHT_WHITE = 107;

    /**
     * TODO
     *
     *
     * @param string    $value
     * @param int       $code
     * @param int|array $optionalEffects Should be single `int` or array of `ints`, see below sample
     *
     *
     * Usage
     * <pre>
     *
     * // basic case
     * echo Ansi::colorize(
     *      'Value to colorize which should be green without additional effects',
     *      Ansi::BACKGROUND_GREEN
     * );
     *
     * // With single effect as an integer
     * echo Ansi::colorize(
     *      'Value to colorize which should be magenta and underlined',
     *      Ansi::FOREGROUND_MAGENTA,
     *      Ansi::EFFECT_UNDERLINE
     * );
     *
     * // for multiple effects use array of intehers as a $optionalEffects
     * echo Ansi::colorize(
     *      ' Value to colorize which should have red background, faint, italic, underlined and should slowly blink ',
     *      Ansi::BACKGROUND_RED,
     *      [
     *          Ansi::EFFECT_FAINT,
     *          Ansi::EFFECT_ITALIC,
     *          Ansi::EFFECT_UNDERLINE,
     *          Ansi::EFFECT_SLOW_BLINK,
     *      ]
     * );
     * </pre>
     *
     * @return string
     */
    public static function colorize(string $value, int $code, $optionalEffects = null)
    {
        if (!is_null($optionalEffects)) {
            if (is_array($optionalEffects)) {
                foreach ($optionalEffects as $effect) {
                    if (!is_integer($effect)) {
                        throw new \Exception(sprintf('All elements of array must be an integers, `%s` given.', gettype($effect)), 1597658704);
                    }
                    $optionalEffects = implode(';', $optionalEffects);
                    return sprintf("\e[%s;%dm%s\e[0m", $optionalEffects, $code, $value);
                }
            } else {
                return sprintf("\e[%d;%dm%s\e[0m", $optionalEffects, $code, $value);
            }
        }
        // else...
        return sprintf("\e[%dm%s\e[0m", $code, $value);

    }

    public static function test()
    {
        return "\e[1;3;4;5;31mfoooooooo\e[0m";
    }

    public static function reset($value = null)
    {
        return sprintf("\e[0m%s", $value);
    }
}