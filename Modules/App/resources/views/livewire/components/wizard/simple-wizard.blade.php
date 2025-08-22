<div>
  <style>
    :root{
      --k-primary: #017e84;
      --k-primary-10: rgba(1,126,132,.10);
      --k-surface: #ffffff;
      --k-border: #e5e7eb;
      --k-text: #1f2937;
      --k-muted: #6b7280;
      --k-error: #dc2626;
    }

    .k-wizard{
      background: var(--k-surface);
      border: 1px solid var(--k-border);
      border-radius: 1rem;
      box-shadow: 0 6px 20px rgba(0,0,0,.06);
      overflow: hidden;
    }

    /* HEADER / STEPS */
    .wizard-header{
      padding: 1rem 1.25rem .75rem;
      border-bottom: 1px solid var(--k-border);
      backdrop-filter: saturate(110%);
    }

    .wizard-meta{
      display:flex; align-items:center; justify-content:space-between; gap:.75rem;
      margin-bottom:.5rem;
      color: var(--k-muted);
      font-size:.875rem;
    }
    .wizard-meta .current{
      color: var(--k-text);
      font-weight:600;
    }

    .wizard-steps{
      display:flex; gap:.75rem; overflow-x:auto; scrollbar-width:thin; padding-bottom:.25rem;
    }
    .wizard-steps::-webkit-scrollbar{height:6px}
    .wizard-steps::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:4px}

    .wizard-step{
      display:flex; align-items:center; gap:.5rem;
      background:#f3f4f6;
      border:1px solid #ececec;
      color:var(--k-text);
      padding:.5rem .75rem;
      border-radius:999px;
      white-space:nowrap;
      font-size:.875rem;
      transition:all .2s ease;
      cursor:pointer;
    }
    .wizard-step:hover{ background:#eef7f7; border-color: var(--k-primary-10) }
    .wizard-step[aria-current="step"]{
      background: var(--k-primary);
      color:#fff;
      border-color: var(--k-primary);
      box-shadow: 0 6px 16px rgba(1,126,132,.18);
    }
    .wizard-step .dot{
      display:inline-grid; place-items:center;
      width:22px; height:22px; border-radius:999px;
      background:#fff; color:var(--k-text);
      font-weight:700; font-size:.75rem;
    }
    .wizard-step[aria-current="step"] .dot{
      background:#fff; color: var(--k-primary);
    }
    .wizard-step .label{ font-weight:500 }

    /* PROGRESS BAR (thin, animated) */
    .wizard-progress{
      position:relative; height:4px; background:#eef2f7; border-radius:999px; overflow:hidden; margin-top:.5rem;
    }
    .wizard-progress .bar{
      position:absolute; top:0; left:0; height:100%;
      background: linear-gradient(90deg, var(--k-primary), #00a6ad);
      width:0%;
      transition: width .25s ease;
    }

    /* CONTENT */
    .wizard-content{
      padding: 1.25rem;
      animation: fadeIn .25s ease;
    }
    .card.active-pick{ border:2px solid var(--k-primary) }

    /* FORM */
    .form-label{ font-size:.9rem; font-weight:600; color:var(--k-text) }
    .form-control{
      border-radius:.75rem; border:1px solid #d1d5db; padding:.75rem 1rem; font-size:.95rem;
      transition: border-color .2s ease, box-shadow .2s ease;
    }
    .form-control:focus{
      border-color: var(--k-primary);
      box-shadow: 0 0 0 3px var(--k-primary-10);
    }
    .form-control.is-invalid{ border-color: var(--k-error) }
    .text-danger{ font-size:.8rem; animation: fadeIn .2s ease }

    /* MEDIA */
    .guest-card img,.guest-sidebar img{
      border-radius:.75rem; object-fit:cover;
    }
    .guest-card img{ width:120px;height:120px }
    .guest-sidebar img{ width:100%; max-height:250px }
    .room-card img{ border-radius:.75rem; object-fit:cover; width:100%; max-height:220px }

    /* FOOTER / NAV */
    .wizard-footer{
      position: sticky; bottom: 0; z-index: 10;
      border-top:1px solid var(--k-border);
      background: linear-gradient(to top, rgba(255,255,255,.96), rgba(255,255,255,.92));
      backdrop-filter: blur(6px);
      padding:.75rem 1rem;
      display:flex; align-items:center; justify-content:space-between; gap:.75rem;
    }
    .wizard-footer .btn{
      border-radius: .75rem; padding:.65rem 1rem; font-weight:600;
    }
    .btn-ghost{
      background:#f3f4f6; border:1px solid #e5e7eb; color:#374151;
    }
    .btn-ghost:hover{ background:#eef1f4 }

    @keyframes fadeIn{ from{opacity:0;transform: translateY(2px)} to{opacity:1;transform:none} }

    @media (max-width: 768px){
      .wizard-step .label{ display:none }
      .wizard-step{ padding:.4rem .55rem }
      .wizard-content{ padding: 1rem }
      .guest-card img{ width:90px;height:90px }
      .room-card img{ max-height:160px }
    }

    @media (prefers-reduced-motion: reduce){
      .wizard-content,.wizard-step,.wizard-progress .bar{ transition: none !important; animation: none !important; }
    }
  </style>

  <div class="k-wizard">
    {{-- HEADER: Steps + Progress --}}
    @php
      $totalSteps = max(1, count($this->steps()));
      $humanIndex = ($currentStep ?? 0) + 1;
      $percent = min(100, max(0, ($humanIndex / $totalSteps) * 100));
    @endphp
    <div class="wizard-header">
      <div class="wizard-meta">
        <div class="current">
          {{ __('Step') }} {{ $humanIndex }} {{ __('of') }} {{ $totalSteps }}
        </div>
        <div class="text-muted">
          {{ __('Progress') }} • {{ number_format($percent, 0) }}%
        </div>
      </div>

      @if(count($this->steps()) >= 1)
        <div class="wizard-steps" role="tablist" aria-label="{{ __('Wizard Steps') }}">
          @foreach($this->steps() as $index => $step)
            {{-- Keep your dynamic component; add accessibility props --}}
            <div class="wizard-step"
                 aria-current="{{ $index === ($currentStep ?? 0) ? 'step' : 'false' }}"
                 role="tab">
              <span class="dot">{{ $index + 1 }}</span>
              <span class="label">{{ $step->label ?? $step->name ?? __('Step').' '.($index+1) }}</span>
            </div>
          @endforeach
        </div>
        <div class="wizard-progress" aria-hidden="true">
          <div class="bar" style="width: {{ $percent }}%;"></div>
        </div>
      @endif
    </div>

    {{-- CONTENT --}}
    <div class="wizard-content">
      @foreach($this->stepPages() as $page)
        <x-dynamic-component :component="$page->component" :value="$page" />
      @endforeach
    </div>

    {{-- FOOTER / NAVIGATION --}}
    @if($showButtons)
      <div class="wizard-footer">
        <button class="btn btn-ghost"
                wire:click="goToPreviousStep"
                {{ ($currentStep ?? 0) == 0 ? 'disabled' : '' }}>
          <i class="fa fa-chevron-left me-1" aria-hidden="true"></i>{{ __('Back') }}
        </button>

        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-primary go-next"
                  wire:click="goToNextStep"
                  {{ ($currentStep ?? 0) == ($totalSteps - 1) ? 'disabled' : '' }}>
            {{ ($currentStep ?? 0) == ($totalSteps - 1) ? __('Finish') : __('Continue') }}
            <i class="fa fa-arrow-right ms-1" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    @endif
  </div>
</div>
