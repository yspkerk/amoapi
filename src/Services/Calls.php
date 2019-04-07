<?php
/**
 * @category  AquaBox
 * @package   Binotel
 * @author    Sergei Yarmolyuk <sergei.yarmolyuk@gmail.com>
 * @copyright 2019 Fabrika-Klientov
 * @version   GIT: 19.4.7
 * @link      https://fabrika-klientov.ua
 */

namespace Ufee\Amo\Services;

class Calls extends \Ufee\Amo\Base\Services\LimitedList
{
	protected static 
		$_require = [
			'add' => ['phone_number','direction'],
            'update' => ['updated_at', 'upadted_by']
		];
	protected
		$entity_key = 'calls',
		$entity_model = '\Ufee\Amo\Models\Call',
		$entity_collection = '\Ufee\Amo\Collections\CallCollection',
		$cache_time = false;


    /**
     * Get full
	 * @return Collection
     */
	public function calls()
	{
		return $this->list->recursiveCall();
	}

    /**
     * Get models by id
	 * @param integer|array $id
	 * @param string $element_type - contact/lead/company/task
	 * @return Model|Collection
     */
	public function find($id, $element_type = 'all')
	{
		$result = $this->list->where('limit_rows', is_array($id) ? count($id) : 1)
							 ->where('limit_offset', 0)
							 ->call();
		if (is_array($id)) {
			return $result;
		}
		if (!$model = $result->get(0)) {
			return null;
		}
		return $model;
	}
}
