@section('title', $this->expense->title)

<!-- Control Panel -->
@section('control-panel')
<livewire:revenuemanager::navbar.control-panel.expense-panel :expense="$expense" :isForm="true"/>
@endsection

<section class="">
    <livewire:revenuemanager::form.expense-form :expense="$expense" />
</section>
