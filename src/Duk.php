<?php

namespace Debugger\Duk;

use Debugger\Duk\Payloads\CustomPayload;
use Debugger\Duk\Payloads\ExceptionPayload;
use Debugger\Duk\Payloads\LogPayload;
use Debugger\Duk\Payloads\QueryPayload;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class Duk
{
    private array $payloads = [];
    private ?string $color = null;
    private ?string $label = null;
    private string $uuid;

    private static string $host = 'localhost';
    private static int $port = 23517;
    private static bool $enabled = true;

    public function __construct()
    {
        $this->uuid = (string) Str::uuid();
    }

    public static function configure(string $host, int $port, bool $enabled): void
    {
        self::$host = $host;
        self::$port = $port;
        self::$enabled = $enabled;
    }

    public static function create(mixed ...$values): static
    {
        $instance = new static();

        if (!empty($values)) {
            $instance->payloads[] = new LogPayload($values);
            $instance->send();
        }

        return $instance;
    }

    public function pass(mixed $value): mixed
    {
        return $value;
    }

    public function color(string $color): static
    {
        $this->color = $color;
        $this->send();
        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;
        $this->send();
        return $this;
    }

    public function red(): static { return $this->color('red'); }
    public function green(): static { return $this->color('green'); }
    public function blue(): static { return $this->color('blue'); }
    public function orange(): static { return $this->color('orange'); }
    public function purple(): static { return $this->color('purple'); }
    public function gray(): static { return $this->color('gray'); }

    public function exception(Throwable $e): static
    {
        $this->payloads[] = new ExceptionPayload($e);
        $this->send();
        return $this;
    }

    public function custom(mixed $content, ?string $label = null): static
    {
        $this->payloads[] = new CustomPayload($content, $label);
        $this->send();
        return $this;
    }

    public function showQueries(): static
    {
        DB::listen(function (QueryExecuted $query) {
            $instance = new static();
            $instance->payloads[] = new QueryPayload(
                $query->sql,
                $query->bindings,
                $query->time
            );
            $instance->send();
        });

        return $this;
    }

    private function send(): void
    {
        if (!self::$enabled || empty($this->payloads)) {
            return;
        }

        $payload = [
            'uuid'     => $this->uuid,
            'payloads' => array_map(fn ($p) => [
                'type'    => $p->type,
                'content' => $p->content(),
            ], $this->payloads),
            'meta' => [
                'php_version'    => PHP_VERSION,
                'laravel_version' => app()->version(),
                'color'          => $this->color,
                'label'          => $this->label,
            ],
        ];

        $this->httpSend($payload);
    }

    private function httpSend(array $payload): void
    {
        $url = sprintf('http://%s:%d', self::$host, self::$port);
        $json = json_encode($payload);

        $context = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => "Content-Type: application/json\r\nContent-Length: " . strlen($json),
                'content'       => $json,
                'timeout'       => 0.5,
                'ignore_errors' => true,
            ],
        ]);

        @file_get_contents($url, false, $context);
    }
}
