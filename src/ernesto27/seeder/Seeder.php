<?php

namespace Ernesto27\Seeder;

class Seeder
{
    /**
     * Guarda el objecto instanciado
     * @var object
     */
    private static $instance = null;

    /**
     * Instancia de idiorm ORM
     * @var object
     */
    private $model;

    /**
     * Nombre de la tabla DB
     * @var object
     */
    private $tableName;

    /**
     * Data dummy que se guarda en el modelo
     * @var array
     */
    private $data;

    /**
     * Instacia de faker dummy data
     * @var object
     */
    private $faker;

    /**
     * Obtiene data con los valores creador por faker
     * @var array
     */
    private $fakerData;


    private static $modelCountSaved = 0;


    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }


    /**
     * Crea una instancia de la clase utilizand un singleton
     * @return object
     */
    public static function init()
    {
        self::$modelCountSaved = 0;
        if(!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Setea el nombre de la tabla
     * @param string
     * @return
     */
    public function table($value)
    {
        $this->tableName = $value;
        $this->createModel();
        $this->fakerData = array();
		return $this;
    }

    /**
     * Setea la data dummy que se guarda en DB
     * @param array
     * @return object
     */
    public function data($data)
    {
        $this->data = $data;
        $this->setFields();
        return $this;
    }

    /**
     * Save a model on DB
     * @param $count integer
     * @return $this
     */
    public function create($count = false)
    {
        if($count){
			for ($i=0; $i < $count; $i++) {
				$this->createModel();
                $this->setFields()->save();
                self::$modelCountSaved += 1;
			}
			return $this;
		}

        self::$modelCountSaved += 1;
        return $this->model->save();
    }

    protected function createModel()
	{
		$this->model = \ORM::for_table($this->tableName)->create();
	}

    protected function setFields()
    {
        foreach ($this->data as $item) {
            $fakerArray = explode('.', $item['value']);
            $fakerValue = $this->faker->$fakerArray[1];
            $this->fakerData[] = array('field' => $item['field'], 'value' => $fakerValue);
            $this->model->$item['field'] = $fakerValue ;
        }
        return $this->model;
    }

    /**
     * Verifico si un model existe en DB
     * @param $tableName string
     * @param $data array
     * @return bool
     */

    public function existsOnDatabase($tableName, $data, $count = false)
    {
        $factory = \ORM::for_table($tableName);
        foreach ($data as $item){
            $factory->where($item['field'], $item['value']);
        }

        if($countRows = $factory->count()){
            if($count) return $countRows;
            return true;
        }
        return false;
    }


    public function getModel()
    {
        return $this->model;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getFakerData()
    {
        return $this->fakerData;
    }

    public function getModelCountSaved()
    {
        return self::$modelCountSaved;
    }

}
