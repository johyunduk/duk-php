<?php

namespace Debugger\Duk\Payloads;

class CustomPayload
{
    public string $type = 'custom';

    public function __construct(
        private readonly mixed $content,
        private readonly ?string $label = null
    ) {}

    public function content(): array
    {
        return [
            'content' => $this->content,
            'label'   => $this->label,
        ];
    }
}
