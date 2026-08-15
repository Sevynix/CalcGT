@extends('layouts.admin')
@section('title', 'Semua Tournament')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-4">

    {{-- ── PAGE HEADER ─────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5 mb-1">
                <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center shadow-sm">
                    <img src="{{ asset('images/items/trophy.png') }}" alt="Tournament" class="w-6 h-6 object-contain">
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tournaments</h1>
            </div>
            <p class="text-slate-500 text-sm ml-11">Kelola semua tournament dan kompetisi Growtopia.</p>
        </div>
        <a href="{{ route('admin.tournaments.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tournament Baru
        </a>
    </div>

    {{-- ── SUCCESS BANNER ──────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── EMPTY STATE ─────────────────────────────────────────────── --}}
    @if($tournaments->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="flex flex-col items-center justify-center py-20 px-8 text-center">
                <div class="w-20 h-20 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center mb-5 shadow-inner">
                    <img src="{{ asset('images/items/trophy.png') }}" alt="No tournaments" class="w-12 h-12 object-contain opacity-70">
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum ada tournament</h3>
                <p class="text-slate-500 text-sm mb-6 max-w-xs">Buat tournament pertama untuk memulai kompetisi bagi komunitas Growtopia.</p>
                <a href="{{ route('admin.tournaments.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:-translate-y-0.5 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Tournament Pertama
                </a>
            </div>
        </div>

    {{-- ── TABLE ───────────────────────────────────────────────────── --}}
    @else
        {{-- Stats row --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @php
                $total    = $tournaments->count();
                $active   = $tournaments->where('status', 'active')->count();
                $draft    = $tournaments->where('status', 'draft')->count();
                $finished = $tournaments->where('status', 'finished')->count();
            @endphp
            <div class="bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total</p>
                    <p class="text-xl font-extrabold text-slate-900">{{ $total }}</p>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Active</p>
                    <p class="text-xl font-extrabold text-slate-900">{{ $active }}</p>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Draft</p>
                    <p class="text-xl font-extrabold text-slate-900">{{ $draft }}</p>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl px-4 py-3 shadow-sm flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Finished</p>
                    <p class="text-xl font-extrabold text-slate-900">{{ $finished }}</p>
                </div>
            </div>
        </div>

        {{-- Table card --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 text-sm">Daftar Tournament ({{ $total }})</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70">
                            <th class="text-left px-6 py-3 font-bold text-slate-400 text-xs uppercase tracking-wider">Nama Tournament</th>
                            <th class="text-left px-4 py-3 font-bold text-slate-400 text-xs uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 font-bold text-slate-400 text-xs uppercase tracking-wider">Visibility</th>
                            <th class="text-left px-4 py-3 font-bold text-slate-400 text-xs uppercase tracking-wider">Event</th>
                            <th class="text-left px-4 py-3 font-bold text-slate-400 text-xs uppercase tracking-wider">Dibuat</th>
                            <th class="text-right px-6 py-3 font-bold text-slate-400 text-xs uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($tournaments as $t)
                        <tr class="hover:bg-slate-50/70 transition-colors group">

                            {{-- Name --}}
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.tournaments.show', $t) }}"
                                   class="font-bold text-slate-800 hover:text-slate-600 transition-colors group-hover:underline underline-offset-2">
                                    {{ $t->name }}
                                </a>
                                @if($t->description)
                                    <p class="text-slate-400 text-xs mt-0.5 truncate max-w-[220px]">{{ $t->description }}</p>
                                @endif
                            </td>

                            {{-- Status badge --}}
                            <td class="px-4 py-4">
                                @php
                                    $sc = [
                                        'active'     => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                        'test_drive' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
                                        'finished'   => 'bg-slate-100 text-slate-500 border border-slate-200',
                                        'draft'      => 'bg-amber-100 text-amber-700 border border-amber-200',
                                    ];
                                    $dots = ['active'=>'bg-emerald-500','test_drive'=>'bg-indigo-500','finished'=>'bg-slate-400','draft'=>'bg-amber-400'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $sc[$t->status] ?? 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dots[$t->status] ?? 'bg-slate-400' }}"></span>
                                    {{ $t->status === 'test_drive' ? 'Demo' : str_replace('_',' ',ucfirst($t->status)) }}
                                </span>
                            </td>

                            {{-- Visibility badge --}}
                            <td class="px-4 py-4">
                                @if($t->is_visible)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700 border border-sky-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Tampil
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                    Hidden
                                </span>
                            @endif
                            </td>

                            {{-- Competition count --}}
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-black text-slate-700">
                                        {{ $t->competitions_count }}
                                    </span>
                                    <span class="text-slate-500 text-xs">event</span>
                                </div>
                            </td>

                            {{-- Date --}}
                            <td class="px-4 py-4 text-slate-400 text-xs whitespace-nowrap">
                                {{ $t->created_at->format('d M Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.tournaments.show', $t) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors text-slate-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                    <form method="POST" action="{{ route('admin.tournaments.toggle-visibility', $t) }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-lg transition-colors
                                                    {{ $t->is_visible
                                                        ? 'bg-amber-100 text-amber-700 hover:bg-amber-200'
                                                        : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                                            @if($t->is_visible)
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                </svg>
                                                Sembunyikan
                                            @else
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Tampilkan
                                            @endif
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.tournaments.destroy', $t) }}" @submit.prevent="$store.modal.show('Yakin ingin menghapus tournament ini beserta semua eventnya secara permanen? Tindakan ini tidak bisa dibatalkan.', () => $el.submit(), 'Hapus Tournament?', 'Ya, Hapus', 'danger')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 transition-colors text-red-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
