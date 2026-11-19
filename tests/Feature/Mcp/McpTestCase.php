<?php

namespace Tests\Feature\Mcp;

use Tests\Support\LoadsSiteContent;
use Tests\TestCase;

abstract class McpTestCase extends TestCase
{
    use LoadsSiteContent;

    protected string $secret = 'test_secret_0123456789abcdef0123456789abcdef0123456789abcdef0123';

    protected function setUp(): void
    {
        parent::setUp();
        config(['cms.mcp.secret' => $this->secret, 'cms.mcp.rate_limit' => 10000, 'app.url' => 'http://localhost']);
        $this->loadSiteContent();
        // the test client drops trailing slashes; page URLs are checked without that redirect
        $this->withoutMiddleware(\App\Http\Middleware\AddTrailingSlash::class);
    }

    protected function rpc(string $method, array $params = [], int $id = 1)
    {
        return $this->postJson('/mcp/'.$this->secret, ['jsonrpc' => '2.0', 'id' => $id, 'method' => $method, 'params' => $params]);
    }

    /** @return array{0: bool, 1: string} [isError, text] */
    protected function tool(string $tool, array $args = []): array
    {
        $res = $this->rpc('tools/call', ['name' => $tool, 'arguments' => (object) $args]);
        $res->assertOk();
        $result = $res->json('result');
        $this->assertNotNull($result, "$tool: ".$res->getContent());

        return [(bool) $result['isError'], (string) $result['content'][0]['text']];
    }

    protected function ok(string $tool, array $args = []): mixed
    {
        [$err, $text] = $this->tool($tool, $args);
        $this->assertFalse($err, "$tool failed: $text");

        return json_decode($text, true);
    }

    protected function refused(string $tool, array $args, string $contains = ''): string
    {
        [$err, $text] = $this->tool($tool, $args);
        $this->assertTrue($err, "$tool should have been refused but returned: ".mb_substr($text, 0, 300));
        if ($contains !== '') {
            $this->assertStringContainsStringIgnoringCase($contains, $text);
        }

        return $text;
    }

    protected function pageId(string $slug): int
    {
        return \App\Models\Page::query()->where('slug', $slug)->value('id');
    }
}
