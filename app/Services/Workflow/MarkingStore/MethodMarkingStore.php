<?php

namespace App\Services\Workflow\MarkingStore;

use App\Services\Workflow\Exception\LogicException;
use App\Services\Workflow\Marking;


final class MethodMarkingStore implements MarkingStoreInterface
{
    private $singleState;
    private $property;

    /**
     * @param string $property Used to determine methods to call
     *                         The `getMarking` method will use `$subject->getProperty()`
     *                         The `setMarking` method will use `$subject->setProperty(string|array $places, array $context = array())`
     */
    public function __construct(bool $singleState = false, string $property = 'marking')
    {
        $this->singleState = $singleState;
        $this->property = $property;
    }

    /**
     * {@inheritdoc}
     */
    public function getMarking(object $subject): Marking
    {
        $method = 'get'.ucfirst($this->property);

        if (!method_exists($subject, $method)) {
            throw new LogicException(sprintf('The method "%s::%s()" does not exist.', get_debug_type($subject), $method));
        }

        $marking = null;
        try {
            $marking = $subject->{$method}();
        } catch (\Error $e) {
            $unInitializedPropertyMassage = sprintf('Typed property %s::$%s must not be accessed before initialization', get_debug_type($subject), $this->property);
            if ($e->getMessage() !== $unInitializedPropertyMassage) {
                throw $e;
            }
        }

       
        if (null === $marking || empty($marking)) {
            return new Marking([]);
        }

 

        if ($this->singleState) {
            $marking = [(string) $marking => 1];
        }

        if(gettype($marking) == 'array'){
            return new Marking($marking);   
        }
        
        return new Marking(json_decode($marking, true));
    }

    /**
     * {@inheritdoc}
     */
    public function setMarking(object $subject, Marking $marking, array $context = [])
    {
        $marking = $marking->getPlaces();

        if ($this->singleState) {
            $marking = key($marking);
        }

        $method = 'set'.ucfirst($this->property);

        if (!method_exists($subject, $method)) {
            throw new LogicException(sprintf('The method "%s::%s()" does not exist.', get_debug_type($subject), $method));
        }

        $subject->{$method}($marking, $context);
    }
}
