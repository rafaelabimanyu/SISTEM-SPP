@extends('layouts.staff')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    modalOpen: false, 
    activeId: null,
    activeStudent: '', 
    activeAmount: '', 
    activeBill: '', 
    activeImg: '',
    activeNote: ''
}">
    
    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-check-circle text-xl"></i><span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-[#362773] tracking-tight">Verifikasi Pembayaran</h1>
            <p class="text-slate-500 mt-1.5 font-medium text-sm md:text-base">Tinjau dan verifikasi bukti transfer yang diunggah siswa</p>
        </div>
    </div>


    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8 md:mb-10">
        <div class="bg-white rounded-3xl p-5 border border-amber-100 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 md:w-14 md:h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl md:text-2xl shadow-sm">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">Menunggu</p>
                <p class="text-xl md:text-2xl font-black text-amber-600 tracking-tight">{{ $pendingCount }}</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 border border-emerald-100 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 md:w-14 md:h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl md:text-2xl shadow-sm">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">Selesai Hari Ini</p>
                <p class="text-xl md:text-2xl font-black text-emerald-600 tracking-tight">{{ $successCount }}</p>
            </div>
        </div>
        <div class="bg-white rounded-3xl p-5 border border-rose-100 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 md:w-14 md:h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-xl md:text-2xl shadow-sm">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div>
                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">Ditolak</p>
                <p class="text-xl md:text-2xl font-black text-rose-600 tracking-tight">{{ $rejectedCount }}</p>
            </div>
        </div>
    </div>


    <!-- Verification Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @forelse($pending as $p)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all flex flex-col group">
                <div class="p-6 md:p-8 flex-1">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-slate-50 border border-slate-100 text-indigo-600 flex items-center justify-center font-black text-lg md:text-xl shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                {{ substr($p->student->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-800 text-base md:text-lg leading-tight truncate">{{ $p->student->name }}</h3>
                                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">NISN: {{ $p->student->nisn }}</p>
                            </div>
                        </div>
                        <span class="bg-amber-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm shadow-amber-200 shrink-0">PENDING</span>
                    </div>
                    
                    <div class="bg-slate-50/50 rounded-2xl p-4 md:p-5 mb-6 border border-slate-100">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tagihan</p>
                            <p class="text-sm font-bold text-slate-800 text-right truncate pl-4">{{ $p->fee->name }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jumlah</p>
                            <p class="text-xl font-black text-indigo-600">Rp {{ number_format($p->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <button @click="modalOpen = true; 
                                   activeId = {{ $p->id }};
                                   activeStudent = '{{ addslashes($p->student->name) }} ({{ $p->student->classRoom->name }})'; 
                                   activeAmount = 'Rp {{ number_format($p->amount, 0, ',', '.') }}'; 
                                   activeBill = '{{ addslashes($p->fee->name) }}'; 
                                   activeImg = '{{ $p->proof_img }}';
                                   activeNote = '{{ addslashes($p->note) }}';" 
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-2xl transition-all flex items-center justify-center gap-3 text-sm md:text-base mb-6 shadow-lg shadow-slate-900/10 active:scale-95">
                        <i class="fa-solid fa-magnifying-glass-plus text-lg"></i> Cek Bukti Transfer
                    </button>

                    <div class="flex gap-3">
                        <form action="{{ route('staff.verification.verify', $p) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="success">
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 active:scale-95">
                                <i class="fa-solid fa-check"></i> Terima
                            </button>
                        </form>
                        <form action="{{ route('staff.verification.verify', $p) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="w-full bg-rose-50 hover:bg-rose-500 hover:text-white text-rose-600 font-bold py-3.5 rounded-xl transition-all flex items-center justify-center gap-2 border border-rose-100 active:scale-95">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-span-3 py-16 text-center text-slate-400 font-medium bg-white rounded-[2rem] border border-slate-100">
                <i class="fa-solid fa-check-double text-4xl mb-3 block text-emerald-300"></i>
                Semua pembayaran telah diverifikasi
            </div>
        @endforelse

    </div>

    <!-- Alpine JS Modal for Receipt Preview -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4 md:p-6">
        <!-- Backdrop -->
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>
        
        <!-- Modal Content -->
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-t-[2rem] sm:rounded-3xl shadow-2xl w-full max-w-5xl max-h-[95vh] sm:max-h-[90vh] flex flex-col md:flex-row overflow-hidden z-10">
            
            <!-- Mobile Handle -->
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto my-4 sm:hidden shrink-0"></div>

            <!-- Close Button (Desktop) -->
            <button @click="modalOpen = false" class="absolute top-4 right-4 z-20 w-10 h-10 bg-black/10 hover:bg-black/20 text-white rounded-xl hidden sm:flex items-center justify-center backdrop-blur transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Image Side -->
            <div class="md:w-3/5 bg-slate-900 flex items-center justify-center relative group min-h-[300px] md:min-h-0">
                <img :src="activeImg || 'https://via.placeholder.com/600x800?text=Bukti+Transfer'" class="w-full h-full object-contain">
                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                    <p class="text-white text-sm font-bold bg-black/40 px-4 py-2 rounded-full backdrop-blur-sm">Bukti Transfer Siswa</p>
                </div>
            </div>

            <!-- Details Side -->
            <div class="md:w-2/5 p-6 md:p-10 flex flex-col bg-white overflow-y-auto">
                <h2 class="text-xl md:text-2xl font-black text-slate-800 mb-2 tracking-tight">Detail Verifikasi</h2>
                <p class="text-xs md:text-sm text-slate-500 font-medium mb-8">Pastikan nominal pada struk sesuai dengan tagihan.</p>
                
                <div class="space-y-6 flex-1">
                    <div class="pb-4 border-b border-slate-50">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Nama Siswa</p>
                        <p class="text-base md:text-lg font-bold text-slate-800" x-text="activeStudent"></p>
                    </div>
                    <div class="pb-4 border-b border-slate-50">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Tagihan SPP</p>
                        <p class="text-base md:text-lg font-bold text-slate-800" x-text="activeBill"></p>
                    </div>
                    <div class="pb-4 border-b border-slate-50">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5">Nominal Transfer</p>
                        <p class="text-2xl md:text-3xl font-black text-indigo-600 tracking-tight" x-text="activeAmount"></p>
                    </div>
                    
                    <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100" x-show="activeNote">
                        <p class="text-[10px] font-bold text-amber-700 uppercase tracking-widest flex items-center gap-2 mb-1.5">
                            <i class="fa-solid fa-message"></i> Catatan Siswa
                        </p>
                        <p class="text-xs md:text-sm text-amber-900 font-medium italic leading-relaxed" x-text="activeNote"></p>
                    </div>
                </div>

                <div class="flex gap-4 mt-10 pt-6 border-t border-slate-100">
                    <form :action="'/staff/verification/' + activeId" method="POST" class="flex-1">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="success">
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 text-base md:text-lg active:scale-95">
                            <i class="fa-solid fa-check-double"></i> Terima
                        </button>
                    </form>
                    <form :action="'/staff/verification/' + activeId" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="w-14 md:w-16 h-full bg-rose-50 hover:bg-rose-500 hover:text-white text-rose-600 font-bold rounded-2xl transition-all flex items-center justify-center border border-rose-100 active:scale-95">
                            <i class="fa-solid fa-xmark text-xl md:text-2xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
