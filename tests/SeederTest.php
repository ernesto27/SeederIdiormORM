<?php
require_once 'vendor/autoload.php';
require_once 'src/ernesto27/seeder/Seeder.php';

use \Ernesto27\Seeder\Seeder;

class SeederTest extends \PHPUnit_Framework_TestCase
{
    private $seeder;


	protected function setUp()
	{
        // Create a model and save on DB
        // Seeder::init()->table('sometable')->data($posts)->create(5)

        $this->seeder = Seeder::init();
	}

	/** @test */
	public function it_should_create_an_instance_of_seeder_class()
	{
        var_dump($this->seeder);
        $this->assertInstanceOf(Seeder::class, $this->seeder);
	}
}
