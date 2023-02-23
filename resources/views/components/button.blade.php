@props([
    'href' => null,
])

<{{$href ? 'a':'button'}} {{$attributes->merge(['class'=>'flex items-center rounded p-0.5 text-center'])}}
{{$href ? 'href='.$href : ''}}>
    {{ $slot }}
</{{$href?'a':'button'}}>