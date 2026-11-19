<?php

namespace App\Mcp;

/** A refusal or validation problem, shown to Claude as a tool error (isError: true). */
class ToolError extends \RuntimeException
{
    public function __construct(string $message, public array $details = [])
    {
        parent::__construct($message);
    }
}
