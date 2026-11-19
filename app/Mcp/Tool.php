<?php

namespace App\Mcp;

/**
 * One MCP tool: name, description, JSON schema of its arguments, annotations and handler.
 * kind: read (readOnlyHint), write (changes content, not destructive), delete (destructiveHint).
 */
class Tool
{
    public function __construct(
        public string $name,
        public string $description,
        public array $properties,
        public array $required,
        public string $kind,
        public \Closure $handler,
        public bool $idempotent = false,
        public bool $openWorld = false,
    ) {}

    public function definition(): array
    {
        $title = ucfirst(str_replace('_', ' ', $this->name));

        return [
            'name' => $this->name,
            'title' => $title,
            'description' => $this->description,
            'inputSchema' => [
                'type' => 'object',
                'properties' => (object) $this->properties,
                'required' => $this->required,
                'additionalProperties' => false,
            ],
            'annotations' => [
                'title' => $title,
                'readOnlyHint' => $this->kind === 'read',
                'destructiveHint' => $this->kind === 'delete',
                'idempotentHint' => $this->kind === 'read' || $this->idempotent,
                'openWorldHint' => $this->openWorld,
            ],
        ];
    }
}
