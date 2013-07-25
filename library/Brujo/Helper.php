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
}
