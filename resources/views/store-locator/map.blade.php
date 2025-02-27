<div class="otomaties-store-locator__map">
  @foreach ($stores as $store)
    <div
      class="store"
      data-categories="{{ $store->categories()->map(fn($category) => $category->term_id)->implode(',') }}"
      data-lat="{{ $store->location()->lat() }}"
      data-lng="{{ $store->location()->lng() }}"
      data-title="{{ $store->title() }}"
      {!! $store->marker() ? 'data-marker="' . $store->marker() . '"' : '' !!}
    >
      <div class="store__content">
        <h4 class="store__title"><a href="{{ $store->url() }}">{{ $store->title() }}</a></h4>
        {!! $store->markerContent() !!}
        <a
          class="{{ apply_filters('otomaties/store-locator/store_link_class', 'store__link', $store) }}"
          href="{{ $store->url() }}"
        >
          {{ __('More info', 'otomaties-store-locator') }}
        </a>
        <a
          class="{{ apply_filters('otomaties/store-locator/navigate_link_class', 'store__link', $store) }}"
          href="https://www.google.com/maps/dir/?api=1&destination={{ $store->location()->lat() }}, {{ $store->location()->lng() }}"
          target="_blank"
        >
          {{ __('Navigate', 'otomaties-store-locator') }}
        </a>
      </div>
    </div>
  @endforeach
</div>
