{{-- Iklan Serupa --}}
@if ($relatedListings->count())
    <div>
        <x-section-title title="Iklan Serupa" />
        <div class="grid grid-cols-4 gap-3">
            @foreach ($relatedListings as $related)
                <x-listing-card :listing="$related" />
            @endforeach
        </div>
    </div>
@endif
