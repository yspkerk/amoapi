<?php
/**
 * @category  AquaBox
 * @package   Binotel
 * @author    Sergei Yarmolyuk <sergei.yarmolyuk@gmail.com>
 * @copyright 2019 Fabrika-Klientov
 * @version   GIT: 19.4.7
 * @link      https://fabrika-klientov.ua
 */

namespace Ufee\Amo\Base\Models;

use FK\Traits\Logger;
use Ufee\Amo\Base\Models\CustomField\EntityField;

class ModelWithIE extends ApiModel
{
    use Logger;
	protected
		$hidden = [
			'service',
			'incoming_entities',
		];

	protected $entities_relate = ['leads' => 'lead', 'companies' => 'company', 'contacts' => 'contact'];

    /**
     * Model on load
	 * @param array $data
	 * @return void
     */
    protected function _boot($data = [])
    {
		$this->attributes['incoming_entities'] = [];
		
		if (isset($data->incoming_entities)) {
			foreach($data->incoming_entities as $entity => $ies) {
			    foreach($ies as $key_entity => $ie_data) {
			        if(($entity_name = $this->entities_relate[$entity])) {
                        $this->addIE($entity_name, $ie_data);
                    }
                }
			}
            $this->setChanged('incoming_entities');
		}
	}

    /**
     * @param $entity
     * @return bool
     */
    public function addIE($entity, $data = [])
    {
	$data = (array)$data;    
        $class = '';
        if(is_object($entity)) {
            $class = get_class($entity);
            $class = ($class = explode("\\", $class)) ? strtolower(array_pop($class)) : '';
        } elseif($data) {
            $class = $entity;
            $entity = array_search($entity, $this->entities_relate);
            $entity = $this->service->instance->{$entity}()->create($data);
            if (!empty($data['customField'])) {
                foreach ($data['customField'] as $field => $value) {
                    $field = (is_array($value) && !empty($value['field'])) ? $value['field'] : $field;
                    $enum = (is_array($value) && !empty($value['enum'])) ? $value['enum'] : null;
                    $value = (is_array($value) && !empty($value['value'])) ? $value['value'] : $value;
                    try {
                        if (($entity_cf = $entity->cf($field)) !== false && $entity_cf instanceof EntityField) {
                            $entity_cf->setValue($value, $enum);
                        }
                    } catch (\Exception $e) {
                        $this->log('warning', '[o] Custom Field Not Found', $e->getMessage());
                    }
                }
            }
        }

        if(is_object($entity) && in_array($class, ['lead', 'company', 'contact']));
        {
            $class = array_search($class, $this->entities_relate);
            $this->setChanged('incoming_entities');
            $this->attributes['incoming_entities'][$class][] = array_merge($entity->toArray(), $entity->getChangedRawApiData());
            return true;
        }

        return false;
	}

    /**
     * Convert Model to array
     * @return array
     */
    public function toArray()
    {
	$fields = parent::toArray();
	unset($fields['response']);
	return $fields;
    }
}
