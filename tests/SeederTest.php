<?php
require_once 'vendor/autoload.php';

use \Ernesto27\Seeder\Seeder;

\ORM::configure('sqlite:./data/database');

class SeederTest extends \PHPUnit_Framework_TestCase
{
    private $seeder;
    private $faker;
    private $data;

    public static function setUpBeforeClass()
    {
        exec('vendor/bin/phinx rollback -e development -t 0');
        exec('vendor/bin/phinx migrate');
    }

	protected function setUp()
	{
        $this->seeder = Seeder::getInstance();
        $this->faker = Faker\Factory::create();
        $this->data = array(
            array('field' => 'title', 'value' => 'faker.sentence'),
            array('field' => 'body', 'value' => 'faker.text')
        );

        $this->dataUser = array(
            array('field' => 'name', 'value' => 'faker.name'),
            array('field' => 'email', 'value' => 'faker.email')
        );
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
        $this->seeder->table('posts')->data($this->data);
        $this->assertEquals($this->data, $this->seeder->getData());
    }

    /** @test */
    public function it_should_create_a_instance_of_orm_idiorm()
    {
        $this->assertEquals(\ORM::class, get_class($this->seeder->getModel()));
    }

    /** @test */
    public function it_should_check_if_a_entity_exists_on_db()
    {
        $seeder = Seeder::getInstance();
        $seeder->table('posts')->data($this->data)->create();
        $this->assertTrue($seeder->existsOnDatabase('posts', $seeder->getFakerData()));
    }

    /** @test */
    public function it_should_save_the_quantity_of_parameter_of_create()
    {
        $countTest = 8;
        $save = Seeder::getInstance()->table('posts')->data($this->data)->create($countTest);
        $this->assertSame($countTest, $this->seeder->getModelCountSaved());
    }

    /** @test */
    public function it_should_attach_a_child_model_using_a_closure()
    {
        Seeder::getInstance()->table('users')->data($this->dataUser)->create()->each(function($model){
            $this->data['user_id'] = array(
                'field' => 'user_id',
                'value' => $model->id
            );
            Seeder::getInstance()->table('posts')->data($this->data)->create();
        });

        $this->assertTrue(Seeder::getInstance()->existsOnDatabase('posts', Seeder::getInstance()->getFakerData()));
    }


    /** @test */
    public function it_should_attach_a_child_model_when_create_any_quantity_using_a_closure()
    {

        Seeder::getInstance()->table('users')->data($this->dataUser)->create(4)->each(function($model){
            $this->data['user_id'] = array(
                'field' => 'user_id',
                'value' => $model->id
            );

            Seeder::getInstance()->table('posts')->data($this->data)->create();
        });

        $this->assertTrue(Seeder::getInstance()->existsOnDatabase('posts', Seeder::getInstance()->getFakerData()));
    }



}
