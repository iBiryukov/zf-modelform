<?php
class CU_Form_Model_Adapter_DoctrineTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		parent::setUp();
		Doctrine_Manager::connection('sqlite::memory:');
		Doctrine::createTablesFromModels();
	}

	public function tearDown()
	{
		parent::tearDown();
		Doctrine_Manager::getInstance()->closeConnection(Doctrine_Manager::connection());
	}

	public function testTableSetup()
	{
		$adapter = new CU_Form_Model_Adapter_Doctrine();
		$adapter->setTable('User');

		$this->assertTrue($adapter->getTable() instanceof Doctrine_Table);
	}

	public function testGetsColumnAmountRight()
	{
		$adapter = new CU_Form_Model_Adapter_Doctrine();
		$adapter->setTable('User');

		$columns = $adapter->getColumns();
		$this->assertEquals(4, count($columns));
	}

	public function testGetsCorrectDataForColumns()
	{
		$adapter = new CU_Form_Model_Adapter_Doctrine();
		$adapter->setTable('User');

		$columns = $adapter->getColumns();
		foreach($columns as $name => $c)
		{
			$this->assertTrue(isset($c['type']), 'Data does not contain type');
			$this->assertTrue(isset($c['notnull']), 'Data does not contain notnull');
			$this->assertTrue(isset($c['values']), 'Data does not contain values');
			$this->assertTrue(isset($c['primary']), 'Data does not contain primary');
			
			//Just test that it's not a numeric index
			$this->assertTrue(strlen($name) > 1);
		}
	}

	public function testGetsRelationCountRight()
	{
		$adapter = new CU_Form_Model_Adapter_Doctrine();
		$adapter->setTable('Comment');

		$rels = $adapter->getRelations();
		$this->assertEquals(1, count($rels));
	}

	public function testGetsCorrectDataForRelations()
	{
		$adapter = new CU_Form_Model_Adapter_Doctrine();
		$adapter->setTable('Comment');

		$rels = $adapter->getRelations();

		foreach($rels as $alias => $r)
		{
			$this->assertTrue(isset($r['type']), 'Data does not contain type');
			$this->assertTrue(isset($r['id']), 'Data does not contain id');
			$this->assertTrue(isset($r['model']), 'Data does not contain model');
			$this->assertTrue(isset($r['notnull']), 'Data does not contain notnull');
			$this->assertTrue(isset($r['local']), 'Data does not contain local');

			//Again just check it's not a numeric index
			$this->assertTrue(strlen($alias) > 2);
		}
	}
}
