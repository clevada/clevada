@if ($block_extra['use_hr'] ?? null)
    <hr style="margin-top: {{ round($block_extra['height'] / 2, 0) ?? '10' }}px; margin-bottom: {{ round($block_extra['height'] / 2, 0) ?? '10' }}px; @if ($block_extra['hr_color'] ?? null) color: {{ $block_extra['hr_color'] }}@endif">
@else
    <div classs="clearfix"></div>
	<div style="margin-bottom: {{ $block_extra['height'] ?? '10' }}px"></div>
@endif
