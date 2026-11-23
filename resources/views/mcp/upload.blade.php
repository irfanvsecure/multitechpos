<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Upload an image · MultiTech POS</title>
<style>
  :root { --bg:#f4f6fa; --card:#fff; --text:#16202e; --muted:#5b6778; --line:#dbe1ea; --accent:#1f5fd1; --ok:#137a43; --okbg:#e7f6ee; --err:#b42318; --errbg:#fdecea; }
  @media (prefers-color-scheme: dark) { :root { --bg:#0f141b; --card:#171e28; --text:#e7ecf3; --muted:#9aa6b6; --line:#2a3443; --accent:#5b93f0; --ok:#5fd394; --okbg:#12301f; --err:#ff8a80; --errbg:#3a1714; } }
  * { box-sizing:border-box; }
  body { margin:0; background:var(--bg); color:var(--text); font:16px/1.5 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; }
  main { max-width:560px; margin:40px auto; padding:0 16px; }
  .card { background:var(--card); border:1px solid var(--line); border-radius:14px; padding:24px; }
  h1 { font-size:1.3rem; margin:0 0 6px; }
  p { margin:0 0 14px; color:var(--muted); }
  .purpose { color:var(--text); background:var(--bg); border-radius:8px; padding:10px 12px; }
  label { display:block; font-weight:600; margin:16px 0 6px; }
  input[type=text] { width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:8px; background:var(--card); color:var(--text); font:inherit; }
  .drop { display:block; border:2px dashed var(--line); border-radius:12px; padding:22px; text-align:center; cursor:pointer; color:var(--muted); }
  .drop input { display:block; margin:10px auto 0; max-width:100%; color:var(--text); }
  button { margin-top:18px; width:100%; padding:12px; border:0; border-radius:10px; background:var(--accent); color:#fff; font:600 1rem system-ui,sans-serif; cursor:pointer; }
  button[disabled] { opacity:.6; cursor:wait; }
  .msg { border-radius:8px; padding:10px 12px; margin:0 0 14px; }
  .ok { background:var(--okbg); color:var(--ok); }
  .err { background:var(--errbg); color:var(--err); }
  .err ul { margin:0; padding-left:18px; }
  .thumbs { display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:10px; margin-top:12px; }
  .thumbs img { width:100%; height:auto; border-radius:8px; border:1px solid var(--line); display:block; }
  .thumbs small { display:block; color:var(--muted); word-break:break-all; margin-top:4px; }
</style>
</head>
<body>
<main>
  <div class="card">
    <h1>Upload an image to multitechpos.com</h1>

    @if ($problems)
      <div class="msg err"><ul>@foreach ($problems as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    @if ($state === 'missing')
      <p>This upload link does not exist. Ask Claude for a new one.</p>
    @elseif ($state === 'expired')
      <p>This upload link has expired. Ask Claude for a new one.</p>
    @elseif ($state === 'done')
      @if ($link->replace_path && ! $link->replace_error)
        <div class="msg ok">Done. The new image is now on the website. Go back to Claude and say "uploaded".</div>
      @else
        <div class="msg ok">Done. The image is in the website's media library. Go back to Claude and say "uploaded".</div>
      @endif
    @else
      @if ($link->purpose)
        <p class="purpose">{{ $link->purpose }}</p>
      @endif
      @if ($images->count())
        <div class="msg ok">{{ $images->count() }} uploaded. You can add {{ $link->remaining() }} more, or go back to Claude.</div>
      @endif
      <p>Choose the image (JPEG, PNG, GIF or WebP, up to {{ ini_get('upload_max_filesize') }}). It is converted to WebP for the website. Link valid until {{ $link->expires_at->timezone('Asia/Dubai')->format('j M Y, H:i') }} (UAE time).</p>
      <form method="post" enctype="multipart/form-data" onsubmit="this.querySelector('button').disabled=true;this.querySelector('button').textContent='Uploading…'">
        <label class="drop">
          {{ $link->remaining() > 1 ? 'Choose up to '.$link->remaining().' images' : 'Choose an image' }}
          <input type="file" name="images[]" accept="image/jpeg,image/png,image/gif,image/webp" required @if ($link->remaining() > 1) multiple @endif>
        </label>
        <label for="alt">What does the image show? (alt text)</label>
        <input type="text" id="alt" name="alt" maxlength="250" value="{{ $link->alt }}" placeholder="e.g. Cashier using a POS system in a Dubai shop">
        <button type="submit">Upload</button>
      </form>
    @endif

    @if ($images->count())
      <div class="thumbs">
        @foreach ($images as $m)
          <div><img src="{{ $m->variants['480'] ?? $m->path }}" alt="{{ $m->alt }}"><small>{{ $m->path }}</small></div>
        @endforeach
      </div>
    @endif
  </div>
</main>
</body>
</html>
