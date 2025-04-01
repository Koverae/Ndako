<div>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
                <!-- Side Bar -->
              <div class="flex-grow-0 flex-shrink-0 mb-5 overflow-auto bg-white d-none d-lg-block col-md-2 app-sidebar bg-view position-relative pe-1 ps-3" style="z-index: 500;">
                <form action="./" method="get" autocomplete="off" novalidate class="sticky-top">

                  <header class="pt-3 form-label font-weight-bold text-uppercase"> <b><i class="bi bi-list"></i> {{ $this->headerText }}</b></header>
                  <ul class="mb-4 ml-2">
                    @foreach($this->data() as $row)
                    <li class="text-decoration-none kover-navlink panel-category selected' py-1 pe-0 ps-0 cursor-pointer">
                      {{ $row->name }}
                    </li>
                    @endforeach
                  </ul>

                </form>
              </div>

            <!-- Map -->
            {{-- <x-maps-leaflet :centerPoint="['lat' => 52.16, 'long' => 5]"></x-maps-leaflet> --}}
            <iframe class="p-0 col-12 col-md-12 col-lg-10" height="700" src="https://www.openstreetmap.org/export/embed.html?bbox=30.673828125000004%2C-4.7078283752183046%2C42.93457031250001%2C2.141834969768584&amp;layer=mapnik&amp;marker=-1.2852925793638545%2C36.80419921875" style="border: 1px solid black"></iframe><br/>
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
