<?php

namespace App\Mcp;

/** Checks tool arguments against the tool's JSON schema (types, required, enums, unknown keys). */
class Arguments
{
    /** @return list<string> */
    public static function check(Tool $tool, array $args): array
    {
        $problems = [];
        foreach ($tool->required as $name) {
            if (! array_key_exists($name, $args) || $args[$name] === null || $args[$name] === '') {
                $problems[] = "'$name' is required.";
            }
        }
        foreach ($args as $name => $value) {
            $schema = $tool->properties[$name] ?? null;
            if ($schema === null) {
                $problems[] = "Unknown argument '$name'. Allowed: ".implode(', ', array_keys($tool->properties)).'.';

                continue;
            }
            if ($value === null) {
                continue;
            }
            if ($p = self::type($name, $value, $schema)) {
                $problems[] = $p;
            }
        }

        return $problems;
    }

    private static function type(string $name, mixed $value, array $schema): ?string
    {
        $types = (array) ($schema['type'] ?? []);
        if ($types) {
            $ok = false;
            foreach ($types as $t) {
                $ok = $ok || match ($t) {
                    'string' => is_string($value),
                    'integer' => is_int($value) || (is_string($value) && ctype_digit($value)),
                    'number' => is_int($value) || is_float($value),
                    'boolean' => is_bool($value),
                    'array' => is_array($value) && array_is_list($value),
                    'object' => is_array($value) && ($value === [] || ! array_is_list($value)),
                    'null' => $value === null,
                    default => true,
                };
            }
            if (! $ok) {
                return "'$name' must be ".implode(' or ', $types).'.';
            }
        }
        if (isset($schema['enum']) && ! in_array($value, $schema['enum'], true)) {
            return "'$name' must be one of: ".implode(', ', array_map('json_encode', $schema['enum'])).'.';
        }

        return null;
    }
}
