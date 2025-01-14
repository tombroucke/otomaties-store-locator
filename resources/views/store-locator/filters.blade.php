<form class="otomaties-store-locator__filters">
  @foreach ($categories as $category)
    <div class="form-check">
      <input
        class="form-check-input"
        id="store-category-{{ $category->term_id }}"
        type="checkbox"
        name="store-category[]"
        value="{{ $category->term_id }}"
      >
      <label
        class="form-check-label"
        for="store-category-{{ $category->term_id }}"
      >
        {{ $category->name }}
      </label>
    </div>
  @endforeach
</form>
