@extends('layouts.app')

@section('title', 'Customer Management POS Software in UAE | MultiTech POS')
@section('description', 'Manage customer profiles, purchase history and loyalty across all your outlets with MultiTech POS customer management software for UAE businesses.')

@section('content')

@include('partials.page-hero', ['title' => 'Customers', 'image' => '/images/2025/12/freepik-export-20240628023510yeGg.jpeg', 'parent' => ['name' => 'POS Features', 'url' => '/features/']])

<section class="section section--light">
  <div class="container">
    <div class="cols cols--60-40 gap-10 m-reverse items-center justify-center p-10">
      <div class="stack gap-10 p-10">
        <h2 class="title">Multi Location  <span class="text-gradient">Integration Software In UAE
</span></h2>
        <p class="title fs-30 fw-400">All in one Point Of Sale Software</p>
        <div class="text">
          <p>It’s becoming more common practice as technology advancements facilitate managing multi locations from one centralized point in an effective way. Many businesses are now expanding their reach by opening different outlets at various locations without being restricted by geography.</p>
          <p>For these advanced operations, MultiTech Multi Location Integration Software is fully functional and can be efficiently utilized. Its powerful multi branch POS system allows seamless synchronization, giving you real-time control over inventory and sales across every outlet.</p>
        </div>
        <div class="text">
          It has pre-defined configuration modules for these kinds of businesses and comes with the essential operational option for Online / Offline mode.
        </div>
        <div class="row p-10">
          <a href="{{ $base }}/contact/" class="btn btn--sm">Contact Us</a>
        </div>
      </div>
      <div class="stack card card--round card--sh4 p-20">
        <p class="title text-center">Get Your<span class="text-gradient"> Quote Today!
</span></p>
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
              <option>POS Software</option>
              <option>POS Hardware</option>
              <option>Demo Request</option>
            </select>
          </div>
          <div class="form__field">
            <textarea name="message" rows="4" placeholder="Message"></textarea>
          </div>
          <button type="submit" class="btn btn--sm btn--block">Send</button>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <h2 class="title text-center">Salient  <span class="text-gradient">Feature
</span> </h2>
    <div class="cols cols-3">
      <div class="stack gap-10 card">
        <div class="icon-box pt-32 pr-32 pb-55 pl-32">
          <span class="icon-tile icon-tile--44 icon-tile--blue"><i class="fas fa-user"></i></span>
          <h3 class="icon-box__title">Customer Profile</h3>
          <p class="icon-box__text">You can build complete Customer profile in our POS and you can choose fields according your business need.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--44 icon-tile--green"><i class="fas fa-globe"></i></span>
          <p class="icon-box__title">Location Base Items</p>
          <p class="icon-box__text">It has been equipped with the new featre of Cutomers location based items will be displayed on handheld devices.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box pt-32 pr-32 pb-55 pl-32">
          <span class="icon-tile icon-tile--44 icon-tile--amber"><i class="fas fa-store"></i></span>
          <p class="icon-box__title">Multi Company</p>
          <p class="icon-box__text">You can manage Multi Company business management in an effective and easy way to reduce burden.</p>
        </div>
      </div>
    </div>
    <div class="cols cols-3">
      <div class="stack gap-10 card">
        <div class="icon-box pt-32 pr-32 pb-55 pl-32">
          <span class="icon-tile icon-tile--44 icon-tile--violet"><i class="fas fa-money-check-alt"></i></span>
          <p class="icon-box__title">Multi Currency</p>
          <p class="icon-box__text">We have integrated the multi currency option to facilitate the Customers, either use Rupee or other currencies.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box p-32">
          <span class="icon-tile icon-tile--44 icon-tile--rose"><i class="fas fa-unlock"></i></span>
          <p class="icon-box__title">Register &amp; Access Levels</p>
          <p class="icon-box__text">Without Security and keeping access in control you can not protect your business and data. So we have best Access &amp; Register mechanism in our POS.</p>
        </div>
      </div>
      <div class="stack gap-10 card">
        <div class="icon-box pt-32 pr-32 pb-80 pl-32">
          <span class="icon-tile icon-tile--44 icon-tile--000000"><i class="fas fa-mobile-alt"></i></span>
          <p class="icon-box__title">Mobile Reporting</p>
          <p class="icon-box__text">We have best Mobile Reporting features integrated with our software module for the sake of easiness.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <h2 class="title text-center">Explore All  <span class="text-gradient">Features
</span> 
</h2>
    <div class="cols cols-4 m-cols-2">
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--blue"><i class="fas fa-tools"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Easy to configure</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--amber"><i class="fas fa-tv"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Touch Screen Ready</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--rose"><i class="fas fa-dollar-sign"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Multi Registers</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--violet"><i class="fas fa-exchange-alt"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Customer Debit/Credit</a></p>
        </div>
      </div>
    </div>
    <div class="cols cols-4 m-cols-2">
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--amber"><i class="fas fa-lock"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Backup/Restore</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--violet"><i class="fas fa-upload"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Bulk Upload Option</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--blue"><i class="fas fa-cloud"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Barcode Printing</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--rose"><i class="fas fa-globe-americas"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Multiple Languages</a></p>
        </div>
      </div>
    </div>
    <div class="cols cols-4 m-cols-2">
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--violet"><i class="fas fa-user-plus"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Supplier Management</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--amber"><i class="fas fa-infinity"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Multiple Payment Option</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--rose"><i class="fas fa-sign-out-alt"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">User Access Levels</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--blue"><i class="fas fa-cog"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Customizable Invoice</a></p>
        </div>
      </div>
    </div>
    <div class="cols cols-4 m-cols-2">
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--blue"><i class="fas fa-user"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Employee Salary</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--rose"><i class="fas fa-exclamation-triangle"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Low Stock Alerts</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--amber"><i class="far fa-calendar-alt"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">Product Expiry Alerts</a></p>
        </div>
      </div>
      <div class="stack justify-center card">
        <div class="icon-box text-center p-32">
          <span class="icon-tile icon-tile--44 icon-tile--violet"><i class="fas fa-bullhorn"></i></span>
          <p class="icon-box__title"><a href="{{ $base }}/index.php/features/">And much more...</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section--p10">
  <div class="container">
    <div class="stack p-10">
      <div class="logo-slider" data-slider style="--show:5;--show-tablet:5;--show-mobile:1">
        <div class="logo-slider__track">
          <div class="logo-slider__item"><img src="{{ $base }}/images/elementor/thumbs/theivy-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="theivy" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/images/elementor/thumbs/bazaar-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="bazaar" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/images/elementor/thumbs/brain-games-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="brain-games" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/images/elementor/thumbs/candadian-pizza-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="candadian-pizza" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/images/elementor/thumbs/emd-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="emd" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/images/elementor/thumbs/maras-turka-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="maras-turka" loading="lazy"></div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('partials.cta-band')

@endsection
