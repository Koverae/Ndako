<div>
    <style>
        /* Smooth expansion for sub-rows (keeps your classes) */
        tr.expandable-row td {
            padding-top: .75rem;
            padding-bottom: .75rem;
            background: #fff;
        }

        /* Smaller touch target for header checkbox so it doesn't "feel" heavy */
        .k-head-check { transform: scale(1.05); }

        /* Optional: faint hover to hint interactivity (kept minimal) */
        .kover-navlink:hover { background: rgba(0,0,0,.02); }
    </style>

    <div class="mb-2 table-responsive bg-white">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead class="list-table">
                <tr class="list-tr">
                    <th class="w-1">
                        <input
                            class="m-0 align-middle form-check-input k-head-check"
                            type="checkbox"
                            wire:click="toggleSelectAll"
                            @if($this->isPageFullySelected()) checked @endif
                            aria-label="Select all rows on this page">
                    </th>

                    @php
                        $currentSort = $sortBy ?? '';
                        $currentDir  = $sortDirection ?? 'asc';
                    @endphp

                    @foreach($this->columns() as $column)
                        @php
                            $isActiveSort = $currentSort === $column->key;
                            $ariaSort = $isActiveSort ? ($currentDir === 'asc' ? 'ascending' : 'descending') : 'none';
                        @endphp
                        <th
                            wire:click="sort('{{ $column->key }}')"
                            class="cursor-pointer fs-5"
                            aria-sort="{{ $ariaSort }}"
                            title="Sort by {{ $column->label }}"
                        >
                            {{ $column->label }}

                            {{-- Sort icons (kept your original icons) --}}
                            @if($isActiveSort)
                                @if ($currentDir === 'asc')
                                    <i class="bi bi-arrow-up-short"></i>
                                @else
                                    <i class="bi bi-arrow-down-short"></i>
                                @endif
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>

            {{-- Actual rows --}}
            <tbody class="bg-white">
                @foreach($this->data() as $key => $row)
                    @php
                        $expanded = in_array($row->id, $this->expandedRows);
                    @endphp
                    <tr class="cursor-pointer kover-navlink"
                        wire:key="row-{{ $row->id }}"
                        wire:click="toggleRowExpansion({{ $row->id }})"
                        aria-expanded="{{ $expanded ? 'true' : 'false' }}"
                    >
                        <td>
                            {{-- Stop propagation so the row doesn't expand when clicking the checkbox --}}
                            <input
                                class="m-0 align-middle form-check-input"
                                type="checkbox"
                                wire:model="selected.{{ $row->id }}"
                                wire:click.stop="toggleCheckbox({{ $row->id }})"
                                wire:loading.attr="disabled"
                                aria-label="Select row {{ $row->id }}"
                            >
                        </td>

                        @foreach($this->columns() as $column)
                            <td>
                                <x-dynamic-component
                                    :component="$column->component"
                                    :value="$row[$column->key]"
                                    :id="$row->id"
                                />
                            </td>
                        @endforeach
                    </tr>

                    {{-- Sub rows (kept your logic, added wire:key) --}}
                    @php
                        $hasSubData = method_exists($this, 'subData') && method_exists($this, 'subColumns');
                    @endphp

                    @if($hasSubData && $expanded)
                        <tr class="expandable-row show" wire:key="sub-{{ $row->id }}">
                            <td colspan="{{ count($this->columns()) + 1 }}">
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
                                        @foreach($this->subData($row->id) as $key => $row)
                                            <tr class="cursor-pointer kover-navlink" wire:key="subrow-{{ $row->id }}">
                                                @foreach($this->subColumns() as $column)
                                                    <td>
                                                        <x-dynamic-component
                                                            :component="$column->component"
                                                            :value="$row[$column->key]"
                                                            :id="$row->id"
                                                        />
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
        <div class="d-flex w-100 justify-content-between p-3 align-items-center">
            <div class="d-flex align-items-center gap-2">
                <label class="text-muted">Rows</label>
                <select class="form-select form-select-sm" wire:model.live="perPage" style="width:auto">
                    <option>10</option>
                    <option>20</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
            <div>{{ $this->data()->links() }}</div>
        </div>
    </div>

    @if($this->data()->count() == 0)
        <div class="bg-white empty k_nocontent_help h-100">
            <img src="{{ asset('assets/images/illustrations/errors/419.svg') }}" style="height: 350px" alt="">
            <p class="empty-title">{{ $this->emptyTitle() }}</p>
            <p class="empty-subtitle">{{ $this->emptyText() }}</p>
        </div>
    @endif
</div>
