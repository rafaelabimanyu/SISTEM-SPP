@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{
    detailOpen: false,
    detail: {},
    openDetail(data) { this.detail = data; this.detailOpen = true; }
}">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6 px-4 md:px-0">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-[#362773] tracking-tight">Riwayat Transaksi</h1>
            <p class="text-slate-500 mt-1.5 font-medium text-sm md:text-base">Lihat semua pembayaran lalu dan status verifikasinya</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <form action="{{ route('student.history') }}" method="GET" class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari transaksi..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 transition-all shadow-sm">
            </form>
            <a href="/payment" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fa-solid fa-plus"></i> Bayar Baru
            </a>
        </div>
    </div>


    <!-- Main Content Area -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mx-4 md:mx-0">
        
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-[11px] uppercase tracking-wider">
                        <th class="px-8 py-5 font-bold border-b border-slate-100">ID</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100">DESKRIPSI</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100">TANGGAL</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100">JUMLAH</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100">STATUS</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                    @forelse($payments as $p)
                        <tr class="hover:bg-slate-50/80 transition group">
                            <td class="px-8 py-6 font-bold text-slate-500">#TRX-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-slate-800">{{ $p->fee->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Method: {{ $p->proof_img ? 'Transfer' : 'Cash' }}</p>
                            </td>
                            <td class="px-8 py-6 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y') }}<br>
                                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($p->payment_date)->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-8 py-6 font-black text-slate-800 text-base">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                            <td class="px-8 py-6">
                                @if($p->status === 'pending')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-sm"><i class="fa-solid fa-hourglass-half"></i> PENDING</span>
                                @elseif($p->status === 'success')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow-sm"><i class="fa-solid fa-check"></i> SUCCESS</span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[10px] font-bold bg-rose-500 text-white shadow-sm"><i class="fa-solid fa-xmark"></i> FAILED</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <button @click="openDetail({
                                    id: '#TRX-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}',
                                    desc: '{{ addslashes($p->fee->name) }}',
                                    bank: '{{ $p->proof_img ? 'Transfer Proof Uploaded' : 'Tunai' }}',
                                    date: '{{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y, H:i') }} WIB',
                                    amount: 'Rp {{ number_format($p->amount, 0, ',', '.') }}',
                                    status: '{{ strtoupper($p->status) }}',
                                    statusColor: '{{ $p->status === 'pending' ? 'amber' : ($p->status === 'success' ? 'emerald' : 'rose') }}',
                                    note: '{{ addslashes($p->note) }}'
                                })" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm flex items-center justify-center mx-auto">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-slate-400 font-medium">
                                Belum ada riwayat transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($payments as $p)
                <div class="p-5 hover:bg-slate-50 transition-colors" @click="openDetail({
                                    id: '#TRX-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}',
                                    desc: '{{ addslashes($p->fee->name) }}',
                                    bank: '{{ $p->proof_img ? 'Transfer Proof Uploaded' : 'Tunai' }}',
                                    date: '{{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y, H:i') }} WIB',
                                    amount: 'Rp {{ number_format($p->amount, 0, ',', '.') }}',
                                    status: '{{ strtoupper($p->status) }}',
                                    statusColor: '{{ $p->status === 'pending' ? 'amber' : ($p->status === 'success' ? 'emerald' : 'rose') }}',
                                    note: '{{ addslashes($p->note) }}'
                                })">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">#TRX-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</p>
                            <h4 class="font-bold text-slate-800 text-base">{{ $p->fee->name }}</h4>
                        </div>
                        @if($p->status === 'pending')
                            <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] shadow-sm"><i class="fa-solid fa-hourglass-half"></i></span>
                        @elseif($p->status === 'success')
                            <span class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] shadow-sm"><i class="fa-solid fa-check"></i></span>
                        @else
                            <span class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] shadow-sm"><i class="fa-solid fa-xmark"></i></span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-sm font-black text-slate-800">
                            Rp {{ number_format($p->amount, 0, ',', '.') }}
                        </div>
                        <div class="text-[10px] text-slate-400 font-medium italic">
                            {{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/y H:i') }}
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

        
        <!-- Pagination -->
        <div class="p-6 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="detailOpen" x-cloak class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div @click="detailOpen = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
        <div class="relative bg-white rounded-t-[2rem] sm:rounded-3xl shadow-2xl w-full max-w-md p-6 md:p-8 z-10"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
            
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6 sm:hidden"></div>

            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl flex items-center justify-center shadow-sm"
                     :class="{'bg-amber-500 text-white': detail.statusColor==='amber', 'bg-emerald-500 text-white': detail.statusColor==='emerald', 'bg-rose-500 text-white': detail.statusColor==='rose'}">
                    <i class="fa-solid text-xl md:text-2xl"
                       :class="{'fa-hourglass-half': detail.statusColor==='amber', 'fa-check': detail.statusColor==='emerald', 'fa-xmark': detail.statusColor==='rose'}"></i>
                </div>
                <button @click="detailOpen = false" class="hidden sm:flex w-10 h-10 bg-slate-50 hover:bg-slate-100 rounded-xl items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-slate-400"></i>
                </button>
            </div>

            <h2 class="text-xl md:text-2xl font-black text-slate-800 mb-6">Detail Transaksi</h2>
            
            <div class="space-y-4 mb-8">
                <div class="flex justify-between items-center pb-3 border-b border-slate-50"><span class="text-xs md:text-sm text-slate-400 font-bold uppercase tracking-wider">ID Transaksi</span><span class="font-bold text-slate-800 text-sm md:text-base" x-text="detail.id"></span></div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-50"><span class="text-xs md:text-sm text-slate-400 font-bold uppercase tracking-wider">Deskripsi</span><span class="font-bold text-slate-800 text-sm md:text-base text-right" x-text="detail.desc"></span></div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-50"><span class="text-xs md:text-sm text-slate-400 font-bold uppercase tracking-wider">Metode</span><span class="font-bold text-slate-800 text-sm md:text-base text-right" x-text="detail.bank"></span></div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-50"><span class="text-xs md:text-sm text-slate-400 font-bold uppercase tracking-wider">Tanggal</span><span class="font-bold text-slate-800 text-sm md:text-base text-right" x-text="detail.date"></span></div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-50">
                    <span class="text-xs md:text-sm text-slate-400 font-bold uppercase tracking-wider">Status</span>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold text-white shadow-sm" x-text="detail.status"
                          :class="{'bg-amber-500':detail.statusColor==='amber','bg-emerald-500':detail.statusColor==='emerald','bg-rose-500':detail.statusColor==='rose'}"></span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-xs md:text-sm text-slate-400 font-bold uppercase tracking-wider">Total</span>
                    <span class="text-xl md:text-2xl font-black text-indigo-600" x-text="detail.amount"></span>
                </div>
            </div>

            <div class="bg-indigo-50/50 rounded-2xl p-4 mb-8 border border-indigo-100" x-show="detail.note">
                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1.5">Catatan Petugas</p>
                <p class="text-xs md:text-sm text-indigo-900 font-medium leading-relaxed italic" x-text="detail.note"></p>
            </div>

            <button @click="detailOpen = false" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-2xl shadow-lg transition-all active:scale-95">
                Selesai
            </button>
        </div>
    </div>

</div>
@endsection
