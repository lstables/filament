@isset($nav)
    <nav class="mt-5 flex-1 px-2">
        @foreach ($nav as $item)
            @if (!isset($item['ability']) || auth()->user()->can($item['ability']))
                <a href="{{ Route::has($item['url']) ? route($item['url']) : $item['url'] }}" 
                    class="@unless($loop->first) mt-2 @endunless group flex items-center px-3 py-2 text-sm leading-5 font-medium rounded transition ease-in-out duration-150 @isset($item['active']) {{ active($item['active'], 'text-gray-50 bg-gray-900', 'text-gray-300') }} @else text-gray-300 @endisset focus:text-gray-50 focus:outline-none focus:bg-gray-700 hover:text-gray-50 hover:bg-gray-900"
                    @isset($item['target'])
                        target="{{ $item['target'] }}"
                        @if ($item['target'] == '_blank')
                            rel="noopener noreferrer"
                        @endif
                    @endisset
                >
                    @if (isset($item['icon']))
                        {{ Filament::icon($item['icon'], 'mr-3 h-6 w-6 text-gray-400') }}
                    @endif
                    {{ __($item['label']) }}
                </a>
            @endif
        @endforeach
    </nav>
@endisset