<?php

namespace GiftCards\Extension;

use GiftCards\TestExtension\TestCase\Extension\AbstractExtendableTestCase;

class FakerExtensionTest extends AbstractExtendableTestCase
{
    protected $extension;

    public function setUp()
    {
    	$this->extension = new FakerExtension();
    }

    public function testGetFaker()
    {
    	$this->assertInstanceOf('Faker\Generator', $this->extension->getFaker());
    }
}
