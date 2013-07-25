<?php
/**
 * 
 * 
 * Date: 07.07.13
 * Time: 15:17
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Brujo
 */

namespace Brujo;
 
use Brujo\Helper\ViewParser;
use Brujo\Traits;

abstract class Helper
{
    use Traits\Dependency\Container, Traits\HasParameters;

    /**
     * @var HelperBroker
     */
    protected $broker;

    public function __construct(HelperBroker $broker)
    {
        $this->setBroker($broker);
    }

    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @return \Brujo\HelperBroker
     */
    public function getBroker()
    {
        return $this->broker;
    }

    /**
     * @param \Brujo\HelperBroker $broker
     * @return Helper
     */
    public function setBroker(HelperBroker $broker)
    {
        $this->broker = $broker;

        return $this;
    }

    protected function parseBlock($block)
    {
        if (isset($block[1])) {
            $command = trim($block[1]);
            $pattern = '/([a-z]+\.?[a-z]+):([a-z]+)(\s+.+)?/i';
            preg_match($pattern, $command, $data);

            if (isset($data[1], $data[2])) {
                $arguments = isset($data[3]) ? trim($data[3]) : '';
                $result    = $this->getBroker()->callHelper(
                    $data[1], $data[2], $arguments
                );
            } else {
                $result = "Invalid helper call: {$command}";
            }
        } else {
            $result = 'Invalid block';
        }

        return $result;
    }

    protected function getParser()
    {
        $parser = $this->getBroker()->getHelper('viewParser');

        if (!$parser instanceof ViewParser) {
            throw new \RuntimeException('Cannot extract view parser');
        }

        return $parser;
    }
}
