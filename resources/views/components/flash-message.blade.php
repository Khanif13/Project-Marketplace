@if (session('success'))
    <div class="bg-green-900/40 border border-green-500/20 text-green-300 text-sm px-6 py-3 flex items-center gap-2">
        <i class="ti ti-circle-check"></i> {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="bg-red-900/40 border border-red-500/20 text-red-300 text-sm px-6 py-3 flex items-center gap-2">
        <i class="ti ti-circle-x"></i> {{ session('error') }}
    </div>
@endif
