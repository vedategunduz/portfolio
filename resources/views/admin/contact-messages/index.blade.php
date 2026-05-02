@extends('layouts.admin')

@section('title', __('messages.admin.nav.contact_messages') . __('messages.admin.title_suffix') . config('app.name'))
@section('page-title', 'İletişim Mesajları')

@section('content')
    @if(session('success'))
        <x-admin.notice variant="success" class="mb-6">
            {{ session('success') }}
        </x-admin.notice>
    @endif

    <x-admin.card class="mb-6">
        <div class="px-4 py-3 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h2 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">{{ __('messages.filters') }}</h2>
        </div>
        <div class="p-4">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.contact-messages') }}" class="px-4 py-2 rounded-sm text-xs font-medium transition-colors {{ !request('status') ? 'bg-[#D62113] text-white' : 'border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:border-[#D62113]/50 hover:text-[#D62113]' }}">
                    {{ __('messages.message.all') }} ({{ \Modules\Contact\Models\ContactMessage::count() }})
                </a>
                <a href="{{ route('admin.contact-messages', ['status' => 'unread']) }}" class="px-4 py-2 rounded-sm text-xs font-medium transition-colors {{ request('status') == 'unread' ? 'bg-[#D62113] text-white' : 'border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:border-[#D62113]/50 hover:text-[#D62113]' }}">
                    {{ __('messages.message.unread') }} ({{ \Modules\Contact\Models\ContactMessage::where('status', 'unread')->count() }})
                </a>
                <a href="{{ route('admin.contact-messages', ['status' => 'read']) }}" class="px-4 py-2 rounded-sm text-xs font-medium transition-colors {{ request('status') == 'read' ? 'bg-[#D62113] text-white' : 'border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:border-[#D62113]/50 hover:text-[#D62113]' }}">
                    {{ __('messages.message.read') }} ({{ \Modules\Contact\Models\ContactMessage::where('status', 'read')->count() }})
                </a>
            </div>
        </div>
    </x-admin.card>

    <div class="space-y-3">
        @forelse($messages as $message)
            <x-admin.card class="p-4 {{ $message->status == 'unread' ? 'border-l-4 border-l-[#D62113]' : '' }}">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $message->name }}</h3>
                            @if($message->status == 'unread')
                                <span class="px-2 py-0.5 text-xs font-medium rounded-sm bg-[#D62113]/15 text-[#D62113] dark:bg-[#D62113]/25">{{ __('messages.message.new') }}</span>
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
                                {{ __('messages.message.mark_as_read') }}
                            </button>
                        </form>
                    @endif
                </div>
                <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]/50 p-4">
                    <p class="text-sm text-[#1b1b18] dark:text-[#EDEDEC] whitespace-pre-wrap">{{ $message->message }}</p>
                </div>
            </x-admin.card>
        @empty
            <x-admin.empty-state title="{{ __('messages.message.no_messages') }}" description="{{ __('messages.message.no_messages_desc') }}">
                <x-slot:icon><i data-lucide="inbox" class="mx-auto w-12 h-12"></i></x-slot:icon>
            </x-admin.empty-state>
        @endforelse
    </div>

    <x-admin.ui.pagination :paginator="$messages" class="mt-6" />
@endsection

@push('scripts')
    @vite('resources/js/pages/admin/contact-messages.js')
@endpush
