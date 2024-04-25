@php 
$fonts_list = '';
@endphp

@foreach ($fonts as $font)
    @php
	$font_family_import = str_replace(' ', '+', $font->name);
    $fonts_list = $fonts_list.'&family='.$font_family_import;
    @endphp 
@endforeach

@php
    if(substr($fonts_list, 0, 8) == '&family=') $fonts_list = substr($fonts_list, 8);
    $import_url = 'https://fonts.googleapis.com/css2?family='.$fonts_list.'&display=swap';
@endphp 

<style>
    @import url('{!! $import_url !!}');
</style>