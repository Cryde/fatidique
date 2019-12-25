<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer as DateTimeToStringTransformerAlias;

class DateTimeToStringTransformer extends DateTimeToStringTransformerAlias
{
    public function __construct(
        string $inputTimezone = null,
        string $outputTimezone = null,
        string $format = 'Y-m-d H:i:s'
    ) {
        parent::__construct($inputTimezone, $outputTimezone, 'd/m/Y H:i');
    }
}
