<?php

declare(strict_types=1);

namespace App\Template;

final class TemplateManager
{
    private string $defaultTemplateDirectory;
    private string $fileContent;

    public function __construct()
    {
        $this->defaultTemplateDirectory = getenv('TEMPLATE_DIR');
    }

    public function loadFile(string $file): self
    {
        $this->fileContent = @file_get_contents($this->defaultTemplateDirectory . $file);
        return $this;
    }

    public function replace(array $valuesToReplace): self
    {
        foreach ($valuesToReplace as $variable => $value) {
            $this->fileContent = str_replace('{$'.$variable.'}', $value, $this->fileContent);
        }
        return $this;
    }

    public function build(): string
    {
        return $this->fileContent;
    }
}