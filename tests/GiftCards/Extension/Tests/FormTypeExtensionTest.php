<?php

namespace GiftCards\Extension;

use Mockery;
use GiftCards\TestExtension\TestCase\Extension\AbstractExtendableTestCase;

class FormTypeExtensionTest extends AbstractExtendableTestCase
{
    protected $extension;
    
    public function setUp()
    {
    	$this->extension = new FormTypeExtension();
    }
    
    public function testAssertFormTypeAndOptions()
    {
    	$builder = Mockery::mock('Symfony\Component\Form\FormBuilderInterface');
    	$builder
    	   ->shouldReceive('getType->getName')
    	   ->once()
    	   ->andReturn('type')
    	;
    	$builder
    	   ->shouldReceive('getOption')
    	   ->once()
    	   ->with('key')
    	   ->andReturn('value')
    	;
        $this->extension->assertFormTypeAndOptions($builder, 'type', array('key' => 'value'));
    }
    
    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that form type is named "type", name received is "wrong_type"
     */
    public function testAssertFormTypeAndOptionsWhereTypeWrong()
    {
    	$builder = Mockery::mock('Symfony\Component\Form\FormBuilderInterface');
    	$builder
    	   ->shouldReceive('getType->getName')
    	   ->twice()
    	   ->andReturn('wrong_type')
    	;
        $this->extension->assertFormTypeAndOptions($builder, 'type', array('key' => 'value'));
    }

    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that form options were the same:
     *   Array (
     *      'key' => 'value'
     *   )
     */
    public function testAssertFormTypeAndOptionsWhereOptionsWrong()
    {
        $builder = Mockery::mock('Symfony\Component\Form\FormBuilderInterface');
    	$builder
    	   ->shouldReceive('getType->getName')
    	   ->once()
    	   ->andReturn('type')
    	;
    	$builder
    	   ->shouldReceive('getOption')
    	   ->once()
    	   ->with('key')
    	   ->andReturn('value')
    	;
        $this->extension->assertFormTypeAndOptions($builder, 'type', array('key' => 'other_value'));
    }
}
