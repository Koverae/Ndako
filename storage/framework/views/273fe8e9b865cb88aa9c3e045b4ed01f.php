<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo e(__('Booking Confirmation')); ?> · <?php echo e($company_name); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind (CDN). For server-side PDF, prefer a precompiled CSS file. -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    @page { size: A4; margin: 18mm 14mm; }
    @media print {
      html, body { -webkit-print-color-adjust: exact; print-color-adjust: exact; color-adjust: exact; }
      .shadow, .shadow-md, .shadow-lg { box-shadow: none !important; }
      .no-print { display: none !important; }
    }
    .avoid-break { break-inside: avoid; page-break-inside: avoid; }
    .stamp {
      position: absolute; right: 1rem; top: 1rem;
      transform: rotate(-10deg);
      border: 2px solid rgb(4 120 87);
      color: rgb(4 120 87);
      border-radius: .25rem;
      padding: .2rem .5rem;
      font-weight: 800; letter-spacing: .06em;
      opacity: .85;
      font-size: .75rem;
    }
  </style>
</head>
<body class="bg-white text-slate-800 antialiased">
<main class="mx-auto max-w-4xl">

  <!-- Header -->
  <section class="relative avoid-break">
    <?php if((float)($paid_amount ?? 0) >= (float)($total_amount ?? 0)): ?>
      <div class="stamp"><?php echo e(__('PAID')); ?></div>
    <?php endif; ?>

    <div class="flex items-center gap-4">
      <img src="<?php echo e(asset('assets/images/logo/ndako.png')); ?>" alt="Ndako" class="h-12 w-auto">
      <div>
        <h1 class="text-xl font-extrabold tracking-tight"><?php echo e(__('Booking Confirmation')); ?></h1>
        <p class="text-sm text-slate-500"><?php echo e($company_name); ?></p>
      </div>
      <div class="ml-auto text-right">
        <div class="text-xs text-slate-500"><?php echo e(__('Confirmation #')); ?></div>
        <div class="text-base font-bold tracking-wide"><?php echo e($reference); ?></div>
      </div>
    </div>

    <div class="mt-4 h-px bg-slate-200"></div>
  </section>

  <!-- Guest & Reservation -->
  <section class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 avoid-break">
    <!-- Guest -->
    <div class="rounded-xl border border-slate-200 p-4">
      <h2 class="text-sm font-bold tracking-wide text-slate-600"><?php echo e(__('Guest')); ?></h2>
      <div class="mt-2 space-y-1 text-sm">
        <div class="font-semibold"><?php echo e($guest_name); ?></div>
        <div class="text-slate-600"><?php echo e($guest_email); ?></div>
        <div class="text-slate-600"><?php echo e($guest_phone); ?></div>
      </div>
    </div>

    <!-- Reservation Details -->
    <div class="rounded-xl border border-slate-200 p-4">
      <h2 class="text-sm font-bold tracking-wide text-slate-600"><?php echo e(__('Reservation')); ?></h2>
      <dl class="mt-2 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
        <div>
          <dt class="text-slate-500"><?php echo e(__('Arrival')); ?></dt>
          <dd class="font-semibold">
            <?php echo e(\Carbon\Carbon::parse($check_in)->format('D, d M Y') ?? '—'); ?>

          </dd>
        </div>
        <div>
          <dt class="text-slate-500"><?php echo e(__('Departure')); ?></dt>
          <dd class="font-semibold">
            <?php echo e(\Carbon\Carbon::parse($check_out)->format('D, d M Y') ?? '—'); ?>

          </dd>
        </div>
        <div>
          <dt class="text-slate-500"><?php echo e(__('Nights')); ?></dt>
          <dd class="font-semibold">
            <?php echo e($nights); ?> <?php echo e(Str::plural('nights', $nights)); ?>

          </dd>
        </div>
        <div>
          <dt class="text-slate-500"><?php echo e(__('Guests')); ?></dt>
          <dd class="font-semibold"><?php echo e($guest_count); ?></dd>
        </div>
        <div class="col-span-2">
          <dt class="text-slate-500"><?php echo e(__('Room')); ?></dt>
          <dd class="font-semibold"><?php echo e($room); ?></dd>
        </div>
      </dl>
    </div>
  </section>

  <!-- Amounts -->
  <section class="mt-6 rounded-xl border border-slate-200 p-4 avoid-break">
    <h2 class="text-sm font-bold tracking-wide text-slate-600"><?php echo e(__('Charges Summary')); ?></h2>

    <div class="mt-3 overflow-hidden rounded-lg border border-slate-200">
      <table class="w-full text-sm">
        <tbody class="divide-y divide-slate-200">
          <tr>
            <td class="px-4 py-3 text-slate-600"><?php echo e(__('Paid Amount')); ?></td>
            <td class="px-4 py-3 text-right font-semibold"><?php echo e($paid_amount); ?></td>
          </tr>
          <tr class="bg-slate-50">
            <td class="px-4 py-3 font-semibold text-slate-800"><?php echo e(__('Total Amount')); ?></td>
            <td class="px-4 py-3 text-right font-extrabold"><?php echo e($total_amount); ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <p class="mt-4 text-sm leading-6 text-slate-700">
      <?php echo e(__('We’re delighted to confirm your booking at')); ?> <?php echo e($company_name); ?>.
      <?php echo e(__('Please review the details above and contact us with any questions.')); ?>

    </p>
  </section>

  <!-- Footer -->
  <footer class="mt-8 border-t border-slate-200 pt-3 text-xs text-slate-500">
    <div class="flex items-center justify-between">
      <span><?php echo e($company_name); ?></span>
      <span><?php echo e(__('Issued on')); ?> <?php echo e(\Carbon\Carbon::now()->format('d M Y, H:i')); ?></span>
    </div>
  </footer>
</main>
</body>
</html>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/pdf/templates/booking-confirmation.blade.php ENDPATH**/ ?>