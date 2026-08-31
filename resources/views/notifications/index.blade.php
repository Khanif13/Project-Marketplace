@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="bg-[#f5f0ef] min-h-screen">
        <div class="max-w-2xl mx-auto px-6 py-8">

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-xl font-black text-[#1a0a0e]">Notifikasi</h1>
                @if ($notifications->count())
                    <form method="POST" action="{{ route('notifications.readAll') }}">
                        @csrf
                        <button type="submit" class="text-xs text-[#7D1A2E] font-semibold hover:underline">
                            Tandai semua sudah dibaca
                        </button>
                    </form>
                @endif
            </div>

            @if ($notifications->count())
                <div class="flex flex-col gap-2 mb-6">
                    @foreach ($notifications as $notif)
                        <a href="{{ route('notifications.read', $notif->id) }}"
                            class="flex items-start gap-4 bg-white border rounded-2xl px-5 py-4 hover:border-[#7D1A2E]/30 transition-all
                              {{ $notif->isUnread() ? 'border-[#7D1A2E]/20 bg-[#7D1A2E]/[0.02]' : 'border-[#ede5e6]' }}">

                            {{-- Icon --}}
                            <div
                                class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 mt-0.5
                                    {{ $notif->isUnread() ? 'bg-[#7D1A2E]/10' : 'bg-[#f5f0ef]' }}">
                                <i
                                    class="ti ti-bell text-sm {{ $notif->isUnread() ? 'text-[#7D1A2E]' : 'text-[#aaa]' }}"></i>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm {{ $notif->isUnread() ? 'font-semibold text-[#1a0a0e]' : 'text-[#555]' }} leading-snug">
                                    {{ $notif->message }}
                                </p>
                                <p class="text-xs text-[#aaa] mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>

                            {{-- Unread dot --}}
                            @if ($notif->isUnread())
                                <div class="w-2 h-2 rounded-full bg-[#7D1A2E] shrink-0 mt-2"></div>
                            @endif
                        </a>
                    @endforeach
                </div>
                {{ $notifications->links() }}
            @else
                <div class="bg-white border border-dashed border-[#ede5e6] rounded-2xl p-16 text-center">
                    <i class="ti ti-bell-off text-5xl text-[#ddd] block mb-4"></i>
                    <p class="text-sm font-semibold text-[#888]">Belum ada notifikasi</p>
                </div>
            @endif

        </div>
    </div>
@endsection
