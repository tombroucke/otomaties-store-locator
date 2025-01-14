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
        <h4 class="store__title">{{ $store->title() }}</h4>
        {!! $store->markerContent() !!}
      </div>
    </div>
  @endforeach
</div>
