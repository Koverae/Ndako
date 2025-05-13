<?php

namespace Modules\App\Traits\Table;

use Illuminate\Database\Eloquent\Builder;

trait HasCalendar{

    public $events = [];  // Array of calendar events
    public $options = []; // Custom FullCalendar.js options
}
