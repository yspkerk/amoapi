<?php
/**
 * @category  AquaBox
 * @package   Binotel
 * @author    Sergei Yarmolyuk <sergei.yarmolyuk@gmail.com>
 * @copyright 2019 Fabrika-Klientov
 * @version   GIT: 19.4.5
 * @link      https://fabrika-klientov.ua
 */

namespace Ufee\Amo\Services;

use Ufee\Amo\Base\Services\Traits;

class IncomingLeads extends \Ufee\Amo\Base\Services\MainEntity
{
	use Traits\SearchByName, Traits\SearchByPhone;
	
	protected static 
		$_require = [
			'add' => ['source_name'],
		];
	protected
        $entity_key = 'incomingLeads',
        $entity_model = '\Ufee\Amo\Models\IncomingLead',
        $entity_collection = '\Ufee\Amo\Collections\IncomingLeadCollection',
        $cache_time = false;

    /**
     * Get full
	 * @return Collection
     */
	public function incomingLeads()
	{
		return $this->list->recursiveCall();
	}
}
