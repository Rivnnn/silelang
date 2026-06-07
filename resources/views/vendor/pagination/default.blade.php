@if ($paginator->hasPages())
<nav class="sil-pagination" aria-label="Navigasi halaman">

    {{-- Info --}}
    <div class="sil-page-info">
        Menampilkan
        <strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong>
        dari <strong>{{ $paginator->total() }}</strong> nasabah
    </div>

    {{-- Tombol --}}
    <ul class="sil-page-list">

        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <li class="disabled">
                <span aria-disabled="true" aria-label="Halaman sebelumnya">‹</span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}"
                   rel="prev"
                   aria-label="Halaman sebelumnya">‹</a>
            </li>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)

            {{-- "..." separator --}}
            @if (is_string($element))
                <li class="disabled"><span>…</span></li>
            @endif

            {{-- Link group --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active">
                            <span aria-current="page">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}"
                   rel="next"
                   aria-label="Halaman berikutnya">›</a>
            </li>
        @else
            <li class="disabled">
                <span aria-disabled="true" aria-label="Halaman berikutnya">›</span>
            </li>
        @endif

    </ul>
</nav>

<style>
.sil-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-top: 1px solid var(--border, #E8ECF2);
    flex-wrap: wrap;
    gap: 10px;
}

.sil-page-info {
    font-size: 12.5px;
    color: var(--muted, #8A94A6);
}

.sil-page-info strong {
    color: var(--navy, #1a2e3b);
}

.sil-page-list {
    display: flex;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.sil-page-list li a,
.sil-page-list li span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 6px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border: 1.5px solid var(--border, #E8ECF2);
    color: var(--slate, #4A5568);
    text-decoration: none;
    transition: all 0.15s;
    background: #fff;
    cursor: pointer;
    font-family: var(--font, 'Segoe UI', sans-serif);
    line-height: 1;
}

.sil-page-list li a:hover {
    border-color: var(--brand, #39C6C9);
    color: var(--brand, #39C6C9);
    background: var(--brand-light, #E6F9F9);
}

.sil-page-list li.active span {
    background: var(--brand, #39C6C9);
    border-color: var(--brand, #39C6C9);
    color: #fff;
}

.sil-page-list li.disabled span {
    background: #F4F6F9;
    color: #CBD2DC;
    cursor: not-allowed;
    border-color: #E8ECF2;
}

@media (max-width: 768px) {
    .sil-pagination {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px;
    }

    .sil-page-list li a,
    .sil-page-list li span {
        min-width: 30px;
        height: 30px;
        font-size: 12px;
    }
}
</style>
@endif