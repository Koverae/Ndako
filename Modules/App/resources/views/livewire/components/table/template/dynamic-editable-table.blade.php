<div>

    <!-- Table -->
    <div class="table-responsive mb-2">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead class="list-table">
            <tr class="list-tr">
                <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                @foreach($this->columns() as $column)
                    <th wire:click="sort('{{ $column->key }}')" class="cursor-pointer">
                        {{ $column->label }}
                        <!-- Sort By -->
                        @if($sortBy === $column->key)
                          @if ($sortDirection === 'asc')
                            <i class="bi bi-arrow-up-short"></i>
                          @else
                          <i class="bi bi-arrow-down-short"></i>
                          @endif
                        @endif
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody class=" bg-white">
                @foreach($this->tables as $row)
                <tr class="cursor-pointer">
                    <td>
                        <input class="form-check-input m-0 align-middle" type="checkbox" wire:model.defer="ids.{{ $row->id }}" wire:click="toggleCheckbox({{ $row->id }})" wire:loading.attr="disabled" defer>
                    </td>
                    @foreach($this->columns() as $column)
                    <td>
                        <x-dynamic-component
                            :component="$column->component"
                            :value="$row[$column->key]"
                            :id="$row->id"
                        >
                        </x-dynamic-component>
                    </td>
                    @endforeach
                    <div class="centered-section ">

                    </div>
                </tr>
                @endforeach

            </tbody>
        </table>
        <div class="card-footer d-flex align-items-end ms-auto w-100">
            {{ $this->data()->links() }}
        </div>
    </div>
    @if(count($this->tables) == 0)
    <div class="empty k_nocontent_help bg-white">
        <img src="{{ asset('assets/images/16.svg') }}"style="height: 350px" alt="">
        <p class="empty-title">{{ $this->emptyTitle() }}</p>
        <p class="empty-subtitle">{{ $this->emptyText() }}</p>
    </div>
    @endif

</div>
