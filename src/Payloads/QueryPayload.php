<?php

namespace Debugger\Duk\Payloads;

class QueryPayload
{
    public string $type = 'query';

    public function __construct(
        private readonly string $sql,
        private readonly array $bindings,
        private readonly float $time
    ) {}

    public function content(): array
    {
        return [
            'sql'      => $this->interpolate($this->sql, $this->bindings),
            'bindings' => $this->bindings,
            'time'     => round($this->time, 2),
        ];
    }

    private function interpolate(string $sql, array $bindings): string
    {
        $result = $sql;
        foreach ($bindings as $binding) {
            $value = is_string($binding) ? "'{$binding}'" : (string) $binding;
            $result = preg_replace('/\?/', $value, $result, 1);
        }
        return $result;
    }
}
