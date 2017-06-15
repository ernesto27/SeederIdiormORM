<?php
require_once 'vendor/autoload.php';
require_once 'src/ernesto27/seeder/Seeder.php';

use \Ernesto27\Seeder\Seeder;
\ORM::configure('sqlite:./data/database');

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
        $this->assertInstanceOf(Seeder::class, $this->seeder);
	}

    /** @test */
    public function it_should_set_a_table_name()
    {
        $this->seeder->table('posts');
        $this->assertEquals('posts', $this->seeder->getTableName());
    }

    /** @test */
    public function it_should_set_data_dummy_array()
    {
        $data = array(
            array('key' => 'title1', 'title post2'),
            array('key' => 'title2', 'title post2')
        );
        $this->seeder->table('posts')->data($data);
        $this->assertEquals($data, $this->seeder->getData());
    }

    /** @test */
    public function it_should_create_a_instance_of_orm_idiorm()
    {
        $this->assertEquals(\ORM::class, get_class($this->seeder->getModel()));
    }



    /** @test */
    public function it_should_save_on_sqlite_a_model()
    {
        $data = array(
            array('key' => 'title1', 'title post2'),
            array('key' => 'title2', 'title post2')
        );
        $posts = \ORM::for_table('posts')->find_many();
        var_dump($posts);
        //$this->seeder->table('posts')->data($data)->create();
        // $this->assertEquals(\ORM::class, get_class($this->seeder->getModel()));
    }

}
