@extends('layouts.app')

@section('title', 'Multi Location POS Integration in UAE | MultiTech POS')
@section('description', 'Manage stock, sales and staff across all your outlets from one central dashboard with MultiTech multi-location POS integration for UAE businesses.')

@section('content')

@include('partials.page-hero', ['title' => 'Multi-Location Integration', 'image' => '/wp-content/uploads/2025/12/freepik-export-20240628023510yeGg.jpeg', 'parent' => ['name' => 'POS Features', 'url' => '/features/']])

<section class="section section--light">
  <div class="container">
    <div class="cols cols--60-40 gap-10 m-reverse items-center justify-center p-10">
      <div class="stack gap-10 p-10">
        <h2 class="title">Multi Location  <span class="text-gradient">Integration Software In UAE
</span></h2>
        <h3 class="title fs-30 fw-400">All in one Point Of Sale Software</h3>
        <div class="text">
          <p>It’s becoming more common practice as technology advancements facilitate managing multi locations from one centralized point in an effective way. Many businesses are now expanding their reach by opening different outlets at various locations without being restricted by geography.</p>
          <p>For these advanced operations, MultiTech Multi Location Integration Software is fully functional and can be efficiently utilized. Its powerful multi branch POS system allows seamless synchronization, giving you real-time control over inventory and sales across every outlet.</p>
        </div>
        <div class="text">
          It has pre-defined configuration modules for these kinds of businesses and comes with the essential operational option for Online / Offline mode.
        </div>
        <div class="row p-10">
          <a href="tel:+971588979925" class="btn btn--sm">Multi Company POS</a>
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
            <input type="email" name="email" placeholder="Email" required>
          </div>
          <div class="form__field">
            <input type="tel" name="phone" placeholder="Phone Number">
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

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h2 class="title">MultiTechPos <span class="text-gradient">RetailPro</span></h2>
        <div class="text">
          Set up your multitechpos Retail Point of Sale system with ease. The effortless flow makes multitechpos transactions flawless and allows you to focus on your business and customers.
        </div>
        <div class="row p-10">
          <a href="tel:+971588979925" class="btn btn--sm">Download RetailPro <i class="fas fa-arrow-down"></i></a>
          <a href="tel:+971588979925" class="btn btn--sm btn--sm-outline">How to install <i class="fas fa-video"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/01/Retail-POS.jpg" alt="" class="img r-30" width="648" height="432" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="cols cols-2 cols--reverse gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h2 class="title">MultiTechPos <span class="text-gradient">SalonPro</span></h2>
        <div class="text">
          <p>Get an immediate summary of your business across all of your locations and strategies.​​</p>
        </div>
        <div class="text">
          <p>Specially designed for salons. Scheduling, Packages, Stylist everything related to salon is covered.</p>
        </div>
        <div class="text">
          Nothing beats, when you get your business related application.
        </div>
        <div class="row p-10">
          <a href="tel:+971588979925" class="btn btn--sm">Download SalonPro <i class="fas fa-arrow-down"></i></a>
          <a href="tel:+971588979925" class="btn btn--sm btn--sm-outline">How to install <i class="fas fa-video"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/01/Benefits-of-Using-MultiTech-POS-for-Your-Salon-or-Spa.jpg" alt="" class="img r-30" width="648" height="432" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--light">
  <div class="container">
    <div class="cols cols-2 gap-10 m-reverse p-10">
      <div class="stack gap-10 justify-center p-10">
        <h2 class="title">MultiTechPos <span class="text-gradient">RestroPro</span></h2>
        <div class="text">
          Another business specific solution… multitechpos RestroPro.
        </div>
        <div class="text">
          Tablet booking for waiters and Android application for online order booking makes multitechpos Restro unique among all other applications around.
        </div>
        <div class="row p-10">
          <a href="tel:+971588979925" class="btn btn--sm">Download RestroPro <i class="fas fa-arrow-down"></i></a>
          <a href="tel:+971588979925" class="btn btn--sm btn--sm-outline">How to install <i class="fas fa-video"></i></a>
        </div>
      </div>
      <div class="stack justify-center p-10">
        <img src="{{ $base }}/wp-content/uploads/2026/01/Centralized-POS-Management-for-Multi-Branch-Businesses.jpg" alt="" class="img r-30" width="648" height="432" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section section--p10">
  <div class="container">
    <div class="stack p-10">
      <div class="logo-slider" data-slider style="--show:5;--show-tablet:5;--show-mobile:1">
        <div class="logo-slider__track">
          <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/theivy-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="theivy" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/bazaar-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="bazaar" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/brain-games-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="brain-games" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/candadian-pizza-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="candadian-pizza" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/emd-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="emd" loading="lazy"></div>
          <div class="logo-slider__item"><img src="{{ $base }}/wp-content/uploads/elementor/thumbs/maras-turka-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg" alt="maras-turka" loading="lazy"></div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('partials.cta-band')

@endsection
