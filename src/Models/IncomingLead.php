<?php
/**
 * @category  AquaBox
 * @package   Binotel
 * @author    Sergei Yarmolyuk <sergei.yarmolyuk@gmail.com>
 * @copyright 2019 Fabrika-Klientov
 * @version   GIT: 19.4.7
 * @link      https://fabrika-klientov.ua
 */

namespace Ufee\Amo\Models;

class IncomingLead extends \Ufee\Amo\Base\Models\ModelWithIE
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

    /**
     * @param $info
     */
    public function addInfo($info)
    {
        $this->setChanged('incoming_lead_info');
        $this->attributes['incoming_lead_info'] = $info;
    }
}
