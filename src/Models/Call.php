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

class Call extends \Ufee\Amo\Base\Models\ApiModel
{
	protected
		$hidden = [
		],
		$writable = [
		    'phone_number',
            'direction',
            'uniq',
            'duration',
            'source',
            'call_status',
            'call_result',
            'link',
            'responsible_user_id'
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
