<?php

declare(strict_types=1);

namespace App\Email\Contact;
use App\Validator\Validate;

final class Validator extends Validate
{
    public array $requiredFields = [
        'subject',
        'data' => [
            'interests',
            'fullname',
            'cellphone',
            'subject'
        ]
    ];

    public function __construct(array $fields)
    {
        parent::__construct($fields);
        $this->validateRequired();
    }
}