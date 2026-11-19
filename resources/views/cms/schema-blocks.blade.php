{{-- Extra JSON-LD blocks saved through the MCP (site-wide + this page). Expects $schemaBlocks. --}}
@foreach ($schemaBlocks as $block)
    @push('schema')
<script type="application/ld+json">{!! \App\Cms\Schema::encode($block->data) !!}</script>
    @endpush
@endforeach
