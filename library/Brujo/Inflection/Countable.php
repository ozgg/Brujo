<?php
/**
 * Pluralization/singularization of countable words
 *
 * Used for pluralization and singularization of words.
 * Uses simple mechanisms. For enhancements, see wiki:
 * @link http://en.wikipedia.org/wiki/English_plurals
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo\Inflection
 */

namespace Brujo\Inflection;

/**
 * Pluralization and singularization of countable words
 */
class Countable
{
    /**
     * Exceptions from common rule
     *
     * @var array
     */
    protected $exceptions = [];

    public function __construct()
    {
        $this->exceptions = [
            'person' => 'people',
            'child'  => 'children',
            'mouse'  => 'mice',
            'foot'   => 'feet',
            'tooth'  => 'teeth',
        ];
    }

    public function pluralize($singular)
    {
        $singular = mb_strtolower($singular);

        if (isset($this->exceptions[$singular])) {
            $plural = $this->exceptions[$singular];
        } elseif (strlen($singular) > 2) {
            $last        = substr($singular, -1);
            $penultimate = substr($singular, -2, 1);
            $consonants  = [
                'q', 'w', 'r', 't', 'y', 'p', 's', 'd', 'f', 'g', 'h',
                'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm',
            ];
            if ($last === 's') {
                $plural = $singular . 'es';
            } elseif (($last === 'h') && in_array($penultimate, ['s', 'c'])) {
                $plural = $singular . 'es';
            } elseif (($last === 'o') && in_array($penultimate, $consonants)) {
                $plural = $singular . 'es';
            } elseif (($last === 'y') && in_array($penultimate, $consonants)) {
                $plural = substr($singular, 0, -1) . 'ies';
            } elseif (substr($singular, -3) === 'quy') {
                $plural = substr($singular, 0, -1) . 'ies';
            } else {
                $plural = $singular . 's';
            }
        } else {
            $plural = $singular . 's';
        }

        return $plural;
    }

    public function singularize($plural)
    {
        $plural  = mb_strtolower($plural);
        $reverse = array_flip($this->exceptions);
        if (isset($reverse[$plural])) {
            $singular = $reverse[$plural];
        } elseif (substr($plural, -1) !== 's') {
            $singular = $plural;
        } elseif (strlen($plural) > 2) {
            $singular     = substr($plural, 0, -1);
            $last         = substr($singular, -1);
            $penultimate  = substr($singular, -2, 1);
            $thirdFromEnd = substr($singular, -3, 1);
            $consonants   = [
                'q', 'w', 'r', 't', 'y', 'p', 's', 'd', 'f', 'g', 'h',
                'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm',
            ];
            if ($last === 'e') {
                if ($penultimate === 'o') {
                    if (in_array($thirdFromEnd, $consonants)) {
                        $singular = substr($singular, 0, -1);
                    }
                } elseif ($penultimate === 'h') {
                    if (in_array($thirdFromEnd, ['s', 'c'])) {
                        $singular = substr($singular, 0, -1);
                    }
                } elseif ($penultimate === 'i') {
                    if (in_array($thirdFromEnd, $consonants)) {
                        $singular = substr($singular, 0, -2) . 'y';
                    } elseif (substr($singular, -4) === 'quie') {
                        $singular = substr($singular, 0, -2) . 'y';
                    }
                } elseif ($penultimate === 's') {
                    if ($thirdFromEnd !== 'p') {
                        $singular = substr($singular, 0, -1);
                    }
                }
            }
        } else {
            $singular = substr($plural, 0, -1);
        }

        return $singular;
    }

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @param array $exceptions
     * @return Countable
     */
    public function setExceptions(array $exceptions)
    {
        $this->exceptions = $exceptions;

        return $this;
    }

    /**
     * Add exception to dictionary or set it
     *
     * @param string $singular
     * @param string $plural
     */
    public function setException($singular, $plural)
    {
        $this->exceptions[trim($singular)] = trim($plural);
    }

    /**
     * Remove exception from dictionary based on singular form of word
     *
     * @param string $singular
     */
    public function removeException($singular)
    {
        unset($this->exceptions[trim($singular)]);
    }
}
