<?php

namespace Debugger\Duk\Payloads;

use Throwable;

class ExceptionPayload
{
    public string $type = 'exception';

    public function __construct(
        private readonly Throwable $exception
    ) {}

    public function content(): array
    {
        return [
            'class'   => get_class($this->exception),
            'message' => $this->exception->getMessage(),
            'code'    => $this->exception->getCode(),
            'file'    => $this->exception->getFile(),
            'line'    => $this->exception->getLine(),
            'trace'   => collect($this->exception->getTrace())
                ->take(10)
                ->map(fn ($frame) => [
                    'file'     => $frame['file'] ?? '[internal]',
                    'line'     => $frame['line'] ?? 0,
                    'function' => ($frame['class'] ?? '') . ($frame['type'] ?? '') . $frame['function'],
                ])
                ->values()
                ->all(),
        ];
    }
}
