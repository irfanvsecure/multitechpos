<?php

namespace App\Mcp;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * MCP over Streamable HTTP (JSON-RPC 2.0), stateless: every POST is answered with JSON.
 */
class Server
{
    public const NAME = 'multitechpos-website';

    public const VERSION = '1.0.0';

    public const PROTOCOLS = ['2025-06-18', '2025-03-26', '2024-11-05'];

    /** @var array<string, Tool> */
    private array $tools = [];

    public function __construct()
    {
        foreach (Registry::tools() as $tool) {
            $this->tools[$tool->name] = $tool;
        }
    }

    /** @return array<string, Tool> */
    public function tools(): array
    {
        return $this->tools;
    }

    /** Handle one decoded JSON-RPC message (or a batch). Null = no response (notification). */
    public function handle(mixed $message): ?array
    {
        if (is_array($message) && array_is_list($message) && $message !== []) {
            $out = array_values(array_filter(array_map(fn ($m) => $this->one($m), $message)));

            return $out ?: null;
        }

        return $this->one($message);
    }

    private function one(mixed $m): ?array
    {
        if (! is_array($m) || ($m['jsonrpc'] ?? null) !== '2.0' || ! isset($m['method']) || ! is_string($m['method'])) {
            return $this->error($m['id'] ?? null, -32600, 'Invalid Request');
        }
        $id = $m['id'] ?? null;
        $isNotification = ! array_key_exists('id', $m);
        $params = $m['params'] ?? [];

        try {
            $result = match ($m['method']) {
                'initialize' => $this->initialize($params),
                'ping' => (object) [],
                'tools/list' => ['tools' => array_values(array_map(fn (Tool $t) => $t->definition(), $this->tools))],
                'tools/call' => $this->call($params),
                'resources/list' => ['resources' => []],
                'prompts/list' => ['prompts' => []],
                default => str_starts_with($m['method'], 'notifications/') ? null : throw new \BadMethodCallException($m['method']),
            };
        } catch (\BadMethodCallException $e) {
            return $isNotification ? null : $this->error($id, -32601, 'Method not found: '.$e->getMessage());
        } catch (\InvalidArgumentException $e) {
            return $isNotification ? null : $this->error($id, -32602, $e->getMessage());
        }

        if ($isNotification) {
            return null;
        }

        return ['jsonrpc' => '2.0', 'id' => $id, 'result' => $result];
    }

    private function initialize(array $params): array
    {
        $asked = $params['protocolVersion'] ?? self::PROTOCOLS[0];

        return [
            'protocolVersion' => in_array($asked, self::PROTOCOLS, true) ? $asked : self::PROTOCOLS[0],
            'capabilities' => ['tools' => ['listChanged' => false]],
            'serverInfo' => ['name' => self::NAME, 'title' => 'MultiTech POS website', 'version' => self::VERSION],
            'instructions' => Instructions::text(),
        ];
    }

    private function call(array $params): array
    {
        $name = $params['name'] ?? null;
        $tool = is_string($name) ? ($this->tools[$name] ?? null) : null;
        if (! $tool) {
            throw new \InvalidArgumentException('Unknown tool: '.json_encode($name));
        }
        $args = $params['arguments'] ?? [];
        if (! is_array($args)) {
            throw new \InvalidArgumentException('arguments must be an object');
        }

        $started = microtime(true);
        try {
            if ($problems = Arguments::check($tool, $args)) {
                throw new ToolError('Invalid arguments: '.implode(' ', $problems));
            }
            Context::$tool = $tool->name;
            $result = ($tool->handler)($args);
            $this->log($tool, $args, 'ok', $started);

            return [
                'content' => [['type' => 'text', 'text' => json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE)]],
                'isError' => false,
            ];
        } catch (ToolError $e) {
            $this->log($tool, $args, 'refused: '.$e->getMessage(), $started);

            return $this->toolError($e->getMessage(), $e->details);
        } catch (ValidationException $e) {
            $this->log($tool, $args, 'invalid', $started);

            return $this->toolError('Invalid arguments.', $e->errors());
        } catch (\Throwable $e) {
            Log::channel('mcp')->error('MCP tool crashed', ['tool' => $tool->name, 'error' => $e->getMessage(), 'at' => $e->getFile().':'.$e->getLine()]);

            return $this->toolError('The tool failed: '.$e->getMessage());
        } finally {
            Context::$tool = null;
        }
    }

    private function toolError(string $message, array $details = []): array
    {
        $text = $message.($details ? "\n".json_encode($details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '');

        return ['content' => [['type' => 'text', 'text' => $text]], 'isError' => true];
    }

    private function error(mixed $id, int $code, string $message): array
    {
        return ['jsonrpc' => '2.0', 'id' => $id, 'error' => ['code' => $code, 'message' => $message]];
    }

    private function log(Tool $tool, array $args, string $outcome, float $started): void
    {
        $short = array_map(fn ($v) => is_string($v) ? mb_strimwidth($v, 0, 120, '…') : (is_array($v) ? '[array '.count($v).']' : $v), $args);
        Log::channel('mcp')->info('tools/call '.$tool->name, [
            'kind' => $tool->kind, 'args' => $short, 'outcome' => $outcome,
            'ms' => (int) round((microtime(true) - $started) * 1000), 'ip' => request()->ip(),
        ]);
    }
}
