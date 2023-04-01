<?php

declare(strict_types=1);

namespace App\Template;

use App\Exception\TemplateNotFoundException;

final class TemplateProvider
{
    public static function getFromString(string $template): string
    {
        $returnTemplate = '';
        switch($template) {
            case 'contact':
                $returnTemplate = 'contact/contact.template.html';
            break;
            default:
                throw new TemplateNotFoundException("Template `$template` not found.");
        }

        return $returnTemplate;
    }
}