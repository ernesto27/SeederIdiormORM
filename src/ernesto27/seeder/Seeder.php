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
     * Crea una instancia de la clase utilizand un singleton
     * @return object
     */
    public static function init()
    {
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
			}
			return $this;
		}

        return $this->model->save();
    }

    protected function createModel()
	{
		$this->model = \ORM::for_table($this->tableName)->create();
	}

    protected function setFields()
    {
        foreach ($this->data as $item) {
            $this->model->$item['field'] = $item['value'];
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

}
