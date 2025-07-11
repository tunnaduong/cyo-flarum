<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace VendorPatches202502\Symfony\Component\Console\Helper;

use VendorPatches202502\Symfony\Component\Console\Cursor;
use VendorPatches202502\Symfony\Component\Console\Exception\LogicException;
use VendorPatches202502\Symfony\Component\Console\Output\ConsoleOutputInterface;
use VendorPatches202502\Symfony\Component\Console\Output\ConsoleSectionOutput;
use VendorPatches202502\Symfony\Component\Console\Output\OutputInterface;
use VendorPatches202502\Symfony\Component\Console\Terminal;
/**
 * The ProgressBar provides helpers to display progress output.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Chris Jones <leeked@gmail.com>
 */
final class ProgressBar
{
    public const FORMAT_VERBOSE = 'verbose';
    public const FORMAT_VERY_VERBOSE = 'very_verbose';
    public const FORMAT_DEBUG = 'debug';
    public const FORMAT_NORMAL = 'normal';
    private const FORMAT_VERBOSE_NOMAX = 'verbose_nomax';
    private const FORMAT_VERY_VERBOSE_NOMAX = 'very_verbose_nomax';
    private const FORMAT_DEBUG_NOMAX = 'debug_nomax';
    private const FORMAT_NORMAL_NOMAX = 'normal_nomax';
    /**
     * @var int
     */
    private $barWidth = 28;
    /**
     * @var string
     */
    private $barChar;
    /**
     * @var string
     */
    private $emptyBarChar = '-';
    /**
     * @var string
     */
    private $progressChar = '>';
    /**
     * @var string|null
     */
    private $format;
    /**
     * @var string|null
     */
    private $internalFormat;
    /**
     * @var int|null
     */
    private $redrawFreq = 1;
    /**
     * @var int
     */
    private $writeCount = 0;
    /**
     * @var float
     */
    private $lastWriteTime = 0;
    /**
     * @var float
     */
    private $minSecondsBetweenRedraws = 0;
    /**
     * @var float
     */
    private $maxSecondsBetweenRedraws = 1;
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;
    /**
     * @var int
     */
    private $step = 0;
    /**
     * @var int
     */
    private $startingStep = 0;
    /**
     * @var int|null
     */
    private $max;
    /**
     * @var int
     */
    private $startTime;
    /**
     * @var int
     */
    private $stepWidth;
    /**
     * @var float
     */
    private $percent = 0.0;
    /**
     * @var mixed[]
     */
    private $messages = [];
    /**
     * @var bool
     */
    private $overwrite = \true;
    /**
     * @var \Symfony\Component\Console\Terminal
     */
    private $terminal;
    /**
     * @var string|null
     */
    private $previousMessage;
    /**
     * @var \Symfony\Component\Console\Cursor
     */
    private $cursor;
    /**
     * @var mixed[]
     */
    private $placeholders = [];
    /**
     * @var mixed[]
     */
    private static $formatters;
    /**
     * @var mixed[]
     */
    private static $formats;
    /**
     * @param int $max Maximum steps (0 if unknown)
     */
    public function __construct(OutputInterface $output, int $max = 0, float $minSecondsBetweenRedraws = 1 / 25)
    {
        if ($output instanceof ConsoleOutputInterface) {
            $output = $output->getErrorOutput();
        }
        $this->output = $output;
        $this->setMaxSteps($max);
        $this->terminal = new Terminal();
        if (0 < $minSecondsBetweenRedraws) {
            $this->redrawFreq = null;
            $this->minSecondsBetweenRedraws = $minSecondsBetweenRedraws;
        }
        if (!$this->output->isDecorated()) {
            // disable overwrite when output does not support ANSI codes.
            $this->overwrite = \false;
            // set a reasonable redraw frequency so output isn't flooded
            $this->redrawFreq = null;
        }
        $this->startTime = \time();
        $this->cursor = new Cursor($output);
    }
    /**
     * Sets a placeholder formatter for a given name, globally for all instances of ProgressBar.
     *
     * This method also allow you to override an existing placeholder.
     *
     * @param string                       $name     The placeholder name (including the delimiter char like %)
     * @param callable(ProgressBar):string $callable A PHP callable
     */
    public static function setPlaceholderFormatterDefinition(string $name, callable $callable) : void
    {
        self::$formatters = self::$formatters ?? self::initPlaceholderFormatters();
        self::$formatters[$name] = $callable;
    }
    /**
     * Gets the placeholder formatter for a given name.
     *
     * @param string $name The placeholder name (including the delimiter char like %)
     */
    public static function getPlaceholderFormatterDefinition(string $name) : ?callable
    {
        self::$formatters = self::$formatters ?? self::initPlaceholderFormatters();
        return self::$formatters[$name] ?? null;
    }
    /**
     * Sets a placeholder formatter for a given name, for this instance only.
     *
     * @param callable(ProgressBar):string $callable A PHP callable
     */
    public function setPlaceholderFormatter(string $name, callable $callable) : void
    {
        $this->placeholders[$name] = $callable;
    }
    /**
     * Gets the placeholder formatter for a given name.
     *
     * @param string $name The placeholder name (including the delimiter char like %)
     */
    public function getPlaceholderFormatter(string $name) : ?callable
    {
        return $this->placeholders[$name] ?? $this::getPlaceholderFormatterDefinition($name);
    }
    /**
     * Sets a format for a given name.
     *
     * This method also allow you to override an existing format.
     *
     * @param string $name   The format name
     * @param string $format A format string
     */
    public static function setFormatDefinition(string $name, string $format) : void
    {
        self::$formats = self::$formats ?? self::initFormats();
        self::$formats[$name] = $format;
    }
    /**
     * Gets the format for a given name.
     *
     * @param string $name The format name
     */
    public static function getFormatDefinition(string $name) : ?string
    {
        self::$formats = self::$formats ?? self::initFormats();
        return self::$formats[$name] ?? null;
    }
    /**
     * Associates a text with a named placeholder.
     *
     * The text is displayed when the progress bar is rendered but only
     * when the corresponding placeholder is part of the custom format line
     * (by wrapping the name with %).
     *
     * @param string $message The text to associate with the placeholder
     * @param string $name    The name of the placeholder
     */
    public function setMessage(string $message, string $name = 'message') : void
    {
        $this->messages[$name] = $message;
    }
    public function getMessage(string $name = 'message') : ?string
    {
        return $this->messages[$name] ?? null;
    }
    public function getStartTime() : int
    {
        return $this->startTime;
    }
    public function getMaxSteps() : int
    {
        return $this->max;
    }
    public function getProgress() : int
    {
        return $this->step;
    }
    private function getStepWidth() : int
    {
        return $this->stepWidth;
    }
    public function getProgressPercent() : float
    {
        return $this->percent;
    }
    public function getBarOffset() : float
    {
        return \floor($this->max ? $this->percent * $this->barWidth : (null === $this->redrawFreq ? (int) (\min(5, $this->barWidth / 15) * $this->writeCount) : $this->step) % $this->barWidth);
    }
    public function getEstimated() : float
    {
        if (0 === $this->step || $this->step === $this->startingStep) {
            return 0;
        }
        return \round((\time() - $this->startTime) / ($this->step - $this->startingStep) * $this->max);
    }
    public function getRemaining() : float
    {
        if (0 === $this->step || $this->step === $this->startingStep) {
            return 0;
        }
        return \round((\time() - $this->startTime) / ($this->step - $this->startingStep) * ($this->max - $this->step));
    }
    public function setBarWidth(int $size) : void
    {
        $this->barWidth = \max(1, $size);
    }
    public function getBarWidth() : int
    {
        return $this->barWidth;
    }
    public function setBarCharacter(string $char) : void
    {
        $this->barChar = $char;
    }
    public function getBarCharacter() : string
    {
        return $this->barChar ?? ($this->max ? '=' : $this->emptyBarChar);
    }
    public function setEmptyBarCharacter(string $char) : void
    {
        $this->emptyBarChar = $char;
    }
    public function getEmptyBarCharacter() : string
    {
        return $this->emptyBarChar;
    }
    public function setProgressCharacter(string $char) : void
    {
        $this->progressChar = $char;
    }
    public function getProgressCharacter() : string
    {
        return $this->progressChar;
    }
    public function setFormat(string $format) : void
    {
        $this->format = null;
        $this->internalFormat = $format;
    }
    /**
     * Sets the redraw frequency.
     *
     * @param int|null $freq The frequency in steps
     */
    public function setRedrawFrequency(?int $freq) : void
    {
        $this->redrawFreq = null !== $freq ? \max(1, $freq) : null;
    }
    public function minSecondsBetweenRedraws(float $seconds) : void
    {
        $this->minSecondsBetweenRedraws = $seconds;
    }
    public function maxSecondsBetweenRedraws(float $seconds) : void
    {
        $this->maxSecondsBetweenRedraws = $seconds;
    }
    /**
     * Returns an iterator that will automatically update the progress bar when iterated.
     *
     * @template TKey
     * @template TValue
     *
     * @param iterable<TKey, TValue> $iterable
     * @param int|null               $max      Number of steps to complete the bar (0 if indeterminate), if null it will be inferred from $iterable
     *
     * @return iterable<TKey, TValue>
     */
    public function iterate(iterable $iterable, ?int $max = null) : iterable
    {
        $this->start($max ?? (\is_array($iterable) || $iterable instanceof \Countable ? \count($iterable) : 0));
        foreach ($iterable as $key => $value) {
            (yield $key => $value);
            $this->advance();
        }
        $this->finish();
    }
    /**
     * Starts the progress output.
     *
     * @param int|null $max     Number of steps to complete the bar (0 if indeterminate), null to leave unchanged
     * @param int      $startAt The starting point of the bar (useful e.g. when resuming a previously started bar)
     */
    public function start(?int $max = null, int $startAt = 0) : void
    {
        $this->startTime = \time();
        $this->step = $startAt;
        $this->startingStep = $startAt;
        $startAt > 0 ? $this->setProgress($startAt) : ($this->percent = 0.0);
        if (null !== $max) {
            $this->setMaxSteps($max);
        }
        $this->display();
    }
    /**
     * Advances the progress output X steps.
     *
     * @param int $step Number of steps to advance
     */
    public function advance(int $step = 1) : void
    {
        $this->setProgress($this->step + $step);
    }
    /**
     * Sets whether to overwrite the progressbar, false for new line.
     */
    public function setOverwrite(bool $overwrite) : void
    {
        $this->overwrite = $overwrite;
    }
    public function setProgress(int $step) : void
    {
        if ($this->max && $step > $this->max) {
            $this->max = $step;
        } elseif ($step < 0) {
            $step = 0;
        }
        $redrawFreq = $this->redrawFreq ?? ($this->max ?: 10) / 10;
        $prevPeriod = (int) ($this->step / $redrawFreq);
        $currPeriod = (int) ($step / $redrawFreq);
        $this->step = $step;
        $this->percent = $this->max ? (float) $this->step / $this->max : 0;
        $timeInterval = \microtime(\true) - $this->lastWriteTime;
        // Draw regardless of other limits
        if ($this->max === $step) {
            $this->display();
            return;
        }
        // Throttling
        if ($timeInterval < $this->minSecondsBetweenRedraws) {
            return;
        }
        // Draw each step period, but not too late
        if ($prevPeriod !== $currPeriod || $timeInterval >= $this->maxSecondsBetweenRedraws) {
            $this->display();
        }
    }
    public function setMaxSteps(int $max) : void
    {
        $this->format = null;
        $this->max = \max(0, $max);
        $this->stepWidth = $this->max ? Helper::width((string) $this->max) : 4;
    }
    /**
     * Finishes the progress output.
     */
    public function finish() : void
    {
        if (!$this->max) {
            $this->max = $this->step;
        }
        if ($this->step === $this->max && !$this->overwrite) {
            // prevent double 100% output
            return;
        }
        $this->setProgress($this->max);
    }
    /**
     * Outputs the current progress string.
     */
    public function display() : void
    {
        if (OutputInterface::VERBOSITY_QUIET === $this->output->getVerbosity()) {
            return;
        }
        if (null === $this->format) {
            $this->setRealFormat($this->internalFormat ?: $this->determineBestFormat());
        }
        $this->overwrite($this->buildLine());
    }
    /**
     * Removes the progress bar from the current line.
     *
     * This is useful if you wish to write some output
     * while a progress bar is running.
     * Call display() to show the progress bar again.
     */
    public function clear() : void
    {
        if (!$this->overwrite) {
            return;
        }
        if (null === $this->format) {
            $this->setRealFormat($this->internalFormat ?: $this->determineBestFormat());
        }
        $this->overwrite('');
    }
    private function setRealFormat(string $format) : void
    {
        // try to use the _nomax variant if available
        if (!$this->max && null !== self::getFormatDefinition($format . '_nomax')) {
            $this->format = self::getFormatDefinition($format . '_nomax');
        } elseif (null !== self::getFormatDefinition($format)) {
            $this->format = self::getFormatDefinition($format);
        } else {
            $this->format = $format;
        }
    }
    /**
     * Overwrites a previous message to the output.
     */
    private function overwrite(string $message) : void
    {
        if ($this->previousMessage === $message) {
            return;
        }
        $originalMessage = $message;
        if ($this->overwrite) {
            if (null !== $this->previousMessage) {
                if ($this->output instanceof ConsoleSectionOutput) {
                    $messageLines = \explode("\n", $this->previousMessage);
                    $lineCount = \count($messageLines);
                    foreach ($messageLines as $messageLine) {
                        $messageLineLength = Helper::width(Helper::removeDecoration($this->output->getFormatter(), $messageLine));
                        if ($messageLineLength > $this->terminal->getWidth()) {
                            $lineCount += \floor($messageLineLength / $this->terminal->getWidth());
                        }
                    }
                    $this->output->clear($lineCount);
                } else {
                    $lineCount = \substr_count($this->previousMessage, "\n");
                    for ($i = 0; $i < $lineCount; ++$i) {
                        $this->cursor->moveToColumn(1);
                        $this->cursor->clearLine();
                        $this->cursor->moveUp();
                    }
                    $this->cursor->moveToColumn(1);
                    $this->cursor->clearLine();
                }
            }
        } elseif ($this->step > 0) {
            $message = \PHP_EOL . $message;
        }
        $this->previousMessage = $originalMessage;
        $this->lastWriteTime = \microtime(\true);
        $this->output->write($message);
        ++$this->writeCount;
    }
    private function determineBestFormat() : string
    {
        switch ($this->output->getVerbosity()) {
            case OutputInterface::VERBOSITY_VERBOSE:
                return $this->max ? self::FORMAT_VERBOSE : self::FORMAT_VERBOSE_NOMAX;
            case OutputInterface::VERBOSITY_VERY_VERBOSE:
                return $this->max ? self::FORMAT_VERY_VERBOSE : self::FORMAT_VERY_VERBOSE_NOMAX;
            case OutputInterface::VERBOSITY_DEBUG:
                return $this->max ? self::FORMAT_DEBUG : self::FORMAT_DEBUG_NOMAX;
            default:
                return $this->max ? self::FORMAT_NORMAL : self::FORMAT_NORMAL_NOMAX;
        }
    }
    private static function initPlaceholderFormatters() : array
    {
        return ['bar' => function (self $bar, OutputInterface $output) {
            $completeBars = $bar->getBarOffset();
            $display = \str_repeat($bar->getBarCharacter(), $completeBars);
            if ($completeBars < $bar->getBarWidth()) {
                $emptyBars = $bar->getBarWidth() - $completeBars - Helper::length(Helper::removeDecoration($output->getFormatter(), $bar->getProgressCharacter()));
                $display .= $bar->getProgressCharacter() . \str_repeat($bar->getEmptyBarCharacter(), $emptyBars);
            }
            return $display;
        }, 'elapsed' => function (self $bar) {
            return Helper::formatTime(\time() - $bar->getStartTime(), 2);
        }, 'remaining' => function (self $bar) {
            if (!$bar->getMaxSteps()) {
                throw new LogicException('Unable to display the remaining time if the maximum number of steps is not set.');
            }
            return Helper::formatTime($bar->getRemaining(), 2);
        }, 'estimated' => function (self $bar) {
            if (!$bar->getMaxSteps()) {
                throw new LogicException('Unable to display the estimated time if the maximum number of steps is not set.');
            }
            return Helper::formatTime($bar->getEstimated(), 2);
        }, 'memory' => function (self $bar) {
            return Helper::formatMemory(\memory_get_usage(\true));
        }, 'current' => function (self $bar) {
            return \str_pad($bar->getProgress(), $bar->getStepWidth(), ' ', \STR_PAD_LEFT);
        }, 'max' => function (self $bar) {
            return $bar->getMaxSteps();
        }, 'percent' => function (self $bar) {
            return \floor($bar->getProgressPercent() * 100);
        }];
    }
    private static function initFormats() : array
    {
        return [self::FORMAT_NORMAL => ' %current%/%max% [%bar%] %percent:3s%%', self::FORMAT_NORMAL_NOMAX => ' %current% [%bar%]', self::FORMAT_VERBOSE => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%', self::FORMAT_VERBOSE_NOMAX => ' %current% [%bar%] %elapsed:6s%', self::FORMAT_VERY_VERBOSE => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%', self::FORMAT_VERY_VERBOSE_NOMAX => ' %current% [%bar%] %elapsed:6s%', self::FORMAT_DEBUG => ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%', self::FORMAT_DEBUG_NOMAX => ' %current% [%bar%] %elapsed:6s% %memory:6s%'];
    }
    private function buildLine() : string
    {
        \assert(null !== $this->format);
        $regex = "{%([a-z\\-_]+)(?:\\:([^%]+))?%}i";
        $callback = function ($matches) {
            if ($formatter = $this->getPlaceholderFormatter($matches[1])) {
                $text = $formatter($this, $this->output);
            } elseif (isset($this->messages[$matches[1]])) {
                $text = $this->messages[$matches[1]];
            } else {
                return $matches[0];
            }
            if (isset($matches[2])) {
                $text = \sprintf('%' . $matches[2], $text);
            }
            return $text;
        };
        $line = \preg_replace_callback($regex, $callback, $this->format);
        // gets string length for each sub line with multiline format
        $linesLength = \array_map(function ($subLine) {
            return Helper::width(Helper::removeDecoration($this->output->getFormatter(), \rtrim($subLine, "\r")));
        }, \explode("\n", $line));
        $linesWidth = \max($linesLength);
        $terminalWidth = $this->terminal->getWidth();
        if ($linesWidth <= $terminalWidth) {
            return $line;
        }
        $this->setBarWidth($this->barWidth - $linesWidth + $terminalWidth);
        return \preg_replace_callback($regex, $callback, $this->format);
    }
}
