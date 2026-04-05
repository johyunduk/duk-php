<?php

namespace Debugger\Duk\Payloads;

class LogPayload
{
    public string $type = 'log';

    public function __construct(
        private readonly array $values
    ) {}

    public function content(): array
    {
        return [
            'values' => array_map(fn ($value) => $this->serialize($value), $this->values),
        ];
    }

    private function serialize(mixed $value): mixed
    {
        if (is_string($value) || is_numeric($value) || is_bool($value) || is_null($value)) {
            return $value;
        }

        return json_decode(json_encode($value), true);
    }
}
