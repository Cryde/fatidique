<?php

namespace AppBundle\Form\DataTransformer;

class DateTimeToStringTransformer extends \Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer
{
    public function __construct($inputTimezone = null, $outputTimezone = null, $format = 'Y-m-d H:i:s')
    {
        parent::__construct($inputTimezone, $outputTimezone, 'd/m/Y H:i');
    }
}
