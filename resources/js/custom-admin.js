document.addEventListener('livewire:initialized', () => {
    // Cek apakah fitur alerts aktif
    if (window.Filament?.unsavedChangesAlerts) {
        
        // Simpan fungsi original agar bisa dipanggil nanti
        const originalUnload = window.onbeforeunload;

        window.onbeforeunload = (event) => {
            // Ambil semua komponen Livewire di halaman
            const dirtyComponents = Livewire.all().filter(component => component.isDirty);

            if (dirtyComponents.length > 0) {
                const confirmLeave = window.confirm(
                    'Anda memiliki perubahan yang belum disimpan. Tinggalkan halaman ini?',
                );

                if (confirmLeave) {
                    // Izinkan navigasi selanjutnya tanpa prompt berulang
                    window.onbeforeunload = originalUnload;
                    return;
                }

                // Tahan navigasi
                event.preventDefault();
                event.returnValue = '';
                return false;
            }
        };
    }
});
