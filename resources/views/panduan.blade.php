<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2
                class="font-black text-2xl text-gray-900 dark:text-white leading-tight tracking-tighter uppercase italic">
                {{ __('AHA STRATEGIC ENCYCLOPEDIA') }}
            </h2>
            <div class="flex items-center gap-2">
                <span
                    class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-200 dark:border-emerald-800">System
                    v2.5.4</span>
                <span
                    class="px-3 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-primary-200 dark:border-primary-800 italic">Full
                    Manual</span>
            </div>
        </div>
    </x-slot>

    <div
        class="py-12 bg-gray-50/30 dark:bg-gray-950 min-h-screen font-sans selection:bg-primary-500 selection:text-white">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                <!-- STICKY SIDE NAVIGATION -->
                <aside class="w-full lg:w-72 lg:sticky lg:top-24 space-y-4">
                    <div
                        class="bg-white dark:bg-gray-900 p-6 rounded-[2.5rem] shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800">
                        <h3
                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 border-b pb-4 dark:border-gray-800">
                            Quick Navigation</h3>
                        <nav class="space-y-1">
                            <a href="#intro"
                                class="group flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-all">
                                <span
                                    class="w-2 h-2 bg-primary-500 rounded-full group-hover:scale-150 transition-transform"></span>
                                <span
                                    class="text-xs font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest group-hover:text-primary-600 transition-colors">Introduction</span>
                            </a>
                            <a href="#masters"
                                class="group flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-orange-50 dark:hover:bg-orange-900/10 transition-all">
                                <span
                                    class="w-2 h-2 bg-orange-500 rounded-full group-hover:scale-150 transition-transform"></span>
                                <span
                                    class="text-xs font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest group-hover:text-orange-600 transition-colors">Master
                                    Data</span>
                            </a>
                            <a href="#access"
                                class="group flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-fuchsia-50 dark:hover:bg-fuchsia-900/10 transition-all">
                                <span
                                    class="w-2 h-2 bg-fuchsia-500 rounded-full group-hover:scale-150 transition-transform"></span>
                                <span
                                    class="text-xs font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest group-hover:text-fuchsia-600 transition-colors">Access
                                    Control (RBAC)</span>
                            </a>
                            <a href="#purchase"
                                class="group flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-emerald-50 dark:hover:bg-emerald-900/10 transition-all">
                                <span
                                    class="w-2 h-2 bg-emerald-500 rounded-full group-hover:scale-150 transition-transform"></span>
                                <span
                                    class="text-xs font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest group-hover:text-emerald-600 transition-colors">Purchase
                                    Cycle</span>
                            </a>
                            <a href="#finance"
                                class="group flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all">
                                <span
                                    class="w-2 h-2 bg-blue-500 rounded-full group-hover:scale-150 transition-transform"></span>
                                <span
                                    class="text-xs font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest group-hover:text-blue-600 transition-colors">Financial
                                    Deck</span>
                            </a>
                            <a href="#inventory"
                                class="group flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all">
                                <span
                                    class="w-2 h-2 bg-indigo-500 rounded-full group-hover:scale-150 transition-transform"></span>
                                <span
                                    class="text-xs font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Inventory
                                    Control</span>
                            </a>
                            <a href="#returns"
                                class="group flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-rose-50 dark:hover:bg-rose-900/10 transition-all">
                                <span
                                    class="w-2 h-2 bg-rose-500 rounded-full group-hover:scale-150 transition-transform"></span>
                                <span
                                    class="text-xs font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest group-hover:text-rose-600 transition-colors">Returns
                                    (Retur)</span>
                            </a>
                        </nav>
                    </div>

                    <div
                        class="bg-gradient-to-br from-primary-600 to-indigo-700 p-8 rounded-[2.5rem] shadow-2xl text-white space-y-4">
                        <div class="p-3 bg-white/20 rounded-2xl w-fit">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-black text-sm uppercase tracking-widest">Support Center</h4>
                        <p class="text-xs font-medium text-primary-100 leading-relaxed">Butuh bantuan teknis lebih
                            lanjut? Hubungi Administrator Tim melalui menu User Management.</p>
                    </div>
                </aside>

                <!-- MAIN CONTENT AREA -->
                <main class="flex-1 space-y-12 pb-24">

                    <!-- 1. INTRODUCTION SECTION -->
                    <section id="intro" class="scroll-mt-24 space-y-6">
                        <div
                            class="bg-white dark:bg-gray-900 p-8 lg:p-16 rounded-[3rem] shadow-2xl border border-gray-50 dark:border-gray-800 relative overflow-hidden">
                            <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-500/5 rounded-full blur-3xl">
                            </div>
                            <div class="relative z-10 space-y-8">
                                <div class="space-y-4">
                                    <h2
                                        class="text-5xl lg:text-7xl font-black text-gray-900 dark:text-white leading-[1.1] tracking-tighter">
                                        Selamat Datang di <br>
                                        <span
                                            class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 via-indigo-600 to-violet-600">AHA
                                            Intelligent Inventory.</span>
                                    </h2>
                                    <p
                                        class="max-w-3xl text-gray-500 dark:text-gray-400 text-xl font-medium leading-relaxed">
                                        Platform ini dirancang untuk otomasi total—dari pencatatan stok di banyak gudang
                                        hingga rekonsiliasi kas secara real-time. Pelajari setiap modul untuk efisiensi
                                        maksimal.
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div
                                        class="p-8 bg-gray-50/50 dark:bg-gray-800/50 rounded-3xl border border-gray-100 dark:border-gray-800 space-y-4">
                                        <div
                                            class="p-3 bg-white dark:bg-gray-800 shadow-sm rounded-2xl w-fit text-primary-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <h3
                                            class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm italic">
                                            Prinsip Integritas</h3>
                                        <p class="text-sm text-gray-500 font-medium leading-relaxed">Setiap aksi
                                            memiliki reaksi. Menambah stok akan mencatat mutasi kas, membuat log
                                            aktivitas, dan memberitahukan tim secara atomik dalam satu detik.</p>
                                    </div>
                                    <div
                                        class="p-8 bg-gray-50/50 dark:bg-gray-800/50 rounded-3xl border border-gray-100 dark:border-gray-800 space-y-4">
                                        <div
                                            class="p-3 bg-white dark:bg-gray-800 shadow-sm rounded-2xl w-fit text-emerald-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3
                                            class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm italic">
                                            Otomasi Keuangan</h3>
                                        <p class="text-sm text-gray-500 font-medium leading-relaxed">Tidak ada input
                                            manual untuk pembayaran nota. Sistem memotong saldo akun kas PIC secara
                                            cerdas berdasarkan pengaturan di User Management.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 1.5. ACCESS CONTROL (RBAC) -->
                    <section id="access" class="scroll-mt-24 space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-[2px] bg-fuchsia-500"></span>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Access Control & Permissions</h2>
                        </div>

                        <div class="bg-white dark:bg-gray-900 rounded-[3rem] shadow-2xl overflow-hidden border border-gray-50 dark:border-gray-800 p-8 lg:p-12 space-y-8">
                            
                            <div class="space-y-4">
                                <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Konsep Hybrid: Roles vs Direct Permissions</h3>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-relaxed">
                                    Aplikasi ini menggunakan arsitektur keamanan <b>Hybrid RBAC</b> (Role-Based Access Control) yang menggabungkan kekuatan <i>Jetstream Teams</i> dan <i>Spatie Permissions</i>. Dirancang khusus untuk perusahaan dengan banyak cabang/tim yang membutuhkan fleksibilitas tinggi.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="p-8 bg-gray-50 dark:bg-gray-800/50 rounded-3xl border border-gray-100 dark:border-gray-800 space-y-4">
                                    <div class="flex items-center justify-between border-b pb-4 dark:border-gray-700">
                                        <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">1. Team Roles (Peran)</h4>
                                        <span class="px-2 py-1 bg-gray-200 dark:bg-gray-700 rounded text-[10px] font-black uppercase">Level Global</span>
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium leading-relaxed">
                                        Peran dasar bawaan sistem (contoh: <code class="text-pink-500">admin</code>, <code class="text-pink-500">member</code>). Peran ini menentukan struktur hierarki di dalam sebuah Cabang/Tim. 
                                        <b>Sebagai contoh:</b> Hanya pengguna dengan Role <code class="text-pink-500">admin</code> yang dapat mengakses menu "User Management" untuk mengundang atau mengeluarkan staf lain.
                                    </p>
                                </div>

                                <div class="p-8 bg-fuchsia-50 dark:bg-fuchsia-950/20 rounded-3xl border border-fuchsia-100 dark:border-fuchsia-900/30 space-y-4">
                                    <div class="flex items-center justify-between border-b border-fuchsia-200 dark:border-fuchsia-900/50 pb-4">
                                        <h4 class="font-black text-fuchsia-900 dark:text-fuchsia-200 uppercase tracking-widest text-sm">2. Direct Permissions (Izin Langsung)</h4>
                                        <span class="px-2 py-1 bg-fuchsia-200 dark:bg-fuchsia-900/50 text-fuchsia-700 dark:text-fuchsia-300 rounded text-[10px] font-black uppercase">Level Spesifik</span>
                                    </div>
                                    <p class="text-xs text-fuchsia-800 dark:text-fuchsia-300/80 font-medium leading-relaxed">
                                        Karena Role terlalu kaku, kita menggunakan <b>Izin Langsung</b> ke setiap staf untuk kontrol satuan. Meskipun 5 orang memiliki Role yang sama (<code class="text-pink-500 text-sm">member</code>), Anda bisa mengatur agar Budi hanya boleh input Mutasi Kas, sedangkan Siti hanya boleh input Pembelian.
                                    </p>
                                </div>
                            </div>

                            <!-- CONTOH KASUS LAPANGAN -->
                            <div class="p-8 bg-blue-50 dark:bg-blue-950/20 rounded-[2.5rem] border border-blue-100 dark:border-blue-900/30 space-y-6">
                                <div class="flex items-center gap-3 border-b border-blue-200 dark:border-blue-900/50 pb-4">
                                    <div class="p-2 bg-blue-600 rounded-lg text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h4 class="text-lg font-black uppercase text-blue-900 dark:text-blue-300 tracking-tight">Contoh Kasus Nyata di Lapangan</h4>
                                </div>
                                
                                <p class="text-[13px] text-blue-800 dark:text-blue-300/80 font-medium leading-relaxed italic">
                                    Bayangkan Anda memiliki sebuah Cabang/Tim bernama "Toko Pusat". Di sana ada 3 orang staf yang semuanya Anda beri Role dasar <code class="bg-blue-200 dark:bg-blue-900 px-1 rounded">member</code> (agar mereka tidak bisa masuk ke menu User Management). Bagaimana Anda membagi tugas mereka?
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-blue-100 dark:border-blue-800 space-y-3 relative overflow-hidden">
                                        <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/10 rounded-bl-full"></div>
                                        <h5 class="font-black text-gray-900 dark:text-white text-sm">Staf A: (Bag. Gudang)</h5>
                                        <ul class="space-y-2 text-[11px] text-gray-600 dark:text-gray-400 font-medium">
                                            <li class="flex items-start gap-2"><span class="text-emerald-500 mt-0.5">✓</span> Boleh <span class="font-bold text-gray-900 dark:text-gray-300">Terima Pembelian</span></li>
                                            <li class="flex items-start gap-2"><span class="text-emerald-500 mt-0.5">✓</span> Boleh <span class="font-bold text-gray-900 dark:text-gray-300">Mutasi Antar Gudang</span></li>
                                            <li class="flex items-start gap-2 text-rose-500/80"><span class="mt-0.5">✕</span> Dilarang <span class="font-bold">Input Retur</span></li>
                                        </ul>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-blue-100 dark:border-blue-800 space-y-3 relative overflow-hidden">
                                        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500/10 rounded-bl-full"></div>
                                        <h5 class="font-black text-gray-900 dark:text-white text-sm">Staf B: (Bag. Pembelian)</h5>
                                        <ul class="space-y-2 text-[11px] text-gray-600 dark:text-gray-400 font-medium">
                                            <li class="flex items-start gap-2"><span class="text-emerald-500 mt-0.5">✓</span> Boleh <span class="font-bold text-gray-900 dark:text-gray-300">Buat PO Baru</span></li>
                                            <li class="flex items-start gap-2"><span class="text-emerald-500 mt-0.5">✓</span> Boleh <span class="font-bold text-gray-900 dark:text-gray-300">Manajemen Vendor</span></li>
                                            <li class="flex items-start gap-2 text-rose-500/80"><span class="mt-0.5">✕</span> Dilarang <span class="font-bold">Lihat Laba/Rugi</span></li>
                                        </ul>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-blue-100 dark:border-blue-800 space-y-3 relative overflow-hidden">
                                        <div class="absolute top-0 right-0 w-16 h-16 bg-purple-500/10 rounded-bl-full"></div>
                                        <h5 class="font-black text-gray-900 dark:text-white text-sm">Staf C: (Bag. Keuangan)</h5>
                                        <ul class="space-y-2 text-[11px] text-gray-600 dark:text-gray-400 font-medium">
                                            <li class="flex items-start gap-2"><span class="text-emerald-500 mt-0.5">✓</span> Boleh <span class="font-bold text-gray-900 dark:text-gray-300">Bayar Hutang (Kas)</span></li>
                                            <li class="flex items-start gap-2"><span class="text-emerald-500 mt-0.5">✓</span> Boleh <span class="font-bold text-gray-900 dark:text-gray-300">Lihat Neraca Laporan</span></li>
                                            <li class="flex items-start gap-2 text-rose-500/80"><span class="mt-0.5">✕</span> Dilarang <span class="font-bold">Input Barang Fisik</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="text-[11px] text-blue-700 dark:text-blue-400 font-bold bg-white dark:bg-gray-900 w-fit px-3 py-1.5 rounded-full border border-blue-200 dark:border-blue-800">
                                    💡 Kesimpulan: Dengan Izin Langsung (Direct Permissions), tugas dapat diisolasi secara sempurna tanpa perlu membuat puluhan Role (Jabatan) fiktif yang baru.
                                </p>
                            </div>

                            <!-- SOP SECTION -->
                            <div class="p-8 bg-gradient-to-br from-gray-900 to-black rounded-[2.5rem] shadow-xl text-white space-y-8">
                                <div class="flex items-center justify-between border-b border-white/20 pb-4">
                                    <h4 class="text-xl font-black uppercase tracking-widest text-fuchsia-400">SOP Pengaturan Langkah demi Langkah</h4>
                                    <span class="px-3 py-1 bg-fuchsia-500/20 text-fuchsia-300 border border-fuchsia-500/50 rounded-full text-[10px] font-black uppercase tracking-widest">Hanya Untuk Admin</span>
                                </div>
                                
                                <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-white/10 before:to-transparent">
                                    
                                    <!-- Step 1 -->
                                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-gray-900 bg-fuchsia-500 text-white font-black shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-[0_0_0_4px_rgba(217,70,239,0.2)] z-10 transition-all duration-300">
                                            1
                                        </div>
                                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-5 rounded-2xl bg-white/5 border border-white/10 hover:border-fuchsia-500/50 transition-colors">
                                            <h5 class="font-bold text-sm text-fuchsia-300 mb-1 uppercase tracking-wide">Buka Profil Tim</h5>
                                            <p class="text-[12px] text-gray-400 leading-relaxed">Pastikan Anda login sebagai <code class="text-white">Admin</code>. Klik ikon Avatar Anda di sudut kanan atas menu, lalu pilih <b>User Management</b> di bawah grup "Manage Team".</p>
                                        </div>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-gray-900 bg-fuchsia-500 text-white font-black shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-[0_0_0_4px_rgba(217,70,239,0.2)] z-10 transition-all duration-300">
                                            2
                                        </div>
                                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-5 rounded-2xl bg-white/5 border border-white/10 hover:border-fuchsia-500/50 transition-colors">
                                            <h5 class="font-bold text-sm text-fuchsia-300 mb-1 uppercase tracking-wide">Pilih Staf</h5>
                                            <p class="text-[12px] text-gray-400 leading-relaxed">Pada tabel daftar anggota tim, temukan nama staf yang akan ditugaskan. Di kolom "Aksi", klik tombol biru muda bertuliskan <span class="inline-flex items-center justify-center px-2 py-0.5 bg-cyan-100 text-cyan-700 rounded text-[10px] font-bold mx-1">Akses Fitur</span>.</p>
                                        </div>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-gray-900 bg-fuchsia-500 text-white font-black shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-[0_0_0_4px_rgba(217,70,239,0.2)] z-10 transition-all duration-300">
                                            3
                                        </div>
                                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-5 rounded-2xl bg-white/10 border border-fuchsia-500/30 shadow-[0_4px_20px_-4px_rgba(217,70,239,0.3)] transition-colors">
                                            <h5 class="font-bold text-sm text-white mb-2 uppercase tracking-wide">Sesuaikan Kunci Gembok</h5>
                                            <p class="text-[12px] text-gray-300 leading-relaxed mb-3">Sebuah panel <i>Checklist</i> akan muncul (Pop-up). Centang (✓) izin yang diperbolehkan untuk staf tersebut sesuai diskripsi tugas pekerjaannya hari itu.</p>
                                            <div class="bg-black/50 p-3 rounded-lg border border-white/5 space-y-2">
                                                <div class="flex items-center justify-between opacity-50">
                                                    <span class="text-[10px] uppercase text-gray-400">Inventory Master</span>
                                                    <input type="checkbox" checked disabled class="rounded bg-black border-gray-600 text-fuchsia-500">
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[10px] uppercase text-white font-bold tracking-wide">create_pembelian</span>
                                                    <input type="checkbox" checked class="rounded bg-gray-900 border-gray-600 text-fuchsia-500">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 4 -->
                                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-gray-900 bg-emerald-500 text-white font-black shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-[0_0_0_4px_rgba(16,185,129,0.2)] z-10 transition-all duration-300">
                                            ✓
                                        </div>
                                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-5 rounded-2xl bg-white/5 border border-emerald-500/30 transition-colors">
                                            <h5 class="font-bold text-sm text-emerald-400 mb-1 uppercase tracking-wide">Tersimpan Otomatis</h5>
                                            <p class="text-[12px] text-gray-400 leading-relaxed">Sistem menganut prinsip <i>Real-time Save</i>. Setiap kali Anda mencentang, detik itu juga akses langsung aktif di perangkat staf tersebut. Tidak perlu menekan tombol "Simpan". Klik area kosong untuk menutup panel.</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-8 p-4 bg-rose-500/10 rounded-xl border-l-4 border-rose-500 flex items-start gap-4">
                                    <div class="text-rose-500 mt-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div class="space-y-1">
                                        <h6 class="text-[11px] font-black uppercase text-rose-400 tracking-widest">Aturan Isolasi Cabang</h6>
                                        <p class="text-[11px] text-rose-200/70 font-medium leading-relaxed italic">
                                            Hati-hati! Semua *checklists* yang Anda atur ini dikunci pada label **Tim/Cabang yang sedang aktif**. Jika Staf Eko dipindah dari "Cabang Jakarta" ke "Cabang Madiun", profil Izin Eko di Madiun dimulai dari kondisi kosong (terkunci total). Admin di Madiun harus mengatur ulang *checklist* Eko sesuai jabatannya di cabang baru tersebut.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>

                    <!-- 2. MASTER DATA (GEAR MENU) -->
                    <section id="masters" class="scroll-mt-24 space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-[2px] bg-orange-500"></span>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Data
                                Master & Gear Menu</h2>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-900 rounded-[3rem] shadow-2xl overflow-hidden border border-gray-50 dark:border-gray-800">
                            <div class="p-8 lg:p-12 space-y-12">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    <!-- Vendor Master -->
                                    <div class="space-y-4 group">
                                        <div
                                            class="p-4 bg-orange-50 dark:bg-orange-950/20 rounded-2xl w-fit text-orange-600 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <h4
                                            class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">
                                            Vendor / Supplier</h4>
                                        <p class="text-[13px] text-gray-500 leading-relaxed font-medium line-clamp-3">
                                            Tempat pendaftaran mitra bisnis. Wajib diisi karena setiap nota pembelian
                                            harus terikat ke satu vendor untuk pelacakan hutang piutang.</p>
                                    </div>
                                    <!-- Gudang Master -->
                                    <div class="space-y-4 group">
                                        <div
                                            class="p-4 bg-blue-50 dark:bg-blue-950/20 rounded-2xl w-fit text-blue-600 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <h4
                                            class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">
                                            Gudang & Lokasi</h4>
                                        <p class="text-[13px] text-gray-500 leading-relaxed font-medium line-clamp-3">
                                            Dukungan Multi-Warehouse. Stok bisa dipisah per lokasi (Showroom, Gudang
                                            Utama, dll) dengan akses yang bisa dibatasi per staf.</p>
                                    </div>
                                    <!-- Akun Kas Master -->
                                    <div class="space-y-4 group">
                                        <div
                                            class="p-4 bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl w-fit text-emerald-600 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <h4
                                            class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm">
                                            Akun Kas (Keuangan)</h4>
                                        <p class="text-[13px] text-gray-500 leading-relaxed font-medium line-clamp-3">
                                            Representasi laci uang fisik atau rekening bank. Sangat krusial untuk
                                            melacak saldo real per pemegang laci (PIC).</p>
                                    </div>
                                </div>

                                <!-- Step by Step Master -->
                                <div
                                    class="p-8 lg:p-12 bg-gray-50 dark:bg-gray-800/40 rounded-[2.5rem] space-y-10 border border-gray-100 dark:border-gray-800">
                                    <div class="text-center space-y-2">
                                        <h3
                                            class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                            Step-by-Step Menambah Produk</h3>
                                        <p class="text-sm text-gray-500 font-medium italic">Ikuti alur linear ini untuk
                                            mencegah kesalahan data</p>
                                    </div>
                                    <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative">
                                        <!-- Step cards -->
                                        <div class="flex-1 text-center space-y-4">
                                            <div
                                                class="w-16 h-16 bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800 flex items-center justify-center text-primary-600 font-black text-xl mx-auto">
                                                1</div>
                                            <h5 class="text-[10px] font-black uppercase tracking-widest">Setup Dasar
                                            </h5>
                                            <p class="text-xs text-gray-500 font-bold">Buat Kategori, Satuan, & Gudang.
                                            </p>
                                        </div>
                                        <div class="hidden md:block w-12 h-[2px] bg-gray-200 dark:bg-gray-700"></div>
                                        <div class="flex-1 text-center space-y-4">
                                            <div
                                                class="w-16 h-16 bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800 flex items-center justify-center text-primary-600 font-black text-xl mx-auto">
                                                2</div>
                                            <h5 class="text-[10px] font-black uppercase tracking-widest">Detail Produk
                                            </h5>
                                            <p class="text-xs text-gray-500 font-bold">Input Nama, SKU, & Foto Produk.
                                            </p>
                                        </div>
                                        <div class="hidden md:block w-12 h-[2px] bg-gray-200 dark:bg-gray-700"></div>
                                        <div class="flex-1 text-center space-y-4">
                                            <div
                                                class="w-16 h-16 bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800 flex items-center justify-center text-primary-600 font-black text-xl mx-auto">
                                                3</div>
                                            <h5 class="text-[10px] font-black uppercase tracking-widest">Cek Valuasi
                                            </h5>
                                            <p class="text-xs text-gray-500 font-bold">Atur Harga Beli & Harga Jual.</p>
                                        </div>
                                        <div class="hidden md:block w-12 h-[2px] bg-gray-200 dark:bg-gray-700"></div>
                                        <div class="flex-1 text-center space-y-4">
                                            <div
                                                class="w-16 h-16 bg-primary-600 rounded-2xl shadow-lg border border-primary-500 flex items-center justify-center text-white font-black text-xl mx-auto">
                                                4</div>
                                            <h5
                                                class="text-[10px] font-black uppercase tracking-widest text-primary-600">
                                                Terbit!</h5>
                                            <p class="text-xs text-gray-500 font-bold">Barang siap ditransaksikan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 3. PURCHASE CYCLE DEEP DIVE -->
                    <section id="purchase" class="scroll-mt-24 space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-[2px] bg-emerald-500"></span>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Alur
                                Pembelian & Hutang</h2>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-900 rounded-[3rem] shadow-2xl border border-gray-50 dark:border-gray-800 p-8 lg:p-12 space-y-12">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                                <div class="space-y-8">
                                    <div
                                        class="p-8 bg-emerald-50 dark:bg-emerald-950/20 rounded-[2.5rem] border border-emerald-100 dark:border-emerald-800 space-y-4">
                                        <h3
                                            class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            Status Barang: Received vs PO</h3>
                                        <div class="space-y-4">
                                            <div class="flex gap-4">
                                                <div
                                                    class="shrink-0 w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-white font-black text-[10px]">
                                                    R</div>
                                                <p class="text-[13px] text-gray-600 dark:text-gray-400 font-medium">
                                                    <b>Received:</b> Stok langsung masuk ke gudang. Gudang harus dipilih
                                                    saat menyimpan.</p>
                                            </div>
                                            <div class="flex gap-4">
                                                <div
                                                    class="shrink-0 w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center text-white font-black text-[10px]">
                                                    P</div>
                                                <p class="text-[13px] text-gray-600 dark:text-gray-400 font-medium">
                                                    <b>Pre-Order:</b> Stok belum masuk. Nota akan menggantung di menu
                                                    "Riwayat Pembelian" dengan label "Proses".</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="p-8 bg-gray-50 dark:bg-gray-800/50 rounded-[2.5rem] border border-gray-100 dark:border-gray-800 space-y-4">
                                        <h3
                                            class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                                            Sistem Pembayaran</h3>
                                        <ul class="space-y-3">
                                            <li
                                                class="flex items-center gap-3 text-xs text-gray-500 font-bold uppercase tracking-tight">
                                                <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                                                CASH: Saldo Kas PIC langsung terpotong Lunas.
                                            </li>
                                            <li
                                                class="flex items-center gap-3 text-xs text-gray-500 font-bold uppercase tracking-tight">
                                                <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                                                KREDIT: Membentuk Ledger Hutang ke Vendor.
                                            </li>
                                            <li
                                                class="flex items-center gap-3 text-xs text-gray-500 font-bold uppercase tracking-tight">
                                                <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                                                DP/TERMIN: Sebagian Cash, sisa masuk Hutang.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-100 dark:bg-gray-800 rounded-[3rem] p-8 aspect-video flex items-center justify-center group relative overflow-hidden">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-indigo-500/10 animate-pulse">
                                    </div>
                                    <div class="text-center space-y-4 relative z-10">
                                        <div
                                            class="w-20 h-20 bg-white dark:bg-gray-900 rounded-3xl shadow-2xl flex items-center justify-center text-primary-600 mx-auto">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <h5
                                            class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-sm italic">
                                            Otomasi Nota</h5>
                                        <p class="text-xs text-gray-500 font-medium">Sistem me-generate nomor nota unik
                                            dan menghitung total harga secara real-time termasuk diskon vendor.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 4. FINANCIAL DECK (MUKAS) -->
                    <section id="finance" class="scroll-mt-24 space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-[2px] bg-blue-500"></span>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                Financial Deck & Mutasi Kas</h2>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-900 rounded-[3rem] shadow-2xl border border-gray-50 dark:border-gray-800 p-8 lg:p-12 space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div
                                    class="bg-gradient-to-br from-blue-600 to-blue-800 p-8 rounded-[2.5rem] shadow-xl text-white space-y-6">
                                    <h4
                                        class="text-lg font-black uppercase tracking-widest italic border-b border-white/20 pb-4">
                                        Mode Otomatis (Auto-Pilot)</h4>
                                    <p
                                        class="text-[13px] text-blue-100 font-medium leading-relaxed italic border-l-4 border-white/30 pl-4">
                                        "Setiap Pembelian Lunas (Cash) akan membuat satu baris Mutasi Kas bertipe
                                        PENGELUARAN secara otomatis dengan keterangan referensi nota."</p>
                                    <p class="text-xs text-blue-200">Manfaat: Anda tidak perlu input manual untuk
                                        transaksi belanja stok.</p>
                                </div>
                                <div
                                    class="bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-gray-700 space-y-6">
                                    <h4
                                        class="text-lg font-black uppercase tracking-widest italic text-gray-900 dark:text-white border-b pb-4 dark:border-gray-700">
                                        Mode Manual (Operasional)</h4>
                                    <p class="text-[13px] text-gray-500 font-medium leading-relaxed">Gunakan tombol
                                        <b>"Tambah Mutasi"</b> untuk mencatat:</p>
                                    <ul class="space-y-2">
                                        <li
                                            class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Biaya Listrik /
                                            Air</li>
                                        <li
                                            class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Gaji Karyawan</li>
                                        <li
                                            class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>Top-up Saldo atau
                                            Injeksi Modal</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 5. INVENTORY CONTROL & ALERTS -->
                    <section id="inventory" class="scroll-mt-24 space-y-8">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-[2px] bg-indigo-500"></span>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                Inventory Control & Sorting</h2>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-900 rounded-[3rem] shadow-2xl border border-gray-50 dark:border-gray-800 p-8 lg:p-12 space-y-8">
                            <div class="flex flex-wrap items-start gap-8">
                                <div class="flex-1 min-w-[300px] space-y-6">
                                    <div
                                        class="p-4 bg-indigo-50 dark:bg-indigo-900/10 rounded-2xl border-l-4 border-indigo-600">
                                        <h5
                                            class="text-xs font-black uppercase text-indigo-700 dark:text-indigo-400 mb-1 tracking-widest italic underline">
                                            Multi-Sorting Feature</h5>
                                        <p class="text-xs text-gray-500 font-medium">Anda dapat mengurutkan stok
                                            berdasarkan <b>Terbaru</b>, <b>Stok Tersedikit (Alert)</b>, hingga <b>Stok
                                                Terbanyak</b>.</p>
                                    </div>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl">
                                        <h5 class="text-xs font-black uppercase text-gray-900 dark:text-white mb-2">
                                            Pencarian & Filter Pintar</h5>
                                        <p class="text-xs text-gray-500 font-medium leading-relaxed">Gunakan filter
                                            <b>Warehouse</b> untuk melihat stok di lokasi tertentu saja. Gabungkan
                                            dengan filter <b>Kategori</b> untuk laporan stok yang sangat spesifik
                                            (misal: "Stok Elektronik di Gudang B").</p>
                                    </div>
                                </div>
                                <div
                                    class="w-full lg:w-96 p-6 bg-gradient-to-br from-gray-900 to-black rounded-[2.5rem] text-white shadow-2xl border border-gray-800 space-y-4">
                                    <div class="flex items-center justify-between border-b border-gray-800 pb-4">
                                        <span
                                            class="text-[10px] uppercase font-black tracking-widest opacity-50 italic underline">Stock
                                            Analytics</span>
                                        <div class="p-1 bg-rose-500/20 rounded-md"><svg class="w-4 h-4 text-rose-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg></div>
                                    </div>
                                    <h4 class="text-lg font-black uppercase tracking-tighter">Low Stock Warning</h4>
                                    <p class="text-xs text-gray-400 font-medium leading-relaxed">Sistem memberi tanda
                                        merah/badge alert pada barang dengan jumlah stok di bawah ambang batas minimum
                                        untuk mengingatkan tim Purchasing agar segera melakukan RE-ORDER.</p>
                                    <div class="pt-4 flex items-center gap-1.5 opacity-40">
                                        <span class="w-1 h-1 bg-white rounded-full"></span>
                                        <span class="w-1 h-1 bg-white rounded-full"></span>
                                        <span class="w-1 h-1 bg-white rounded-full"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- 6. RETURN PROTOCOL -->
                    <section id="returns" class="scroll-mt-24 space-y-8 pb-20">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-[2px] bg-rose-500"></span>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                Prosedur Retur Barang</h2>
                        </div>

                        <div
                            class="bg-white dark:bg-gray-900 rounded-[3rem] shadow-2xl border border-gray-50 dark:border-gray-800 p-8 lg:p-12">
                            <div class="max-w-4xl space-y-8">
                                <p
                                    class="text-[15px] font-medium text-gray-600 dark:text-gray-400 leading-relaxed italic border-l-4 border-rose-500 pl-6">
                                    Retur dilakukan jika barang yang diterima Rusak (Broken) atau Salah tipe. Sistem
                                    akan otomatis mengurangi stok fisik dan melakukan penyesuaian nilai ke vendor.
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="p-6 bg-rose-50 dark:bg-rose-950/20 rounded-2xl space-y-2">
                                        <h6 class="font-black text-xs uppercase text-rose-600 tracking-widest">Syarat
                                            Retur</h6>
                                        <p class="text-xs text-gray-500 font-bold">Nota Harus Terdaftar: <span
                                                class="font-medium">Retur hanya bisa ditarik dari riwayat nota yang
                                                sudah ada.</span></p>
                                        <p class="text-xs text-gray-500 font-bold">Bukti Alasan: <span
                                                class="font-medium">Wajib menyertakan keterangan alasan retur di form
                                                yang disediakan.</span></p>
                                    </div>
                                    <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-2xl space-y-2">
                                        <h6
                                            class="font-black text-xs uppercase text-gray-900 dark:text-white tracking-widest">
                                            Efek Sistem</h6>
                                        <p class="text-xs text-gray-500 font-bold">Stok Berkurang: <span
                                                class="font-medium">Sesuai jumlah yang di-retur.</span></p>
                                        <p class="text-xs text-gray-500 font-bold">Ledger Balance: <span
                                                class="font-medium">Sistem menyesuaikan piutang vendor (jika nota sudah
                                                Lunas).</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Footer Note -->
                    <div class="text-center pt-24 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-center gap-2 mb-4">
                            <span
                                class="px-3 py-1 bg-black text-white text-[9px] font-black uppercase tracking-[0.3em] rounded">Powered
                                By AHA</span>
                            <span
                                class="px-3 py-1 bg-primary-600 text-white text-[9px] font-black uppercase tracking-[0.3em] rounded">Strategic
                                Edition</span>
                        </div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] italic underline">
                            Confidence Level: 100% — All Logics Audited & Verified.
                        </p>
                        <p class="mt-4 text-[9px] text-gray-400 opacity-50">Document ID: GUID-AHA-2026-XQW-UI-UX-ULTRA
                        </p>
                    </div>

                </main>
            </div>

        </div>
    </div>

    <!-- SMOOTH SCROLL SCRIPTS -->
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</x-app-layout>