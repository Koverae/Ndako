<div>
  <div class="container-fluid">
    <div class="row">
      {{-- Sidebar --}}
      <div class="flex-grow-0 flex-shrink-0 mb-5 overflow-auto bg-white d-none d-lg-block col-md-2 app-sidebar bg-view position-relative pe-1 ps-3" style="z-index:500;">
        <form action="./" method="get" autocomplete="off" novalidate class="sticky-top">
          <header class="pt-3 form-label font-weight-bold text-uppercase">
            <b><i class="bi bi-list"></i> {{ $this->headerText }}</b>
          </header>
          <ul class="mb-4 ms-2 list-unstyled" id="map-side-list">
            @foreach($this->data()->getCollection() as $row)
              @php
                $lat = $row->lat ?? $row->latitude ?? null;
                $lng = $row->lng ?? $row->longitude ?? null;
              @endphp
              <li class="py-1 pe-0 ps-0 cursor-pointer map-list-item"
                  data-id="{{ $row->id }}"
                  @if(!is_null($lat) && !is_null($lng)) data-lat="{{ $lat }}" data-lng="{{ $lng }}" @endif>
                {{ $row->name ?? ('#'.$row->id) }}
              </li>
            @endforeach
          </ul>
        </form>
      </div>

      {{-- Map --}}
      <div class="col-12 col-lg-10 p-0">
        <div id="k-map" wire:ignore.self></div>
      </div>
    </div>
  </div>

  @if($this->data()->count() == 0)
    <div class="bg-white empty k_nocontent_help h-100">
      <img src="{{ asset('assets/images/illustrations/errors/419.svg') }}" style="height:350px" alt="">
      <p class="empty-title">{{ $this->emptyTitle() }}</p>
      <p class="empty-subtitle">{{ $this->emptyText() }}</p>
    </div>
  @endif

  {{-- Height + pin styles --}}
  <style>
    /* Ensure the map always has height */
    #k-map { height: clamp(420px, 72vh, 840px); min-height: 420px; border:1px solid #e5e7eb; border-left:0; }
    @media (max-width: 991.98px){ #k-map { height: clamp(380px, 70vh, 760px); } }

    .map-list-item { border-radius:.25rem; padding:.25rem .35rem; }
    .map-list-item:hover { background:#f5f7ff; }
    .map-list-item.is-active { background:#edf0ff; font-weight:600; }

    /* Pretty pin */
    .k-pin { position:relative; width:30px; height:30px; border-radius:50%;
      background: radial-gradient(100% 100% at 30% 30%, #fff 0%, #e9e9ff 35%, #b9c6ff 60%, #6b7cff 100%);
      box-shadow: 0 6px 12px rgba(72,84,252,.28), inset 0 1px 0 rgba(255,255,255,.8);
      border:2px solid #fff;
    }
    .k-pin:after { content:""; position:absolute; left:50%; bottom:-8px; transform:translateX(-50%);
      width:0; height:0; border-left:6px solid transparent; border-right:6px solid transparent; border-top:10px solid #6b7cff;
      filter: drop-shadow(0 2px 2px rgba(0,0,0,.15));
    }
    .k-pin-inner { position:absolute; inset:6px; border-radius:50%; background:rgba(255,255,255,.9); }
  </style>

  {{-- Leaflet assets (tracked + deferred so SPA nav won’t drop them) --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" data-navigate-track defer></script>

  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js" data-navigate-track defer></script>

  @php
    $points = $this->data()->getCollection()
      ->map(function($row){
        $lat = $row->lat ?? $row->latitude ?? null;
        $lng = $row->lng ?? $row->longitude ?? null;
        if ($lat === null || $lng === null) return null;
        return [
          'id'   => $row->id,
          'name' => $row->name ?? ('#'.$row->id),
          'lat'  => (float) $lat,
          'lng'  => (float) $lng,
          'url'  => $this->showRoute($row->id) ?: null,
        ];
      })
      ->filter()
      ->values();
  @endphp

  <script>
    (function(){
      const initialCenter = [{{ $this->latitude }}, {{ $this->longitude }}];
      const points = @json($points);

      // Safe init after scripts + DOM + Livewire nav
      function ready(fn){
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
      }
      ready(ensureInit);
      window.addEventListener('load', () => setTimeout(invalidate, 50));
      document.addEventListener('livewire:init', ensureInit, { once:true });
      document.addEventListener('livewire:navigated', () => { setTimeout(ensureInit, 0); setTimeout(invalidate, 60); });

      function ensureInit(){
        if (!window.L) return;                       // Leaflet not loaded yet
        const el = document.getElementById('k-map');
        if (!el) return;                             // Container missing
        if (!window.__kMap) initMap(el);             // Create once
        updateMarkers(points);                       // Refresh markers on each render
        invalidate();                                // Fix tiles if size changed
      }

      function initMap(el){
        const map = L.map(el, { zoomControl:true, scrollWheelZoom:true });
        window.__kMap = map;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a>'
        }).addTo(map);
        map.setView([{{ $this->latitude }}, {{ $this->longitude }}], 12);

        const canCluster = !!(L && L.markerClusterGroup);
        window.__kLayer = canCluster ? L.markerClusterGroup({ showCoverageOnHover:false, maxClusterRadius:48 })
                                     : L.layerGroup();
        window.__kLayer.addTo(map);
        window.__kMarkers = new Map();

        document.addEventListener('click', (ev) => {
          const li = ev.target.closest('.map-list-item');
          if (!li) return;
          const id = li.getAttribute('data-id');
          const mk = window.__kMarkers.get(String(id));
          if (mk) {
            mk.openPopup();
            map.panTo(mk.getLatLng());
            highlightListItem(id);
          }
        });

        window.addEventListener('map-updated', (e) => {
          if (e.detail && e.detail.lat && e.detail.lng) {
            map.setView([e.detail.lat, e.detail.lng], map.getZoom());
            invalidate();
          }
        });
      }

      function updateMarkers(points){
        const map = window.__kMap, layer = window.__kLayer, markers = window.__kMarkers;
        if (!map || !layer) return;

        layer.clearLayers();
        markers.clear();

        const pinHtml = '<div class="k-pin"><div class="k-pin-inner"></div></div>';
        const icon = L.divIcon({ html:pinHtml, className:'', iconSize:[30,38], iconAnchor:[15,34], popupAnchor:[0,-32] });

        const bounds = [];
        points.forEach(p => {
          const m = L.marker([p.lat, p.lng], { icon });
          const html = `<div style="min-width:180px">
              <div class="fw-bold mb-1">${escapeHtml(p.name || '')}</div>
              ${p.url ? `<a class="text-decoration-none" href="${p.url}" target="_blank">{{ __('Open details') }} →</a>` : ''}
            </div>`;
          m.bindPopup(html);
          m.on('popupopen', () => highlightListItem(p.id));
          layer.addLayer(m);
          markers.set(String(p.id), m);
          bounds.push([p.lat, p.lng]);
        });

        if (bounds.length >= 2) map.fitBounds(bounds, { padding:[20,20] });
        else if (bounds.length === 1) map.setView(bounds[0], 14);
        else map.setView(initialCenter, 11);
      }

      function invalidate(){
        const map = window.__kMap;
        if (!map) return;
        map.invalidateSize(); // fix “map not visible” / grey tiles issues
      }

      function highlightListItem(id){
        document.querySelectorAll('.map-list-item').forEach(el => el.classList.toggle('is-active', el.getAttribute('data-id') === String(id)));
      }

      function escapeHtml(s){
        return String(s).replace(/[&<>"'`=\/]/g, t => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#47;','`':'&#96;','=':'&#61;'}[t]));
      }
    })();
  </script>
</div>
