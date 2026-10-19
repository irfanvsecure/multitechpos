{{-- Shared bits for every demo / quote form: CSRF token, hidden spam trap and validation errors --}}
@csrf
<div class="form__trap" aria-hidden="true">
    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
</div>
@if ($errors->any())
    <div class="form__errors" role="alert">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
