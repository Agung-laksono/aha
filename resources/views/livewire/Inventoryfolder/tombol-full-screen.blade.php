<!-- Full Screen Toggle (Alpine.js version for high reliability) -->
<button type="button" x-data="{ 
            isFullscreen: false,
            toggleFS() {
                const target = document.getElementById('layarPenuh');
                if (!document.fullscreenElement) {
                    target.requestFullscreen().then(() => this.isFullscreen = true).catch(err => console.error(err));
                } else {
                    document.exitFullscreen().then(() => this.isFullscreen = false).catch(err => console.error(err));
                }
            }
        }" @click="toggleFS()" @fullscreenchange.window="isFullscreen = !!document.fullscreenElement"
    class="flex items-center justify-center p-2 rounded-xl transition-all border border-gray-100 dark:border-gray-600 shadow-sm"
    :class="isFullscreen ? 'bg-rose-600 text-white border-rose-500' : 'bg-white text-gray-400 hover:text-gray-600 dark:bg-gray-700'"
    :title="isFullscreen ? 'Keluar Layar Penuh' : 'Buka Layar Penuh'">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path x-show="!isFullscreen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
        <path x-show="isFullscreen" style="display:none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 14h3m0 0v3m0-3l-3 3m18-3h-3m0 0v3m0-3l3 3M6 7H3M6 7l-3-3m3 3V4m11 3h3m-3 0l3-3m-3 3V4" />
    </svg>
</button>