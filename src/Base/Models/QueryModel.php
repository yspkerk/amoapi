<?php
/**
 * amoCRM Base API Query model
 */
namespace Ufee\Amo\Base\Models;
use Ufee\Amo\Amoapi,
	Ufee\Amo\Base\Services\Service;

class QueryModel
{
	protected 
		$system = [
			'url',
			'method',
			'args',
			'post_data',
			'start_time',
			'end_time',
			'execution_time',
			'sleep_time',
			'hash',
			'memory_usage',
			'headers'
		],
		$hidden = [
			'account_id',
			'service',
			'curl',
			'latency',
			'cookie_file',
			'response'
		],
		$attributes = [];
		
    /**
     * Constructor
	 * @param Amoapi $instance
	 * @param string $service_class
     */
    public function __construct(Amoapi &$instance, $service_class = '')
    {
        foreach ($this->system as $field_key) {
			$this->attributes[$field_key] = null;
		}
        foreach ($this->hidden as $field_key) {
			$this->attributes[$field_key] = null;
		}
		$this->attributes['account_id'] = $instance->getAuth('id');
		$this->attributes['service'] = $service_class;
		$this->_boot();
	}

    /**
     * Model on load
	 * @return void
     */
	protected function _boot()
	{
		$this->attributes['headers'] = [];
		$this->attributes['method'] = 'GET';
		$this->attributes['latency'] = 1;
		$this->attributes['curl'] = \curl_init();
		$this->attributes['cookie_file'] = AMOAPI_COOKIES.'/'.$this->instance()->getAuth('domain').'.cookie';
		curl_setopt_array($this->curl, [
			CURLOPT_AUTOREFERER => 1,
			CURLOPT_USERAGENT => 'Amoapi v.7 ('.$this->instance()->getAuth('domain').')',
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FOLLOWLOCATION => 1,
			CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
			CURLOPT_COOKIEJAR => $this->cookie_file,
			CURLOPT_COOKIEFILE => $this->cookie_file,
		]);
	}

    /**
     * Set query method
     * @param string $method
     */
    public function setMethod($method = 'GET')
    {
		$this->attributes['method'] = $method;
		return $this;
    }
    
    /**
     * Set query args
     * @param array $args
     */
    public function setArgs($args = [])
    {
		$this->attributes['args'] = $args;
		return $this;
    }

    /**
     * Set post query data
     * @param array $data
     */
    public function setPostData($data = [])
    {
		$this->attributes['post_data'] = $data;
		return $this;
    }

    /**
     * Set url link
     * @param string $url
     */
    public function setUrl($url)
    {
		$this->attributes['url'] = $url;
		return $this;
	}
	
    /**
     * Set headers
     * @param string $name
	 * @param mixed $value
     */
    public function setHeader($name, $value)
    {
		$this->attributes['headers'][$name] = $value;
		return $this;
	}

    /**
     * Get headers
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];
        foreach ($this->headers as $name=>$value) {
            $headers[]= $name.': '.$value;
        }
		return $headers;
	}

    /**
     * Get url link
	 * @return string
     */
    public function getUrl()
    {
        $url = $this->url;
        if ($this->args) {
            $url .= '?' . http_build_query($this->args);
        }
        return 'https://'.$this->instance()->getAuth('domain').'.amocrm.'.$this->instance()->getAuth('zone').$url;
	}

    /**
     * Generate query hash
	 * @return string
     */
    public function generateHash()
    {
        return $this->attributes['hash'] = md5(
			$this->instance()->getAuth('domain').
			$this->instance()->getAuth('login').
			$this->instance()->getAuth('hash').
			$this->method.
			$this->getUrl().
			json_encode($this->post_data)
		);
	}

    /**
     * Get start date
	 * @return string
     */
    public function startDate($format = 'Y-m-d H:i:s')
    {
        return date($format, $this->start_time);
	}

    /**
     * Get end date
	 * @return string
     */
    public function endDate($format = 'Y-m-d H:i:s')
    {
        return date($format, $this->end_time);
	}

    /**
     * Get query service
	 * @return Service
     */
    public function getService()
    {
		$instance = $this->instance();
		$serviceClass = $this->service;
        if (!$service = $serviceClass::getInstance(null, $instance)) {
			$service = $serviceClass::setInstance(null, $instance);
		}
		return $service;
	}

    /**
     * Get Amoapi instance
	 * @return Amoapi
     */
    public function instance()
    {
		return Amoapi::getInstance($this->account_id);
	}

    /**
     * Protect get model fields
	 * @param string $field
     */
	public function __get($field)
	{
		if (!array_key_exists($field, $this->attributes)) {
			throw new \Exception('Invalid Query field: '.$field);
		}
		return $this->attributes[$field];
	}
	
    /**
     * Protect set model fields
	 * @param string $field
	 * @param string $value
     */
	public function __set($field, $value)
	{
		if (!array_key_exists($field, $this->attributes)) {
			throw new \Exception('Invalid Query field: '.$field);
		}
		throw new \Exception('Protected Query field set fail: '.$field);
		$this->attributes[$field] = $value;
	}

    /**
     * Clear query cache
     * @return QueryModel
     */
    public function clearCache()
    {
		if (file_exists($this->cache_path.'/'.$this->hash.'.cache')) {
			@unlink($this->cache_path.'/'.$this->hash.'.cache');
		}
	}

    /**
     * Convert Model to array
     * @return array
     */
    public function toArray()
    {
		$fields = [];
		foreach ($this->attributes as $field_key=>$val) {
			if (in_array($field_key, $this->hidden)) {
				continue;
			}
			$fields[$field_key] = $val;
		}
		return $fields;
    }
	
    /**
     * Close curl
     */
    public function __destruct()
    {
        if (is_resource($this->curl)) {
			curl_close($this->curl);
		}
    }
}