@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 md:mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-[#362773] tracking-tight">Beranda</h1>
            <p class="text-slate-500 mt-1 md:mt-2 font-medium">Selamat datang kembali, <span class="text-indigo-600 font-bold">{{ $student->name ?? Auth::user()->name }}</span>!</p>
        </div>
        <a href="/payment" class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 rounded-2xl font-bold text-sm md:text-base shadow-lg shadow-indigo-500/30 transition-all transform active:scale-95">
            <i class="fa-solid fa-plus"></i> Bayar SPP Sekarang
        </a>
    </div>


    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-12">

        <!-- Card 1 -->
        <a href="/history" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-blue-200 transition-all duration-300 group flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shrink-0">
                <i class="fa-solid fa-wallet text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 truncate">Total Dibayar</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 truncate">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h3>
            </div>
        </a>

        <!-- Card 2 -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 transition-all duration-300 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-emerald-50 text-emerald-600 shrink-0">
                <i class="fa-solid fa-users text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 truncate">Petugas Aktif</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 truncate">{{ $activeStaff }}</h3>
            </div>
        </div>

        <!-- Card 3 -->
        <a href="/bills" class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-indigo-200 transition-all duration-300 group flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shrink-0">
                <i class="fa-regular fa-calendar-check text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 truncate">Tagihan Saat Ini</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 truncate">Rp {{ number_format($currentBill, 0, ',', '.') }}</h3>
            </div>
        </a>

        <!-- Card 4 (Outstanding Debt) -->
        <a href="/bills" class="bg-white rounded-3xl p-6 shadow-sm border border-rose-100 hover:shadow-md hover:border-rose-200 transition-all duration-300 group flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all duration-300 shrink-0">
                <i class="fa-solid fa-circle-exclamation text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-rose-500 text-xs font-bold uppercase tracking-wider mb-1 truncate">Total Tunggakan</p>
                <h3 class="text-xl md:text-2xl font-black text-rose-600 truncate">Rp {{ number_format($totalArrears, 0, ',', '.') }}</h3>
            </div>
        </a>

    </div>


    <!-- Recent Transactions Preview -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        
        <div class="p-6 md:p-8 border-b border-slate-100 flex justify-between items-center bg-white/50 backdrop-blur-sm">
            <div>
                <h3 class="text-lg md:text-xl font-bold text-slate-800">Transaksi Terakhir</h3>
                <p class="text-slate-500 text-xs md:text-sm mt-1">Riwayat pembayaran terbaru Anda</p>
            </div>
            <a href="/history" class="text-xs md:text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors shrink-0">Lihat Semua</a>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-[11px] uppercase tracking-wider">
                        <th class="px-8 py-4 font-bold border-y border-slate-100">ID</th>
                        <th class="px-8 py-4 font-bold border-y border-slate-100">JUMLAH</th>
                        <th class="px-8 py-4 font-bold border-y border-slate-100">TAGIHAN</th>
                        <th class="px-8 py-4 font-bold border-y border-slate-100">TANGGAL</th>
                        <th class="px-8 py-4 font-bold border-y border-slate-100">STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                    @forelse($recentPayments as $p)
                        <tr class="hover:bg-slate-50/80 transition group cursor-pointer" onclick="window.location='/history'">
                            <td class="px-8 py-5 font-bold text-slate-500">#TRX-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-5 font-bold text-slate-800 text-base">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                            <td class="px-8 py-5 text-slate-600 font-medium">{{ $p->fee->name }}</td>
                            <td class="px-8 py-5 text-slate-500">{{ $p->payment_date }}</td>
                            <td class="px-8 py-5">
                                @if($p->status === 'pending')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-500 text-white shadow-sm">
                                        <i class="fa-regular fa-clock"></i> Menunggu
                                    </span>
                                @elseif($p->status === 'success')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500 text-white shadow-sm">
                                        <i class="fa-solid fa-check"></i> Berhasil
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-500 text-white shadow-sm">
                                        <i class="fa-solid fa-xmark"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-medium">
                                Belum ada riwayat transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($recentPayments as $p)
                <div class="p-5 hover:bg-slate-50 transition-colors" onclick="window.location='/history'">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">#TRX-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</p>
                            <h4 class="font-bold text-slate-800 text-lg">Rp {{ number_format($p->amount, 0, ',', '.') }}</h4>
                        </div>
                        @if($p->status === 'pending')
                            <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs shadow-sm">
                                <i class="fa-regular fa-clock"></i>
                            </span>
                        @elseif($p->status === 'success')
                            <span class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs shadow-sm">
                                <i class="fa-solid fa-check"></i>
                            </span>
                        @else
                            <span class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs shadow-sm">
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <div class="text-slate-500 font-medium">
                            <i class="fa-solid fa-file-invoice-dollar mr-1"></i> {{ $p->fee->name }}
                        </div>
                        <div class="text-slate-400 font-medium italic">
                            {{ $p->payment_date }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 font-medium text-sm">
                    Belum ada riwayat transaksi
                </div>
            @endforelse
        </div>
        
    </div>


</div>
@endsection
