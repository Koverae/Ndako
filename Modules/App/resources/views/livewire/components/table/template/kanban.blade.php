<div>
    <!-- Kanban -->
    <div class="mt-3 container-fluid">
        <div class="row">
            @foreach($this->data() as $row)
            @foreach($this->cards() as $card)
            <x-dynamic-component
                :component="$card->component"
                :value="$card"
                :model="$row"
                :key="$row[$card->key]"
                :id="$row->id"
            >
            </x-dynamic-component>
            @endforeach
            @endforeach
        </div>
    </div>

    @if($this->data()->count() == 0)
    <div class="bg-white empty k_nocontent_help h-100">
        <img src="{{ asset('assets/images/illustrations/errors/419.svg') }}"style="height: 350px" alt="">
        <p class="empty-title">{{ $this->emptyTitle() }}</p>
        <p class="empty-subtitle">{{ $this->emptyText() }}</p>
    </div>
    @endif
</div>
