<?php
/**
 * 
 * 
 * Date: 12.07.13
 * Time: 17:24
 *
 * @author Maxim Khan-Magomedov <maxim.km@gmail.com>
 * @package Web
 */

namespace Web\Controllers;
 
use Brujo\Http\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->setParameter('Hello', 'Oh, Hi!');
    }

    public function getAboutAction()
    {
        $this->setParameter('Works', true);
    }

    public function demoPostDemoAction()
    {
    }
}
