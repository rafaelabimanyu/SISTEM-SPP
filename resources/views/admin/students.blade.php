@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-check-circle text-xl"></i><span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-[#362773] tracking-tight">Data Master Siswa</h1>
            <p class="text-slate-500 mt-1.5 font-medium text-sm md:text-base">Kelola database siswa, penempatan kelas, dan kenaikan kelas</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3.5 rounded-2xl font-bold shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center gap-2 active:scale-95 text-sm md:text-base">
                <i class="fa-solid fa-user-plus"></i> Tambah Siswa
            </button>
        </div>
    </div>


    <!-- Filters & Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-10">
        <div class="lg:col-span-3 bg-white rounded-3xl p-5 md:p-6 shadow-sm border border-slate-100">
            <form method="GET" action="{{ route('admin.students') }}" class="flex flex-col md:flex-row gap-4 w-full">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NISN atau Nama..." class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <select name="class_id" onchange="this.form.submit()" class="bg-white border border-slate-100 rounded-2xl px-4 py-3 text-sm font-bold text-slate-600 focus:outline-none shadow-sm cursor-pointer hover:border-indigo-200 transition-colors">
                        <option value="all">Semua Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()" class="bg-white border border-slate-100 rounded-2xl px-4 py-3 text-sm font-bold text-slate-600 focus:outline-none shadow-sm cursor-pointer hover:border-indigo-200 transition-colors">
                        <option value="all">Angkatan</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="bg-[#362773] rounded-3xl p-6 shadow-lg shadow-indigo-900/10 text-white flex flex-col justify-center relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 text-white/5 text-7xl transform group-hover:scale-110 transition-transform"><i class="fa-solid fa-users"></i></div>
            <p class="text-indigo-200/60 text-[10px] font-bold uppercase tracking-widest mb-1 relative z-10">Total Siswa Aktif</p>
            <h3 class="text-3xl md:text-4xl font-black relative z-10">{{ $students->count() }}</h3>
        </div>
    </div>


    <!-- Student Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @forelse($students as $s)
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-indigo-100 hover:shadow-md transition-all relative group flex flex-col">
                <!-- Actions dropdown -->
                <div class="absolute top-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditModal({{ $s->id }}, '{{ addslashes($s->name) }}', '{{ $s->nisn }}', '{{ $s->nis }}', '{{ $s->class_room_id }}', '{{ $s->academic_year }}', '{{ $s->phone }}', '{{ addslashes($s->address) }}')" 
                            class="w-9 h-9 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl flex items-center justify-center transition-all">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <button onclick="openDeleteModal({{ $s->id }}, '{{ addslashes($s->name) }}')"
                            class="w-9 h-9 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl flex items-center justify-center transition-all">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>

                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xl shadow-sm border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        {{ substr($s->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 pr-16">
                        <h3 class="font-bold text-slate-800 text-lg leading-tight truncate">{{ $s->name }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">NISN: {{ $s->nisn }}</p>
                    </div>
                </div>
                
                <div class="bg-slate-50/50 rounded-2xl p-4 mb-6 border border-slate-50 space-y-3 flex-1">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-400 font-medium">Kelas</span>
                        <span class="font-bold text-slate-800">{{ $s->classRoom->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-400 font-medium">Angkatan</span>
                        <span class="font-bold text-slate-800">{{ $s->academic_year }}</span>
                    </div>
                </div>

                <button onclick="openDetailModal('{{ addslashes($s->name) }}', '{{ $s->nisn }}', '{{ $s->nis }}', '{{ $s->classRoom->name }}', '{{ $s->academic_year }}', '{{ $s->phone }}', '{{ addslashes($s->address) }}')" 
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-2xl transition-all text-sm shadow-lg shadow-slate-900/10 active:scale-95">
                    Lihat Profil Siswa
                </button>
            </div>

        @empty
            <div class="col-span-3 py-16 text-center text-slate-400 font-medium">
                <i class="fa-solid fa-users-slash text-4xl mb-3 block text-slate-300"></i>
                Tidak ada data siswa ditemukan
            </div>
        @endforelse
    </div>

    <!-- DETAIL MODAL -->
    <div id="detailModal" class="hidden fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div onclick="document.getElementById('detailModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="relative bg-white rounded-t-[2rem] sm:rounded-3xl shadow-2xl w-full max-w-md p-6 md:p-10 z-10 animate-slide-up sm:animate-none">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-8 sm:hidden"></div>
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-slate-50 hover:bg-slate-100 rounded-xl hidden sm:flex items-center justify-center transition"><i class="fa-solid fa-xmark text-slate-400"></i></button>
            <div class="w-20 h-20 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-3xl shadow-sm mx-auto mb-6" id="detailInitial">A</div>
            <h2 class="text-2xl font-black text-slate-800 text-center mb-1 tracking-tight" id="detailName"></h2>
            <p class="text-indigo-500 text-[10px] font-bold text-center mb-8 uppercase tracking-widest" id="detailNisn"></p>
            <div class="space-y-4 mb-10">
                <div class="flex justify-between items-center pb-3 border-b border-slate-50"><span class="text-xs font-bold text-slate-400 uppercase tracking-widest">NIS</span><span class="font-bold text-slate-800" id="detailNis"></span></div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-50"><span class="text-xs font-bold text-slate-400 uppercase tracking-widest">KELAS</span><span class="font-bold text-slate-800" id="detailClass"></span></div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-50"><span class="text-xs font-bold text-slate-400 uppercase tracking-widest">ANGKATAN</span><span class="font-bold text-slate-800" id="detailYear"></span></div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-50"><span class="text-xs font-bold text-slate-400 uppercase tracking-widest">TELEPON</span><span class="font-bold text-slate-800" id="detailPhone"></span></div>
                <div class="pt-2"><span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">ALAMAT</span><span class="text-sm font-medium text-slate-600 leading-relaxed italic" id="detailAddress"></span></div>
            </div>
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-2xl transition shadow-lg active:scale-95">Tutup Profil</button>
        </div>
    </div>


    <!-- ADD MODAL -->
    <div id="addModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('addModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Tambah Siswa Baru</h2>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan nama..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">NISN</label>
                            <input type="text" name="nisn" required maxlength="10" placeholder="10 digit NISN" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">NIS</label>
                            <input type="text" name="nis" required maxlength="8" placeholder="8 digit NIS" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Kelas</label>
                            <select name="class_room_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Angkatan</label>
                            <input type="text" name="academic_year" required placeholder="2026" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Telepon</label>
                        <input type="text" name="phone" placeholder="08xxxxxxxxxx" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat</label>
                        <textarea name="address" rows="2" placeholder="Masukkan alamat lengkap..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></textarea>
                    </div>
                    <div class="flex items-center gap-2 py-2">
                        <input type="checkbox" name="create_user" value="1" id="create_user" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="create_user" class="text-sm font-bold text-slate-700">Buat akun login siswa (Password: NISN)</label>
                        <input type="hidden" name="password" id="default_pass">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30">
                            <i class="fa-solid fa-user-plus mr-2"></i>Tambah Siswa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('editModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Edit Data Siswa</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="editName" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-bold text-slate-700 mb-1.5">NISN</label>
                            <input type="text" name="nisn" id="editNisn" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                        <div><label class="block text-sm font-bold text-slate-700 mb-1.5">NIS</label>
                            <input type="text" name="nis" id="editNis" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                        <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Kelas</label>
                            <select name="class_room_id" id="editClassId" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Angkatan</label>
                            <input type="text" name="academic_year" id="editYear" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                    </div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="phone" id="editPhone" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat</label>
                        <textarea name="address" id="editAddress" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></textarea></div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('deleteModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-sm p-8 text-center z-10">
            <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-5"><i class="fa-solid fa-trash-can text-2xl"></i></div>
            <h2 class="text-xl font-extrabold text-slate-800 mb-2">Hapus Data Siswa?</h2>
            <p class="text-slate-500 mb-8">Data <span class="font-bold text-slate-800" id="deleteName"></span> akan dihapus permanen.</p>
            <form id="deleteForm" method="POST" action="">
                @csrf @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                    <button type="submit" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-rose-500/30">Hapus</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function openDetailModal(name, nisn, nis, className, year, phone, address) {
    document.getElementById('detailName').textContent = name;
    document.getElementById('detailInitial').textContent = name.charAt(0).toUpperCase();
    document.getElementById('detailNisn').textContent = 'NISN: ' + nisn;
    document.getElementById('detailNis').textContent = nis;
    document.getElementById('detailClass').textContent = className;
    document.getElementById('detailYear').textContent = year;
    document.getElementById('detailPhone').textContent = phone || '-';
    document.getElementById('detailAddress').textContent = address || '-';
    document.getElementById('detailModal').classList.remove('hidden');
}

function openEditModal(id, name, nisn, nis, classId, year, phone, address) {
    document.getElementById('editForm').action = '/admin/students/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editNisn').value = nisn;
    document.getElementById('editNis').value = nis;
    document.getElementById('editClassId').value = classId;
    document.getElementById('editYear').value = year;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editAddress').value = address;
    document.getElementById('editModal').classList.remove('hidden');
}

function openDeleteModal(id, name) {
    document.getElementById('deleteForm').action = '/admin/students/' + id;
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Set password to NISN when checkbox is checked
document.getElementById('create_user').addEventListener('change', function() {
    if(this.checked) {
        const nisn = document.querySelector('input[name="nisn"]').value;
        document.getElementById('default_pass').value = nisn;
    }
});
</script>
@endsection
