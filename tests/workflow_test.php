<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'case.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowTest extends ezcWorkflowTestCase
{
    protected $workflow;
    protected $startNode;
    protected $endNode;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowTest' );
    }

    public function testGetName()
    {
        $this->setUpStartEnd();
        $this->assertEquals( 'StartEnd', $this->workflow->name );
    }

    public function testGetSetId()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->id );

        $this->workflow->id = 1;

        $this->assertEquals( 1, $this->workflow->id );
    }

    public function testGetSetDefinition()
    {
        $this->setUpStartEnd();
        $this->assertNull( $this->workflow->definitionHandler );

        $this->workflow->definitionHandler = $this->definition;
        $this->assertNotNull( $this->workflow->definitionHandler );
    }

    public function testGetSetName()
    {
        $workflow = new ezcWorkflow( 'Test' );
        $this->assertEquals( 'Test', $workflow->name );

        $workflow->name = 'Test2';
        $this->assertEquals( 'Test2', $workflow->name );

        try
        {
            $workflow->name = array();
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testGetNodes()
    {
        $this->setUpStartEnd();
        $nodes = $this->workflow->nodes;

        $this->assertSame( $this->startNode, $nodes[0] );
        $this->assertSame( $this->endNode, $nodes[1] );
    }

    public function testHasSubWorkflows()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->hasSubWorkflows() );

        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->assertTrue( $this->workflow->hasSubWorkflows() );
    }

    public function testIsInteractive()
    {
        $this->setUpStartEnd();
        $this->assertFalse( $this->workflow->isInteractive() );
    }

    public function testVerify()
    {
        $this->setUpStartEnd();
        $this->workflow->verify();
    }

    public function testVerify2()
    {
        try
        {
            $workflow = new ezcWorkflow( 'Test' );
            $workflow->verify();
        }
        catch ( ezcWorkflowDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVerify3()
    {
        try
        {
            $workflow = new ezcWorkflow( 'Test' );
            $workflow->getStartNode()->addOutNode( new ezcWorkflowNodeStart );
            $workflow->verify();
        }
        catch ( ezcWorkflowDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVariableHandler()
    {
        $this->setUpStartEnd();

        $this->assertFalse( $this->workflow->removeVariableHandler( 'foo' ) );

        $this->workflow->setVariableHandlers(
          array( 'foo' => 'ezcWorkflowTestVariableHandler' )
        );

        $this->assertTrue( $this->workflow->removeVariableHandler( 'foo' ) );
    }

    public function testVariableHandler2()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->addVariableHandler(
              'foo', 'StdClass'
            );
        }
        catch ( ezcWorkflowInvalidDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }

    public function testVariableHandler3()
    {
        $this->setUpStartEnd();

        try
        {
            $this->workflow->addVariableHandler(
              'foo', 'NotExisting'
            );
        }
        catch ( ezcWorkflowInvalidDefinitionException $e )
        {
            return;
        }

        $this->fail();
    }
}
?>
