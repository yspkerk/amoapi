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

class Leads extends \Ufee\Amo\Base\Services\MainEntity
{
	use Traits\SearchByName;
	
	protected static 
		$_require = [
			'add' => ['name'],
			'update' => ['id', 'updated_at']
		];
	protected
        $entity_key = 'incoming_leads',
        $entity_model = '\FK\Components\Amo\Models\IncomingLead',
        $entity_collection = '\FK\Components\Amo\Collections\IncomingLead',
        $cache_time = false;

    /**
     * Get full
	 * @return Collection
     */
	public function leads()
	{
		return $this->list->recursiveCall();
	}
}
