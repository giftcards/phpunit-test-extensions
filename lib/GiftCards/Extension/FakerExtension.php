<?php

namespace GiftCards\Extension;

use GiftCards\TestExtension\TestCase\Extension\AbstractExtension;
use Faker;

class FakerExtension extends AbstractExtension
{
    public function getFaker()
    {
        return Faker\Factory::create();
    }
}
