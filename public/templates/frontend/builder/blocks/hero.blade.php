@php
$block_data = block($block->id);
@endphp

@if ($block_data->content ?? null)
    @php
        $block_item = unserialize($block_data->content);
    @endphp


    @if (!($block_extra['use_image'] ?? null))

        <div class="container-xxl">
            <div class="block-hero">

                <div class="row">
                    <div class="col-md-10 offset-md-1" style="text-align: {{ $block_extra['text_align'] ?? 'left' }}">
                        <div class="block-hero-title" style="font-size: {{ $block_extra['title_font_size'] ?? '2em' }}; color: {{ $block_extra['font_color'] ?? '#000' }}">{!! $block_item['title'] !!}</div>

                        @if ($block_item['content'])<div class="block-hero-content" style="font-size: {{ $block_extra['text_font_size'] ?? '1em' }}; color: {{ $block_extra['font_color'] ?? '#000' }}">{!! $block_item['content'] !!}</div>@endif

                        @if ($block_item['btn1_label'] || $block_item['btn2_label'])
                            <div class="row justify-content-center">
                                <div class="col @if ($block_item['btn2_label']) col-12 col-12 col-md-6 col-lg-4 @else col-12 @endif">
                                    @if ($block_item['btn1_label'])
                                        <a href="{{ $block_item['btn1_url'] ?? '#' }}" class="block-hero-button1 btn btn-lg {{ $block_item['btn1_style'] }}">@if($block_item['btn1_icon']){!! $block_item['btn1_icon'] !!}@endif {{ $block_item['btn1_label'] }}</a>
                                        @if ($block_item['btn1_info'])<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn1_info'] }}</div>@endif
                                    @endif
                                </div>

                                <div class="col col-12 col-md-6 col-lg-4">
                                    @if ($block_item['btn2_label'])
                                        <a href="{{ $block_item['btn2_url'] ?? '#' }}" class="block-hero-button2 btn btn-lg {{ $block_item['btn2_style'] }}">@if($block_item['btn2_icon']){!! $block_item['btn2_icon'] !!}@endif {{ $block_item['btn2_label'] }}</a>
                                        @if ($block_item['btn2_info'])<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn2_info'] }}</div>@endif
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    @elseif ($block_extra['image_position'] == 'cover')
        <div style="display: block; justify-content: center; align-items: center; overflow: hidden; color: {{ $block_extra['font_color'] ?? null }}; background-image: @if ($block_extra['cover_dark'] ?? null) linear-gradient(rgb(0 0 0 / 50%), rgb(0 0 0 / 50%)), @endif url('{{ str_replace('\\', '/', image($block_extra['image'])) }}'); background-size: cover; @if ($block_extra['cover_fixed'] ?? null) background-attachment: fixed; @endif">

            <div class="container-xxl">
                <div class="block-hero">
                    <div class="block-hero-title" style="font-size: {{ $block_extra['title_font_size'] ?? '2em' }}">{!! $block_item['title'] !!}</div>
                    @if ($block_item['content'])<div class="block-hero-content" style="font-size: {{ $block_extra['text_font_size'] ?? '1em' }}; color: {{ $block_extra['font_color'] ?? '#000' }}">{!! $block_item['content'] !!}</div>@endif

                    @if ($block_item['btn1_label'] || $block_item['btn2_label'])
                        <div class="row justify-content-center">
                            <div class="col @if ($block_item['btn2_label']) col-12 col-12 col-md-6 col-lg-4 @else col-12 @endif">
                                @if ($block_item['btn1_label'])
                                    <a href="{{ $block_item['btn1_url'] ?? '#' }}" class="block-hero-button1 btn btn-lg {{ $block_item['btn1_style'] }}">@if($block_item['btn1_icon']){!! $block_item['btn1_icon'] !!}@endif {{ $block_item['btn1_label'] }}</a>
                                    @if ($block_item['btn1_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn1_info'] }}</div>@endif
                                @endif
                            </div>

                            <div class="col col-12 col-md-6 col-lg-4">
                                @if ($block_item['btn2_label'])
                                    <a href="{{ $block_item['btn2_url'] ?? '#' }}" class="block-hero-button2 btn btn-lg {{ $block_item['btn2_style'] }}">@if($block_item['btn2_icon']){!! $block_item['btn2_icon'] !!}@endif {{ $block_item['btn2_label'] }}</a>
                                    @if ($block_item['btn2_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn2_info'] }}</div>@endif
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>

    @elseif ($block_extra['image_position'] == 'top')
        <div class="block-hero" style="color: {{ $block_extra['font_color'] }}">

            <div class="container-xxl">
                @if ($block_extra['image'])
                    <div class="row">
                        <div class="{{ $block_extra['img_container_width'] ?? 'col-12' }}">

                            @if ($block_extra['img_click'] ?? null)
                                <a data-fancybox="gallery-{{ $block->id }}" class="gallery" href="{{ image($block_extra['image']) }}">
                                    <img src="{{ image($block_extra['image']) }}" class="block-hero-img img-fluid @if ($block_extra['shaddow'] ?? null) shadow @endif" alt="{{ $block_item['title'] }}">
                                </a>
                            @else
                                <img src="{{ image($block_extra['image']) }}" class="block-hero-img img-fluid @if ($block_extra['shaddow'] ?? null) shadow @endif" alt="{{ $block_item['title'] }}">
                            @endif
                        </div>
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12" style="text-align: {{ $block_extra['text_align'] ?? 'left' }}">
                        <div class="block-hero-title" style="font-size: {{ $block_extra['title_font_size'] ?? '2em' }}">{!! $block_item['title'] !!}</div>

                        @if ($block_item['content'])<div class="block-hero-content" style="font-size: {{ $block_extra['text_font_size'] ?? '1em' }}; color: {{ $block_extra['font_color'] ?? '#000' }}">{!! $block_item['content'] !!}</div>@endif

                        @if ($block_item['btn1_label'] || $block_item['btn2_label'])
                            <div class="row justify-content-center">
                                <div class="col @if ($block_item['btn2_label']) col-12 col-12 col-md-6 col-lg-4 @else col-12 @endif">
                                    @if ($block_item['btn1_label'])
                                        <a href="{{ $block_item['btn1_url'] ?? '#' }}" class="block-hero-button1 btn btn-lg {{ $block_item['btn1_style'] }}">@if($block_item['btn1_icon']){!! $block_item['btn1_icon'] !!}@endif {{ $block_item['btn1_label'] }}</a>
                                        @if ($block_item['btn1_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn1_info'] }}</div>@endif
                                    @endif
                                </div>

                                <div class="col col-12 col-md-6 col-lg-4">
                                    @if ($block_item['btn2_label'])
                                        <a href="{{ $block_item['btn2_url'] ?? '#' }}" class="block-hero-button2 btn btn-lg {{ $block_item['btn2_style'] }}">@if($block_item['btn2_icon']){!! $block_item['btn2_icon'] !!}@endif {{ $block_item['btn2_label'] }}</a>
                                        @if ($block_item['btn2_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn2_info'] }}</div>@endif
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>

    @elseif ($block_extra['image_position'] == 'bottom')
        <div class="block-hero" style="color: {{ $block_extra['font_color'] }}">

            <div class="container-xxl">
                <div class="row">
                    <div class="col-12" style="text-align: {{ $block_extra['text_align'] ?? 'left' }}">
                        <div class="block-hero-title" style="font-size: {{ $block_extra['title_font_size'] ?? '2em' }}">{!! $block_item['title'] !!}</div>

                        @if ($block_item['content'])<div class="block-hero-content" style="font-size: {{ $block_extra['text_font_size'] ?? '1em' }}; color: {{ $block_extra['font_color'] ?? '#000' }}">{!! $block_item['content'] !!}</div>@endif

                        @if ($block_item['btn1_label'] || $block_item['btn2_label'])
                            <div class="row justify-content-center">
                                <div class="col @if ($block_item['btn2_label']) col-12 col-12 col-md-6 col-lg-4 @else col-12 @endif">
                                    @if ($block_item['btn1_label'])
                                        <a href="{{ $block_item['btn1_url'] ?? '#' }}" class="block-hero-button1 btn btn-lg {{ $block_item['btn1_style'] }}">@if($block_item['btn1_icon']){!! $block_item['btn1_icon'] !!}@endif {{ $block_item['btn1_label'] }}</a>
                                        @if ($block_item['btn1_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn1_info'] }}</div>@endif
                                    @endif
                                </div>

                                <div class="col col-12 col-md-6 col-lg-4">
                                    @if ($block_item['btn2_label'] ?? null)
                                        <a href="{{ $block_item['btn2_url'] ?? '#' }}" class="block-hero-button2 btn btn-lg {{ $block_item['btn2_style'] }}">@if($block_item['btn2_icon']){!! $block_item['btn2_icon'] !!}@endif {{ $block_item['btn2_label'] }}</a>
                                        @if ($block_item['btn2_info'])<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn2_info'] }}</div>@endif
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                @if ($block_extra['image'])
                    <div class="row mt-4">
                        <div class="{{ $block_extra['img_container_width'] ?? 'col-12' }}">

                            @if ($block_extra['img_click'] ?? null)
                                <a data-fancybox="gallery-{{ $block->id }}" class="gallery" href="{{ image($block_extra['image']) }}">
                                    <img src="{{ image($block_extra['image']) }}" class="block-hero-img img-fluid @if ($block_extra['shaddow'] ?? null) shadow @endif" alt="{{ $block_item['title'] }}">
                                </a>
                            @else
                                <img src="{{ image($block_extra['image']) }}" class="block-hero-img img-fluid @if ($block_extra['shaddow'] ?? null) shadow @endif" alt="{{ $block_item['title'] }}">
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @elseif ($block_extra['image_position'] == 'left')
        <div class="block-hero" style="color: {{ $block_extra['font_color'] }}; text-align: {{ $block_extra['text_align'] ?? 'left' }}">

            <div class="container-xxl">
                <div class="row">
                    <div class="col-12 col-lg-5 col-md-6 d-none d-md-block">
                        @if ($block_extra['image'])
                            @if ($block_extra['img_click'] ?? null)
                                <a data-fancybox="gallery-{{ $block->id }}" class="gallery" href="{{ image($block_extra['image']) }}">
                                    <img src="{{ image($block_extra['image']) }}" class="block-hero-img img-fluid float-start @if ($block_extra['shaddow'] ?? null) shadow @endif" alt="{{ $block_item['title'] }}">
                                </a>
                            @else
                                <img src="{{ image($block_extra['image']) }}" class="block-hero-img img-fluid float-start @if ($block_extra['shaddow'] ?? null) shadow @endif" alt="{{ $block_item['title'] }}">
                            @endif
                        @endif
                    </div>

                    <div class="col-12 col-lg-7 col-md-6">
                        <div class="block-hero-title" style="font-size: {{ $block_extra['title_font_size'] ?? '2em' }}">{!! $block_item['title'] !!}</div>

                        @if ($block_item['content'])<div class="block-hero-content" style="font-size: {{ $block_extra['text_font_size'] ?? '1em' }}; color: {{ $block_extra['font_color'] ?? '#000' }}">{!! $block_item['content'] !!}</div>@endif

                        @if ($block_item['btn1_label'] || $block_item['btn2_label'])
                            <div class="row @if(($block_extra['text_align'] ?? null) == 'center') justify-content-center @endif">
                                <div class="col @if ($block_item['btn2_label']) col-12 col-12 col-md-6 col-lg-4 @else col-12 @endif">
                                    @if ($block_item['btn1_label'])
                                        <a href="{{ $block_item['btn1_url'] ?? '#' }}" class="block-hero-button1 btn btn-lg {{ $block_item['btn1_style'] }}">@if($block_item['btn1_icon']){!! $block_item['btn1_icon'] !!}@endif {{ $block_item['btn1_label'] }}</a>
                                        @if ($block_item['btn1_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn1_info'] }}</div>@endif
                                    @endif
                                </div>

                                <div class="col col-12 col-md-6 col-lg-4">
                                    @if ($block_item['btn2_label'])
                                        <a href="{{ $block_item['btn2_url'] ?? '#' }}" class="block-hero-button2 btn btn-lg {{ $block_item['btn2_style'] }}">@if($block_item['btn2_icon']){!! $block_item['btn2_icon'] !!}@endif {{ $block_item['btn2_label'] }}</a>
                                        @if ($block_item['btn2_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn2_info'] }}</div>@endif
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>


    @elseif ($block_extra['image_position'] == 'right')
        <div class="block-hero" style="color: {{ $block_extra['font_color'] }}; text-align: {{ $block_extra['text_align'] ?? 'left' }}">

            <div class="container-xxl">
                <div class="row">
                    <div class="col-12 col-lg-7 col-md-6">
                        <div class="block-hero-title" style="font-size: {{ $block_extra['title_font_size'] ?? '2em' }}">{!! $block_item['title'] !!}</div>

                        @if ($block_item['content'])<div class="block-hero-content" style="font-size: {{ $block_extra['text_font_size'] ?? '1em' }}; color: {{ $block_extra['font_color'] ?? '#000' }}">{!! $block_item['content'] !!}</div>@endif

                        @if ($block_item['btn1_label'] || $block_item['btn2_label'])
                            <div class="row @if(($block_extra['text_align'] ?? null) == 'center') justify-content-center @endif">
                                <div class="col @if ($block_item['btn2_label']) col-12 col-12 col-md-6 col-lg-4 @else col-12 @endif">
                                    @if ($block_item['btn1_label'])
                                        <a href="{{ $block_item['btn1_url'] ?? '#' }}" class="block-hero-button1 btn btn-lg {{ $block_item['btn1_style'] }}">@if($block_item['btn1_icon']){!! $block_item['btn1_icon'] !!} @endif {{ $block_item['btn1_label'] }}</a>
                                        @if ($block_item['btn1_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn1_info'] }}</div>@endif
                                    @endif
                                </div>

                                <div class="col col-12 col-md-6 col-lg-4">
                                    @if ($block_item['btn2_label'])
                                        <a href="{{ $block_item['btn2_url'] ?? '#' }}" class="block-hero-button2 btn btn-lg {{ $block_item['btn2_style'] }}">@if($block_item['btn2_icon']){!! $block_item['btn2_icon'] !!}@endif {{ $block_item['btn2_label'] }}</a>
                                        @if ($block_item['btn2_info'] ?? null)<div class="block-hero-buttons-caption text-center mb-2 mb-md-0">{{ $block_item['btn2_info'] }}</div>@endif
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="col-12 col-lg-5 col-md-6 d-none d-md-block">
                        @if ($block_extra['image'])
                            @if ($block_extra['img_click'] ?? null)
                                <a data-fancybox="gallery-{{ $block->id }}" class="gallery" href="{{ image($block_extra['image']) }}">
                                    <img src="{{ image($block_extra['image']) }}" class="block-hero-img img-fluid float-end @if ($block_extra['shaddow'] ?? null) shadow @endif" alt="{{ $block_item['title'] }}">
                                </a>
                            @else
                                <img src="{{ image($block_extra['image']) }}" class="block-hero-img img-fluid float-end @if ($block_extra['shaddow'] ?? null) shadow @endif" alt="{{ $block_item['title'] }}">
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>

    @endif

@endif
