<?php
namespace GiftCards\Extension;

use GiftCards\TestExtension\TestCase\Extension\AbstractExtension;

use Symfony\Component\Form\FormBuilderInterface;

class FormTypeExtension extends AbstractExtension
{
    public function assertFormTypeAndOptions(FormBuilderInterface $builder, $type, array $options = array())
    {
        try {

            $this->assertEquals($type, $builder->getType()->getName());
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                'Failed asserting that form type is named "%s", name received is "%s"',
                $type,
                $builder->getType()->getName()
            ));
        }

        try {

            $returnedOptions = array();

            foreach ($options as $name => $value) {

                $returnedOptions[$name] = $builder->getOption($name);
            }

            $this->assertEquals($options, $returnedOptions);
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                "Failed asserting that form options were the same:\n%s",
                $e->getComparisonFailure()->getDiff()
            ));
        }
    }
}
