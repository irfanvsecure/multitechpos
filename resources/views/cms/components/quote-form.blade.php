{{-- "Get your quote" form. Variant "standard" (most pages) or "names" (/pos-software-uae/). Service options: form_options "quote_service". --}}
@if (($variant ?? 'standard') === 'names')
        <form class="form" action="{{ $base }}/request-demo" method="post">
          @include('partials.form-extras')
          <div class="form__field form__field--half">
            <input type="text" name="name" placeholder="First Name" required>
          </div>
          <div class="form__field">
            <textarea name="message" rows="4" placeholder="Message"></textarea>
          </div>
          <button type="submit" class="btn btn--sm btn--block">Send Message</button>
        </form>
@else
        <form class="form" action="{{ $base }}/request-demo" method="post">
          @include('partials.form-extras')
          <div class="form__field">
            <input type="text" name="name" placeholder="Name" required>
          </div>
          <div class="form__field">
            <input type="email" name="email" placeholder="Email (optional)">
          </div>
          <div class="form__field">
            <input type="tel" name="phone" placeholder="Phone / WhatsApp Number" required>
          </div>
          <div class="form__field">
            <input type="text" name="company" placeholder="Company Name">
          </div>
          <div class="form__field">
            <select name="service">
              <option value="">Interested Service</option>
              @foreach (\App\Cms\FormOptions::values('quote_service') as $option)
              <option>{{ $option }}</option>
              @endforeach
            </select>
          </div>
          <div class="form__field">
            <textarea name="message" rows="4" placeholder="Message"></textarea>
          </div>
          <button type="submit" class="btn btn--sm btn--block">Send</button>
        </form>
@endif
