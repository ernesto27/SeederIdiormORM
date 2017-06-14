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
     * Crea una instancia de la clase utilizand un singleton
     */
    public static function init()
    {
        if(!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }


}
