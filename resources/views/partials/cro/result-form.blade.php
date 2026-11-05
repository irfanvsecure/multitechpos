{{--
    "Email me this result" form for the calculator and the 30-second check.
    $service - names the widget on the lead; also tells which form failed validation
    main.js fills the hidden message with the visitor's result. The sales team sends the email.
--}}
<form class="cro-result-form {{ old('service') === $service ? 'is-failed' : '' }}" action="{{ $base }}/request-demo" method="post">
    @include('partials.form-extras')
    <input type="hidden" name="service" value="{{ $service }}">
    <input type="hidden" name="message" value="">
    <p class="cro-result-form__title"><i class="fas fa-envelope"></i> Want this result by email?</p>
    <div class="cro-result-form__fields">
        <label>
            <span>Your name</span>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Ahmed Khan" autocomplete="name" required>
        </label>
        <label>
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email" required>
        </label>
    </div>
    <button type="submit" class="btn btn--light btn--block">Email me this result <i class="fas fa-paper-plane"></i></button>
    <p class="cro-result-form__note"><i class="fas fa-lock"></i> Our team sends it to you. We only use your details for this.</p>
</form>
