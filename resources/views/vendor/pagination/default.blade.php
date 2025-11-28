@if ($paginator->hasPages())
    <nav>
        <ul style="display: flex; list-style: none; padding: 0; gap: 0.5rem; justify-content: center; align-items: center; margin-top: 1rem;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li style="padding: 0.5rem; color: #9ca3af;" aria-disabled="true">
                    <span>« Previous</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" style="padding: 0.5rem 1rem; text-decoration: none; color: #3b82f6; border: 1px solid #3b82f6; border-radius: 0.25rem;">« Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li style="padding: 0.5rem; color: #6b7280;" aria-disabled="true">
                        <span>{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li style="padding: 0.5rem 1rem; background-color: #3b82f6; color: white; border-radius: 0.25rem;" aria-current="page">
                                <span>{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" style="padding: 0.5rem 1rem; text-decoration: none; color: #3b82f6; border: 1px solid #3b82f6; border-radius: 0.25rem;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" style="padding: 0.5rem 1rem; text-decoration: none; color: #3b82f6; border: 1px solid #3b82f6; border-radius: 0.25rem;">Next »</a>
                </li>
            @else
                <li style="padding: 0.5rem; color: #9ca3af;" aria-disabled="true">
                    <span>Next »</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

