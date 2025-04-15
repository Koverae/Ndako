<div>
    <!-- Table -->
    <div class="mb-2 table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead class="list-table">
                <tr class="list-tr">
                    <th class="w-1">

                    <input class="m-0 align-middle form-check-input" type="checkbox" wire:click="$toggle('selectAll')" aria-label="Select all invoices"></th>
                    @foreach($this->columns() as $column)
                        <th wire:click="sort('{{ $column->key }}')" class="cursor-pointer fs-5">
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

            <tbody class="bg-white ">
                @foreach($this->data() as  $key => $row)
                @php
                    $expand = in_array($row->id, $this->expandedRows) ? 'Collapse' : 'Expand';
                @endphp
                <tr class="cursor-pointer kover-navlink" wire:click="toggleRowExpansion({{ $row->id }})">
                    <td>
                        <input class="m-0 align-middle form-check-input" type="checkbox" wire:model="selected.{{ $row->id }}" wire:click="toggleCheckbox({{ $row->id }})" wire:loading.attr="disabled" defer>
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
                {{-- @php
                    $subData = method_exists($this, 'subData') ? $this->subData() : null;
                @endphp --}}

                @php
                    $hasSubData = method_exists($this, 'subData') && method_exists($this, 'subColumns');
                @endphp
                @if($hasSubData && in_array($row->id, $this->expandedRows))
                <tr class="expandable-row show" colspan="6">
                    <td colspan="6">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead class="list-table">
                                <tr>
                                    @foreach($this->subColumns() as $column)
                                    <th class="cursor-pointer fs-5" colspan="auto">
                                        {{ $column->label }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($this->subData($row->id) as  $key => $row)
                                <tr class="cursor-pointer kover-navlink">
                                    @foreach($this->subColumns() as $column)
                                    <td>
                                        <x-dynamic-component
                                            :component="$column->component"
                                            :value="$row[$column->key]"
                                            :id="$row->id"
                                        >
                                        </x-dynamic-component>
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
                @endforeach

            </tbody>
        </table>
        <div class="card-footer d-flex align-items-end ms-auto w-100">
            {{ $this->data()->links() }}
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
