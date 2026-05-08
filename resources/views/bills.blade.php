@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-[#362773] tracking-tight">Ringkasan Tagihan</h1>
            <p class="text-slate-500 mt-1.5 font-medium text-sm md:text-base">Kelola dan pantau pembayaran SPP Anda</p>
        </div>
        
        <div class="bg-white px-6 py-4 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 shrink-0">
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Tunggakan</p>
                <p class="text-xl md:text-2xl font-black text-rose-600">Rp {{ number_format($totalArrears, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 md:w-14 md:h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-file-invoice-dollar text-xl md:text-2xl"></i>
            </div>
        </div>
    </div>


    <!-- Outstanding Bills Section -->
    <h2 class="text-lg md:text-xl font-bold text-slate-800 mb-6 flex items-center gap-2 px-1">
        <i class="fa-solid fa-circle-exclamation text-amber-500"></i> Tagihan Belum Dibayar
    </h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-12">

        @forelse($unpaidBills as $bill)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-rose-100 transition-all hover:border-rose-200 hover:shadow-md relative overflow-hidden flex flex-col">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-rose-50 to-transparent rounded-bl-full -z-10"></div>
                
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fa-regular fa-calendar text-xl"></i>
                    </div>
                    <span class="bg-rose-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm shadow-rose-200">Belum Bayar</span>
                </div>
                
                <h3 class="text-lg md:text-xl font-black text-slate-800 mb-1">{{ $bill->name }}</h3>
                <p class="text-slate-500 text-xs md:text-sm font-medium mb-6">Silakan segera lakukan pembayaran.</p>

                
                <div class="flex items-end justify-between mt-6 pt-6 border-t border-slate-100">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Jumlah</p>
                        <p class="text-2xl font-extrabold text-slate-800">Rp {{ number_format($bill->amount, 0, ',', '.') }}</p>
                    </div>
                    <a href="/payment?fee_id={{ $bill->id }}" class="bg-slate-900 hover:bg-rose-600 text-white p-3 rounded-xl transition-colors shadow-md hover:shadow-rose-500/30">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-10 text-center text-slate-400 font-medium bg-white rounded-[2rem] border border-slate-100">
                Semua tagihan Anda telah lunas. Luar biasa!
            </div>
        @endforelse
    </div>

    <!-- Paid Bills Section -->
    <h2 class="text-lg md:text-xl font-bold text-slate-800 mb-6 flex items-center gap-2 px-1">
        <i class="fa-solid fa-check-circle text-emerald-500"></i> Riwayat Lunas
    </h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">

        @forelse($paidBills as $pb)
            <div class="bg-white/60 rounded-3xl p-6 shadow-sm border border-slate-100 opacity-80 hover:opacity-100 transition-opacity flex flex-col">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fa-regular fa-calendar-check text-xl"></i>
                    </div>
                    <span class="bg-emerald-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm">Lunas</span>
                </div>

                
                <h3 class="text-lg font-bold text-slate-700 mb-1">{{ $pb->fee->name }}</h3>
                <p class="text-slate-500 text-sm font-medium mb-4">Dibayar pada {{ \Carbon\Carbon::parse($pb->payment_date)->format('d M Y') }}</p>
                
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-xl font-extrabold text-slate-600">Rp {{ number_format($pb->amount, 0, ',', '.') }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-10 text-center text-slate-400 font-medium">
                Belum ada riwayat pembayaran lunas.
            </div>
        @endforelse
    </div>

</div>
@endsection
