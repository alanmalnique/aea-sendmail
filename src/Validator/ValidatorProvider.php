<?php

declare(strict_types=1);

namespace App\Validator;

use App\Email\Contact\Validator as ContactValidator;
use App\Validator\Exception\ValidatorProviderNotFoundException;

final class ValidatorProvider
{
    public static function validate(string $type, array $fields): void
    {
        switch($type) {
            case 'contact':
                new ContactValidator($fields);
            break;
            default:
                throw new ValidatorProviderNotFoundException("Type `$type` not found.");
        }
    }
}