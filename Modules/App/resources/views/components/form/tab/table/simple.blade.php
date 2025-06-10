@props([
    'value',
    'data'
])

<div class="table-responsive mb-3">
    <table class="table card-table table-vcenter text-nowrap datatable">
        <thead class="order-table">
            <tr class="order-tr">
                @foreach($this->columns() as $column)
                    @if($column->table === $value->key)
                    <th class="cursor-pointer fs-5 bg-white">
                        {{ $column->label }}
                    </th>
                    @endif
                @endforeach
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if($value->data)
            @foreach($value->data as $model)
            <tr class="k_field_list_row">
                @foreach($this->columns() as $column)
                    @if($column->table === $value->key)
                    <td class="k_field_list">
                        <x-dynamic-component
                            :component="$column->component"
                            :value="$model[$column->key]"
                            :id="$model->id"
                        >
                        </x-dynamic-component>
                    </td>
                    @endif
                @endforeach
                <td class="k_field_list d-flex gap-2">
                    <span wire:click.prevent="delete($model->id)" class="cursor-pointer" style="color: #017E84;" href="avoid:js">
                        <i class="bi bi-pencil-square"></i> Edit
                    </span>
                    <span wire:click.prevent="delete($model->id)" class="cursor-pointer" style="color: #017E84;" href="avoid:js">
                        <i class="bi bi-trash"></i> Remove
                    </span>
                </td>
            </tr>
            @endforeach
            @endif

            {{-- New row (temporary) --}}
            @if($this->addingRowFor === $value->key)
                <tr>
                    @foreach($this->columns() as $column)
                        @if($column->table === $value->key)
                            <td>
                                @if($column->type === 'select')
                                    <select wire:model.defer="newRow.{{ $column->key }}" class="form-control form-control-sm">
                                        <option value="">-- Select {{ $column->label }} --</option>
                                        @foreach($column->options as $option)
                                            <option value="{{ $option }}">{{ inverseSlug($option) }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input
                                        type="{{ $column->type ?? 'text' }}"
                                        wire:model.defer="newRow.{{ $column->key }}"
                                        class="form-control form-control-sm"
                                    />
                                @endif
                            </td>
                        @endif
                    @endforeach
                    <td class="d-flex gap-2">
                        <button wire:click.prevent="saveRow('{{ $value->key }}')" class="btn btn-sm btn-success">
                            <i class="bi bi-check-circle"></i> Save
                        </button>
                        <button wire:click.prevent="cancelAddRow()" class="btn btn-sm btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                    </td>
                </tr>
            @endif

            <tr class="k_field_list_row">
                @foreach($this->columns() as $column)
                    @if($column->table === $value->key)
                    <td class="k_field_list" style="height: 35px;">
                        <span class="cursor-pointer" href="avoid:js">

                        </span>
                    </td>
                    @endif
                @endforeach
                <td class="k_field_list" style="height: 46px;">
                    <span class="cursor-pointer" href="avoid:js">

                    </span>
                </td>
            </tr>
            <span class="k_field_list_row w-100">
                <td class="k_field_list">
                    <span wire:click.prevent="addRow('{{ $value->key }}')" class=" cursor-pointer" style="color: #017E84;" href="avoid:js">
                        <i class="bi bi-plus-circle"></i> Add a line
                    </span>
                </td>
                @foreach($this->columns() as $column)
                    @if($column->table === $value->key)
                    <td class="k_field_list" style="height: 35px;">
                        <span class="cursor-pointer" href="avoid:js">

                        </span>
                    </td>
                    @endif
                @endforeach
            </span>

        </tbody>
        
    </table>
    {{-- @if($value->data->count() == 0)
    <div class="bg-white empty k_nocontent_help">
        <img src="{{ asset('assets/images/illustrations/errors/missing-element.svg') }}"style="height: 350px" alt="">
        <p class="empty-title">None</p>
        <p class="empty-subtitle"></p>
    </div>
    @endif --}}
</div>
