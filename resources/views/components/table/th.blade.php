@props(['header','name'])
<div wire:click="sortBy('{{$name}}', '{{$header['sortDirection'] == 'asc' ?
                'desc':'asc' }}' )" class="cursor-pointer">
    {{$header['label']}} @if($header['sortColumnBy'] == $name) {{$header['sortDirection'] == 'asc' ?
                '↓':'↑' }} @endif
</div>