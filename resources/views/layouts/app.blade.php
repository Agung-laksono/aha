<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

    <!-- Quill.js -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    <!-- Modal Crop Image (Global) -->
    <div id="modal-crop" tabindex="-1" aria-hidden="true"
        class="bg-black bg-opacity-90 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[200] justify-center items-center w-full md:inset-0 h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Potong & Kompres Gambar</h3>
                    <button type="button" onclick="closeCropModal()"
                        class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <div class="max-h-[60vh] overflow-hidden rounded-lg bg-gray-100 flex justify-center items-center">
                        <img id="image-to-crop" src="" class="max-w-full block">
                    </div>
                </div>

                <!-- Stats Panel -->
                <div
                    class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 flex flex-wrap items-center justify-between border-t border-b dark:border-gray-700 gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Ukuran Awal</p>
                            <p id="size-before" class="text-sm font-black text-gray-700 dark:text-gray-200">-</p>
                        </div>
                        <div class="w-px h-8 bg-gray-200 dark:bg-gray-600"></div>
                        <div class="text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Estimasi Akhir</p>
                            <p id="size-after" class="text-sm font-black text-blue-600 dark:text-blue-400">-</p>
                        </div>
                    </div>

                    <div id="save-indicator"
                        class="hidden items-center bg-green-100 dark:bg-green-900/30 px-3 py-1.5 rounded-full border border-green-200 dark:border-green-800">
                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span id="save-percentage" class="text-xs font-bold text-green-700 dark:text-green-400">Hemat
                            0%</span>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 border-t dark:border-gray-700 gap-3">
                    <div class="flex items-center gap-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Resize:</label>
                        <select id="crop-resolution" onchange="updateSizeStats()"
                            class="text-xs bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="600">600px</option>
                            <option value="800">800px</option>
                            <option value="1024" selected>1024px</option>
                            <option value="original">Original Size</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="closeCropModal()"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="button" onclick="cropAndSave()"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-lg">Terapkan
                            & Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        let globalCropper = null;
        let cropTarget = {
            index: null,
            property: 'gambars',
            componentId: null,
            initialSize: 0
        };

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function updateSizeStats() {
            if (!globalCropper) return;

            const res = document.getElementById('crop-resolution').value;
            const canvasOptions = {
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            };

            if (res !== 'original') {
                canvasOptions.width = parseInt(res);
                canvasOptions.height = parseInt(res);
            }

            const canvas = globalCropper.getCroppedCanvas(canvasOptions);

            canvas.toBlob((blob) => {
                if (!blob) return;

                const currentSize = blob.size;
                document.getElementById('size-after').innerText = formatBytes(currentSize);

                if (cropTarget.initialSize > 0) {
                    const saved = Math.max(0, Math.round(((cropTarget.initialSize - currentSize) / cropTarget.initialSize) * 100));
                    document.getElementById('save-percentage').innerText = `Hemat ${saved}%`;
                    document.getElementById('save-indicator').classList.remove('hidden');
                    document.getElementById('save-indicator').classList.add('flex');
                }
            }, 'image/jpeg', 0.8);
        }

        function openCropModal(index, imageUrl, property = 'gambars', wire = null) {
            cropTarget.index = index;
            cropTarget.property = property;
            cropTarget.wire = wire;
            cropTarget.initialSize = 0;

            const modal = document.getElementById('modal-crop');
            const image = document.getElementById('image-to-crop');

            // Reset UI
            document.getElementById('size-before').innerText = 'Loading...';
            document.getElementById('size-after').innerText = '-';
            document.getElementById('save-indicator').classList.add('hidden');

            // Get initial size
            fetch(imageUrl).then(r => r.blob()).then(blob => {
                cropTarget.initialSize = blob.size;
                document.getElementById('size-before').innerText = formatBytes(blob.size);
            });

            image.src = imageUrl;
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (globalCropper) globalCropper.destroy();

            setTimeout(() => {
                globalCropper = new Cropper(image, {
                    aspectRatio: property === 'gambars' ? 1 : (property === 'gambarVendor' ? 1 : NaN),
                    viewMode: 1,
                    autoCropArea: 1,
                    dragMode: 'move',
                    responsive: true,
                    ready() {
                        updateSizeStats();
                    },
                    cropend() {
                        updateSizeStats();
                    }
                });
            }, 100);
        }

        function closeCropModal() {
            const modal = document.getElementById('modal-crop');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (globalCropper) {
                globalCropper.destroy();
                globalCropper = null;
            }
        }

        function cropAndSave() {
            if (!globalCropper) return;

            const res = document.getElementById('crop-resolution').value;
            const canvasOptions = {
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            };

            if (res !== 'original') {
                canvasOptions.width = parseInt(res);
                canvasOptions.height = parseInt(res);
            }

            const canvas = globalCropper.getCroppedCanvas(canvasOptions);

            const base64Data = canvas.toDataURL('image/jpeg', 0.8);

            if (cropTarget.wire) {
                cropTarget.wire.call('updateCroppedImage', cropTarget.index, base64Data, cropTarget.property).then(() => {
                    closeCropModal();
                });
            }
        }

        // Global Auto-Compression Helper
        async function compressImageFile(file, maxWidth = 1024, quality = 0.8) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (event) => {
                    const img = new Image();
                    img.src = event.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let width = img.width;
                        let height = img.height;

                        if (width > maxWidth || height > maxWidth) {
                            if (width > height) {
                                height = Math.round((height * maxWidth) / width);
                                width = maxWidth;
                            } else {
                                width = Math.round((width * maxWidth) / height);
                                height = maxWidth;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        resolve(canvas.toDataURL('image/jpeg', quality));
                    };
                };
            });
        } // ADDED MISSING BRACE HERE

        // Global Auto-Compression Handler for File Inputs
        async function handleAutoCompress(input, targetProperty, wire) {
            if (!input.files || input.files.length === 0) return;
            if (!wire) return;

            // Trigger global event so Alpine can show loading state
            window.dispatchEvent(new CustomEvent('compression-start'));

            // Use setTimeout to allow browser to yield and paint the loading UI before heavy JS processing
            setTimeout(async () => {
                try {
                    if (input.multiple) {
                        const results = [];
                        for (let i = 0; i < input.files.length; i++) {
                            const compressed = await compressImageFile(input.files[i]);
                            results.push(compressed);
                        }
                        wire.set(targetProperty, results);
                    } else {
                        const compressed = await compressImageFile(input.files[0]);
                        wire.set(targetProperty, compressed);
                    }
                } catch (e) {
                    console.error("Compression failed", e);
                } finally {
                    input.value = '';
                    window.dispatchEvent(new CustomEvent('compression-end'));
                }
            }, 50);
        }

        window.addEventListener('close-modal', event => {
            const openedModals = document.querySelectorAll('[role="dialog"]:not(.hidden)');
            openedModals.forEach(modal => {
                const closeBtn = modal.querySelector('[data-modal-hide]');
                if (closeBtn) closeBtn.click();
            });
        });
    </script>
</body>

</html>