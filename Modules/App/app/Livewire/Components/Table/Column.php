<?php

namespace Modules\App\Livewire\Components\Table;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;

class Column
{
    public string $component = 'app::table.column.simple';

    public string $key;

    public string $label;

    public $table;
    public $model;
    public string $type = 'text';
    public array $options = [];

    public function __construct($key, $label, $table = null, $model = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->table = $table;
        $this->model = $model;
    }

    public static function make($key, $label, $table = null, $model = null)
    {
        return new static($key, $label, $table, $model);
    }

    public function component($component)
    {
        $this->component = $component;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function options(array $options): self
    {
        $this->options = $options;
        return $this;
    }

}
