<?php
/**
 * @category  AquaBox
 * @package   Binotel
 * @author    Sergei Yarmolyuk <sergei.yarmolyuk@gmail.com>
 * @copyright 2019 Fabrika-Klientov
 * @version   GIT: 19.4.7
 * @link      https://fabrika-klientov.ua
 */

namespace Ufee\Amo\Methods\IncomingLeads;

class IncomingLeadsList extends \Ufee\Amo\Base\Methods\LimitedList
{
	protected 
		$url = '/api/v2/incoming_leads/sip';
	
}
