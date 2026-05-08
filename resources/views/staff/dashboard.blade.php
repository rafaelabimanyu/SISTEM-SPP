@extends('layouts.staff')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-[#362773] tracking-tight">Beranda Petugas</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Pantau dan kelola pembayaran SPP siswa</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="/staff/verification" class="flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-amber-500/20 transition-all active:scale-95 shrink-0">
                <i class="fa-solid fa-hourglass-half"></i> Verifikasi Pending ({{ $pendingCount }})
            </a>
            <a href="/staff/payment" class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-500/20 transition-all active:scale-95 shrink-0">
                <i class="fa-solid fa-plus"></i> Catat Pembayaran
            </a>
        </div>
    </div>


    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8 md:mb-10">
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-blue-50 text-blue-600 shrink-0">
                <i class="fa-solid fa-wallet text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 truncate">Total Diterima (Hari Ini)</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 truncate">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        <a href="/staff/verification" class="bg-white rounded-3xl p-6 shadow-sm border border-amber-100 hover:border-amber-200 transition-all flex items-center gap-5 group">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all shrink-0">
                <i class="fa-solid fa-hourglass-half text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 truncate">Menunggu Verifikasi</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 truncate">{{ $pendingCount }}</h3>
            </div>
        </a>

        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-rose-50 text-rose-600 shrink-0">
                <i class="fa-solid fa-circle-exclamation text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1 truncate">Total Tunggakan</p>
                <h3 class="text-xl md:text-2xl font-black text-slate-800 truncate">Rp {{ number_format($totalArrears, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>


    <!-- Recent Transactions Preview -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 flex justify-between items-center bg-white/50 backdrop-blur-sm">
            <div>
                <h3 class="text-lg md:text-xl font-bold text-slate-800">Menunggu Verifikasi</h3>
                <p class="text-slate-500 text-xs md:text-sm mt-1">Daftar pembayaran yang perlu Anda tinjau</p>
            </div>
            <a href="/staff/verification" class="text-xs md:text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors shrink-0">Lihat Semua</a>
        </div>

        <div class="divide-y divide-slate-50">
            @forelse($pendingPayments as $p)
                <div class="p-5 md:px-8 md:py-6 hover:bg-slate-50 transition-colors flex justify-between items-center group cursor-pointer" onclick="window.location='/staff/verification'">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 text-base md:text-lg truncate">{{ $p->student->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $p->fee->name }}</p>
                    </div>
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="text-right">
                            <p class="font-bold text-slate-800 text-base md:text-lg tracking-tight">Rp {{ number_format($p->amount, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center text-[10px] md:text-xs font-bold text-amber-500 uppercase tracking-wider">
                                <i class="fa-solid fa-circle text-[6px] mr-1.5"></i> {{ $p->status }}
                            </span>
                        </div>
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-slate-900 group-hover:bg-indigo-600 text-white rounded-xl transition-colors flex items-center justify-center shadow-md">
                            <i class="fa-solid fa-chevron-right text-xs md:text-sm"></i>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-slate-400 font-medium text-sm md:text-base">
                    Tidak ada antrian verifikasi pembayaran
                </div>
            @endforelse
        </div>
    </div>


</div>
@endsection
