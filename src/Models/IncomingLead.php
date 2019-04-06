<?php
/**
 * @category  AquaBox
 * @package   Binotel
 * @author    Sergei Yarmolyuk <sergei.yarmolyuk@gmail.com>
 * @copyright 2019 Fabrika-Klientov
 * @version   GIT: 19.4.5
 * @link      https://fabrika-klientov.ua
 */

namespace FK\Components\Amo\Models;

class IncomingLead extends \Ufee\Amo\Base\Models\ApiModel
{
	protected
		$hidden = [
		],
		$writable = [
		    'source_name',
            'source_uid',
            'pipeline_id',
        ];
	
    /**
     * Model on load
	 * @param array $data
	 * @return void
     */
    protected function _boot($data = [])
    {
		parent::_boot($data);
	}

    /**
     * Convert Model to array
     * @return array
     */
    public function toArray()
    {
		$fields = parent::toArray();
		return $fields;
    }
}
