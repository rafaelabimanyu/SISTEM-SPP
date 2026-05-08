@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-[#362773] tracking-tight">Beranda Admin</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Ringkasan operasional dan keuangan sekolah</p>
        </div>
        
        <div class="bg-white px-6 py-4 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 shrink-0">
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Pemasukan Bulan Ini</p>
                <p class="text-xl md:text-2xl font-black text-emerald-600 tracking-tight">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 md:w-14 md:h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-chart-line text-xl md:text-2xl"></i>
            </div>
        </div>
    </div>


    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <a href="/admin/students" class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg hover:border-indigo-200 transition-all duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Total Siswa Aktif</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $totalSiswa }}</h3>
        </a>

        <a href="/admin/students" class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg hover:border-purple-200 transition-all duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Total Kelas</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $totalKelas }}</h3>
        </a>

        <a href="/admin/users" class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg hover:border-sky-200 transition-all duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Total Petugas</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $totalPetugas }}</h3>
        </a>

        <a href="/admin/students" class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-rose-100 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
            </div>
            <p class="text-rose-500 text-sm font-bold uppercase tracking-wider mb-1">Total Tunggakan</p>
            <h3 class="text-3xl font-black text-slate-800">Rp {{ number_format($totalTunggakan / 1000000, 1) }}M</h3>
        </a>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Left Side: Recent Entries -->
        <div class="xl:col-span-2 space-y-6">
            
            <h2 class="text-2xl font-black text-[#059669] mb-2 tracking-tight">Recent Entry</h2>
            
            <!-- Search Bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Cari ID atau NISN..." class="w-full pl-10 pr-4 py-3 bg-transparent outline-none text-slate-700 font-medium placeholder:font-normal">
                </div>
                <button class="bg-indigo-900 hover:bg-indigo-800 text-white font-bold px-8 py-3 rounded-xl transition-colors shadow-md active:scale-95">
                    Search
                </button>
            </div>


            <!-- Entries List -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                
                <!-- Item 1 -->
                <div class="p-4 md:p-6 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:justify-between sm:items-center group cursor-pointer border-b border-slate-50 last:border-0 gap-4">
                    <div class="flex items-center gap-4 md:gap-5">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg md:text-xl shrink-0">
                            <i class="fa-solid fa-arrow-down-to-line"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-800 text-base md:text-lg truncate">Budi Santoso</h4>
                            <p class="text-xs md:text-sm text-slate-500 truncate">SPP April 2026 + Denda</p>
                            <p class="text-[10px] md:text-xs text-slate-400 mt-1 font-medium"><i class="fa-regular fa-clock"></i> Hari ini, 14:30 WIB</p>
                        </div>
                    </div>
                    <div class="sm:text-right pl-16 sm:pl-0">
                        <p class="text-base md:text-lg font-black text-slate-800">Rp 550,000</p>
                        <p class="text-[10px] md:text-xs font-medium text-slate-500 mt-0.5 truncate">Recorded by <span class="font-bold text-indigo-600">Pak Joko Widodo</span></p>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="p-4 md:p-6 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:justify-between sm:items-center group cursor-pointer border-b border-slate-50 last:border-0 gap-4">
                    <div class="flex items-center gap-4 md:gap-5">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg md:text-xl shrink-0">
                            <i class="fa-solid fa-arrow-down-to-line"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-800 text-base md:text-lg truncate">Ahmad Fauzi</h4>
                            <p class="text-xs md:text-sm text-slate-500 truncate">SPP Mei 2026</p>
                            <p class="text-[10px] md:text-xs text-slate-400 mt-1 font-medium"><i class="fa-regular fa-clock"></i> Hari ini, 10:15 WIB</p>
                        </div>
                    </div>
                    <div class="sm:text-right pl-16 sm:pl-0">
                        <p class="text-base md:text-lg font-black text-slate-800">Rp 500,000</p>
                        <p class="text-[10px] md:text-xs font-medium text-slate-500 mt-0.5 truncate">Recorded by <span class="font-bold text-indigo-600">Raffi Aldhan</span></p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="p-4 md:p-6 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:justify-between sm:items-center group cursor-pointer border-b border-slate-50 last:border-0 gap-4">
                    <div class="flex items-center gap-4 md:gap-5">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg md:text-xl shrink-0">
                            <i class="fa-solid fa-arrow-down-to-line"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-800 text-base md:text-lg truncate">Citra Lestari</h4>
                            <p class="text-xs md:text-sm text-slate-500 truncate">SPP Maret 2026</p>
                            <p class="text-[10px] md:text-xs text-slate-400 mt-1 font-medium"><i class="fa-regular fa-clock"></i> Kemarin, 09:00 WIB</p>
                        </div>
                    </div>
                    <div class="sm:text-right pl-16 sm:pl-0">
                        <p class="text-base md:text-lg font-black text-slate-800">Rp 500,000</p>
                        <p class="text-[10px] md:text-xs font-medium text-slate-500 mt-0.5 truncate">Recorded by <span class="font-bold text-indigo-600">Sistem (Auto)</span></p>
                    </div>
                </div>

            </div>

        </div>

        <!-- Right Side: Quick Actions -->
        <div class="xl:col-span-1 space-y-6">
            
            <h2 class="text-xl font-bold text-slate-800 mb-2">Aksi Cepat</h2>

            <a href="/admin/users" class="block bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden group shadow-lg shadow-indigo-600/20 active:scale-[0.98] transition-all">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-xl mb-6 backdrop-blur-sm border border-white/20">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h3 class="text-lg md:text-xl font-bold mb-1">Tambah Pengguna Baru</h3>
                <p class="text-indigo-100 text-xs md:text-sm">Daftarkan akun Siswa atau Petugas baru ke sistem.</p>
            </a>

            <a href="/admin/fees" class="block bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8 hover:shadow-md transition-all group active:scale-[0.98]">
                <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center text-xl mb-6 border border-slate-200 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-1">Konfigurasi SPP</h3>
                <p class="text-slate-500 text-xs md:text-sm">Atur nominal tagihan tahun ajaran baru.</p>
            </a>


        </div>

    </div>

</div>
@endsection
