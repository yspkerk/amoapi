<?php
/**
 * amoCRM Model Custom field Collection class
 */
namespace Ufee\Amo\Collections;
use Ufee\Amo\Models\CustomField;

class CustomFieldCollection extends CollectionWrapper
{
    const FIELD_CLASSES = [
        1 => 'Ufee\Amo\Base\Models\CustomField\TextField',
        2 => 'Ufee\Amo\Base\Models\CustomField\NumericField',
        3 => 'Ufee\Amo\Base\Models\CustomField\CheckboxField',
        4 => 'Ufee\Amo\Base\Models\CustomField\SelectField',
        5 => 'Ufee\Amo\Base\Models\CustomField\MultiSelectField',
        6 => 'Ufee\Amo\Base\Models\CustomField\DateField',
        7 => 'Ufee\Amo\Base\Models\CustomField\UrlField',
        8 => 'Ufee\Amo\Base\Models\CustomField\MultiTextField',
        9 => 'Ufee\Amo\Base\Models\CustomField\TextareaField',
        10 => 'Ufee\Amo\Base\Models\CustomField\RadioButtonField',
        11 => 'Ufee\Amo\Base\Models\CustomField\StreetAddressField',
        13 => 'Ufee\Amo\Base\Models\CustomField\SmartAddressField',
        14 => 'Ufee\Amo\Base\Models\CustomField\BirthDayField',
        15 => 'Ufee\Amo\Base\Models\CustomField\JurField',
        17 => 'Ufee\Amo\Base\Models\CustomField\OrgField'
	];
	
    /**
     * Constructor
	 * @param array $elements
	 * @param Account $account
     */
    public function __construct(Array $elements = [], \Ufee\Amo\Models\Account &$account)
    {
		$this->collection = new \Ufee\Amo\Base\Collections\Collection($elements);
		$this->collection->each(function(&$item) {
			$item = new CustomField($item);
		});
		$this->attributes['account'] = $account;
	}

    /**
     * Get cf classname
	 * @param CustomField $cfield
	 * @return string
     */
    public function getClassFrom(CustomField $cfield)
    {
        if (!array_key_exists($cfield->field_type, self::FIELD_CLASSES)) {
            throw new \Exception('Unregistered custom field class for type: '.$cfield->field_type);
        }
        return self::FIELD_CLASSES[$cfield->field_type];
    }
}
