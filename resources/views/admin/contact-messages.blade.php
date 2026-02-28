@extends('layouts.admin')

@section('title', 'İletişim Mesajları - Admin - ' . config('app.name'))
@section('page-title', 'İletişim Mesajları')

@section('content')
    @if(session('success'))
    <div class="mb-6 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
        <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 flex space-x-4">
                <a href="{{ route('admin.contact-messages') }}" class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-blue-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                    Tümü ({{ \App\Models\ContactMessage::count() }})
                </a>
                <a href="{{ route('admin.contact-messages', ['status' => 'unread']) }}" class="px-4 py-2 rounded-lg {{ request('status') == 'unread' ? 'bg-blue-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                    Okunmamış ({{ \App\Models\ContactMessage::where('status', 'unread')->count() }})
                </a>
                <a href="{{ route('admin.contact-messages', ['status' => 'read']) }}" class="px-4 py-2 rounded-lg {{ request('status') == 'read' ? 'bg-blue-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                    Okundu ({{ \App\Models\ContactMessage::where('status', 'read')->count() }})
                </a>
            </div>

            <!-- Messages List -->
            <div class="space-y-4">
                @forelse($messages as $message)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 {{ $message->status == 'unread' ? 'border-l-4 border-blue-500' : '' }}">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $message->name }}</h3>
                                @if($message->status == 'unread')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Yeni
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <a href="mailto:{{ $message->email }}" class="hover:text-blue-600">{{ $message->email }}</a>
                                • {{ $message->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        @if($message->status == 'unread')
                        <form action="{{ route('admin.message.mark-read', $message->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition">
                                Okundu İşaretle
                            </button>
                        </form>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Mesaj bulunamadı</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Henüz hiç mesaj gelmemiş.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($messages->hasPages())
            <div class="mt-6">
                {{ $messages->links() }}
            </div>
            @endif
        </div>
@endsection
