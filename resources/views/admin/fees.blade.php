@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-check-circle text-xl"></i><span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 px-4 md:px-0">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-[#362773] tracking-tight">Konfigurasi SPP</h1>
            <p class="text-slate-500 mt-1.5 font-medium text-sm md:text-base">Kelola nominal dan kategori tagihan untuk seluruh siswa</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
            <i class="fa-solid fa-file-invoice-dollar"></i> Buat Kategori Baru
        </button>
    </div>


    <!-- Active Fees -->
    <h2 class="text-lg md:text-xl font-bold text-slate-800 mb-6 flex items-center gap-2 px-4 md:px-0">
        <i class="fa-solid fa-circle-check text-emerald-500"></i> Kategori Tagihan Aktif
        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full ml-2 border border-emerald-100 tracking-wider uppercase">{{ $fees->count() }} ACTIVE</span>
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-12 px-4 md:px-0">

        @foreach($fees as $f)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative group transition-all hover:shadow-md flex flex-col">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500 group-hover:w-2 transition-all"></div>
                <div class="p-6 md:p-8 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold mb-3 border tracking-wider uppercase shadow-sm
                                {{ $f->type === 'Bulanan' ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-indigo-500 text-white border-indigo-500' }}">
                                {{ $f->type }}
                            </span>
                            <h3 class="text-xl md:text-2xl font-black text-slate-800 leading-tight">{{ $f->name }}</h3>
                        </div>
                        <form method="POST" action="{{ route('admin.fees.toggle', $f) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="relative w-12 h-6.5 rounded-full transition-colors shrink-0 bg-emerald-500 shadow-inner">
                                <span class="absolute top-0.5 w-5.5 h-5.5 bg-white rounded-full shadow transition-all left-6"></span>
                            </button>
                        </form>
                    </div>
                    
                    <p class="text-sm text-slate-500 mb-8 font-medium leading-relaxed">{{ $f->description }}</p>
                    
                    <div class="bg-slate-50/50 rounded-2xl p-5 border border-slate-50 flex flex-col sm:flex-row justify-between items-center mb-8 gap-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nominal Dasar</p>
                        <p class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Rp {{ number_format($f->amount, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="mt-auto">
                        <button onclick="openEditModal({{ $f->id }}, '{{ addslashes($f->name) }}', '{{ addslashes($f->description) }}', {{ $f->amount }}, '{{ $f->type }}')" 
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-2xl transition-all shadow-lg shadow-slate-900/10 active:scale-95 text-sm">
                            <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Konfigurasi
                        </button>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <!-- Archived -->
    @if($archived->count() > 0)
    <h2 class="text-lg font-bold text-slate-500 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-box-archive"></i> Arsip Tagihan (Nonaktif)
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($archived as $f)
            <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-200 opacity-70 hover:opacity-100 transition-opacity">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-600 leading-tight">{{ $f->name }}</h3>
                        <p class="text-xs font-bold text-slate-400 mt-1">{{ $f->description }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.fees.toggle', $f) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs bg-white border border-slate-200 text-slate-500 hover:text-emerald-600 hover:border-emerald-300 font-bold px-3 py-1.5 rounded-lg transition-colors">
                            Aktifkan
                        </button>
                    </form>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <p class="text-xl font-bold text-slate-500">Rp {{ number_format($f->amount, 0, ',', '.') }}</p>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- ADD MODAL -->
    <div id="addModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('addModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Kategori Tagihan Baru</h2>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.fees.store') }}">
                @csrf
                <div class="space-y-5">
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Kategori</label>
                        <input type="text" name="name" required placeholder="Contoh: Dana Kegiatan" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" placeholder="Deskripsi singkat..." rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></textarea></div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Jenis</label>
                        <select name="type" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold">
                            <option value="Bulanan">Bulanan</option><option value="Sekali Bayar">Sekali Bayar</option><option value="Tahunan">Tahunan</option>
                        </select></div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                            <input type="number" name="amount" required placeholder="500000" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-12 pr-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold text-xl">
                        </div></div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30">
                            <i class="fa-solid fa-plus mr-2"></i>Buat Kategori
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('editModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Edit Konfigurasi SPP</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Kategori</label>
                        <input type="text" name="name" id="editName" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" id="editDesc" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                            <input type="number" name="amount" id="editAmount" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-12 pr-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold text-xl">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Jenis</label>
                        <select name="type" id="editType" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold">
                            <option value="Bulanan">Bulanan</option>
                            <option value="Sekali Bayar">Sekali Bayar</option>
                            <option value="Tahunan">Tahunan</option>
                        </select>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function openEditModal(id, name, desc, amount, type) {
    document.getElementById('editForm').action = '/admin/fees/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editAmount').value = amount;
    document.getElementById('editType').value = type;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection
