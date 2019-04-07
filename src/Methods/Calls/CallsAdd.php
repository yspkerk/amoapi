<?php
/**
 * @category  AquaBox
 * @package   Binotel
 * @author    Sergei Yarmolyuk <sergei.yarmolyuk@gmail.com>
 * @copyright 2019 Fabrika-Klientov
 * @version   GIT: 19.4.1
 * @link      https://fabrika-klientov.ua
 */

namespace Ufee\Amo\Methods\IncomingLeads;

class IncomingLeadsAdd extends \Ufee\Amo\Base\Methods\Post
{
	protected 
		$url = '/api/v2/incoming_leads/sip';
	
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
