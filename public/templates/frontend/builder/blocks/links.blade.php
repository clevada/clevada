@php
$data = block($block->id);
@endphp

@if ($data->content ?? null)
    @php
        $data = unserialize($data->content);    
    @endphp

    <div class="container-xxl">
        <div class="text-center py-4">
            <div class="row">

                @if (count($data) > 0)

                    @if($block_extra['display_style'] == 'list') <ul @if($block_extra['custom_css_class']) class="{{ $block_extra['custom_css_class'] }}" @endif>@endif

                    @foreach ($data as $item)

                        @if($block_extra['display_style'] == 'list') <li> @endif
                        
                            @if($item['icon']) {!! $item['icon'] !!} @endif
                            <a href="{{ $item['url'] }}" title="{{ $item['title'] }}">{{ $item['title'] }}</a>                                                                
																
                        @if($block_extra['display_style'] == 'list') </li> @else <span class="me-3"></span> @endif
                        
                    @endforeach

                @endif
            
			</div>
        </div>
    </div>
@endif
