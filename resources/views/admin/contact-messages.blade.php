@extends('layouts.admin')

@section('title', 'İletişim Mesajları - Admin - ' . config('app.name'))
@section('page-title', 'İletişim Mesajları')

@section('content')
    @if(session('success'))
        <div class="mb-6 rounded-sm border border-emerald-200 dark:border-emerald-800 bg-emerald-50/90 dark:bg-emerald-900/20 p-4">
            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('admin.contact-messages') }}" class="px-4 py-2 rounded-sm text-xs font-medium transition-colors {{ !request('status') ? 'bg-[#D62113] text-white' : 'border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:border-[#D62113]/50 hover:text-[#D62113]' }}">
            Tümü ({{ \App\Models\ContactMessage::count() }})
        </a>
        <a href="{{ route('admin.contact-messages', ['status' => 'unread']) }}" class="px-4 py-2 rounded-sm text-xs font-medium transition-colors {{ request('status') == 'unread' ? 'bg-[#D62113] text-white' : 'border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:border-[#D62113]/50 hover:text-[#D62113]' }}">
            Okunmamış ({{ \App\Models\ContactMessage::where('status', 'unread')->count() }})
        </a>
        <a href="{{ route('admin.contact-messages', ['status' => 'read']) }}" class="px-4 py-2 rounded-sm text-xs font-medium transition-colors {{ request('status') == 'read' ? 'bg-[#D62113] text-white' : 'border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:border-[#D62113]/50 hover:text-[#D62113]' }}">
            Okundu ({{ \App\Models\ContactMessage::where('status', 'read')->count() }})
        </a>
    </div>

    <!-- Messages List -->
    <div class="space-y-4">
        @forelse($messages as $message)
            <div class="rounded-sm border {{ $message->status == 'unread' ? 'border-l-4 border-l-[#D62113] border-[#e3e3e0] dark:border-[#3E3E3A]' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }} bg-white dark:bg-[#1a1a18] p-6 transition-shadow hover:shadow-lg dark:shadow-black/20">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $message->name }}</h3>
                            @if($message->status == 'unread')
                                <span class="px-2 py-0.5 text-xs font-medium rounded-sm bg-[#D62113]/15 text-[#D62113] dark:bg-[#D62113]/25">Yeni</span>
                            @endif
                        </div>
                        <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">
                            <a href="mailto:{{ $message->email }}" class="hover:text-[#D62113] transition-colors">{{ $message->email }}</a>
                            · {{ $message->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @if($message->status == 'unread')
                        <form action="{{ route('admin.message.mark-read', $message->id) }}" method="POST" class="shrink-0">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-sm text-xs font-medium bg-[#D62113] text-white hover:bg-[#b81a0f] transition-colors">
                                Okundu İşaretle
                            </button>
                        </form>
                    @endif
                </div>
                <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]/50 p-4">
                    <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] whitespace-pre-wrap">{{ $message->message }}</p>
                </div>
            </div>
        @empty
            <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-12 text-center">
                <i data-lucide="inbox" class="mx-auto w-12 h-12 text-[#706f6c] dark:text-[#8F8F8B]"></i>
                <h3 class="mt-4 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Mesaj bulunamadı</h3>
                <p class="mt-1 text-xs text-[#706f6c] dark:text-[#8F8F8B]">Henüz hiç mesaj gelmemiş.</p>
            </div>
        @endforelse
    </div>

    @if($messages->hasPages())
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    @endif
@endsection
