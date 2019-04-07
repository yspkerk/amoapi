<?php
/**
 * @category  AquaBox
 * @package   Binotel
 * @author    Sergei Yarmolyuk <sergei.yarmolyuk@gmail.com>
 * @copyright 2019 Fabrika-Klientov
 * @version   GIT: 19.4.7
 * @link      https://fabrika-klientov.ua
 */

namespace Ufee\Amo\Methods\Calls;

class CallsAdd extends \Ufee\Amo\Base\Methods\Post
{
	protected 
		$url = '/api/v2/calls';
	
    /**
     * Add entitys to CRM
	 * @param array $raws
	 * @param array $arg
	 * @return 
     */
    public function add($raws, $arg = [])
    {
		return $this->call(['add' => $raws], $arg);
	}
}
