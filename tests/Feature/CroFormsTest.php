<?php

namespace Tests\Feature;

use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class CroFormsTest extends TestCase
{
    public function test_builder_uses_defaults_without_a_failed_submit(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('name="b_branches" value="1 branch" checked', false)
            ->assertSee('name="b_counters" value="1 counter" checked', false);
    }

    public function test_builder_keeps_the_choices_after_a_failed_submit(): void
    {
        $response = $this->withSession(['_old_input' => [
            'service' => 'Setup builder: Pharmacy',
            'message' => "Price request from the setup builder\r\nBusiness: Pharmacy\r\nBranches: 6 or more\r\nCounters: 4 or more\r\nModules: Just the POS",
        ]])->get('/');

        $response->assertOk()
            ->assertSee('name="b_type" value="Pharmacy" checked', false)
            ->assertSee('name="b_branches" value="6 or more" checked', false)
            ->assertSee('name="b_counters" value="4 or more" checked', false)
            ->assertDontSee('name="b_branches" value="1 branch" checked', false);

        $this->assertDoesNotMatchRegularExpression('/name="b_modules" value="[^"]*" checked/', $response->getContent());
    }

    public function test_another_forms_failure_does_not_change_the_builder(): void
    {
        $this->withSession(['_old_input' => [
            'service' => 'Home page',
            'message' => "Branches: 6 or more",
        ]])->get('/')
            ->assertOk()
            ->assertSee('name="b_branches" value="1 branch" checked', false);
    }

    public function test_calculator_and_quiz_have_the_email_form(): void
    {
        // crc32 of the slug picks the widget. Rendered directly: the test client drops the
        // trailing slash, so a page request only gets the AddTrailingSlash redirect.
        $render = fn (string $slug) => view('partials.cro.zone', [
            'kind' => 'landing', 'slug' => $slug, 'base' => '', 'errors' => new ViewErrorBag,
        ])->render();

        $this->assertStringContainsString('value="Loss calculator result"', $render('barber-shop-pos-software'));
        $this->assertStringContainsString('value="30-second check result"', $render('bakery-pos-software'));
    }
}
