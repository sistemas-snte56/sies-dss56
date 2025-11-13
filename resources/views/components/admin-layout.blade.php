<div class=" wire:ignore.self p-6 bg-white rounded-2xl shadow-md">
    {{-- Título del módulo --}}
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100" style="color: orangered; font-weight: 700;">
        {{ $title ?? 'Módulo' }} 
    </h2>

    {{-- Contenido dinámico del módulo --}}
    <div>
        {{ $slot }}
    </div>
</div>

{{-- Scripts globales para todos los módulos del panel --}}
@push('scripts')
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:initialized', () => {

            // ✅ Toasts de éxito o error
            @this.on('swal', (data) => {
                const payload = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    title: payload.title ?? 'Acción realizada',
                    icon: payload.icon ?? 'success',
                    // timer: 1500,
                    // toast: true,
                    // position: 'top-end',
                    // showConfirmButton: false,
                    // timerProgressBar: true
                });
            });

            // ⚠️ Confirmación de eliminación genérica
            window.addEventListener('confirmarEliminacion', (event) => {
                const id = event.detail.id;
                Swal.fire({
                    title: '¿Seguro que deseas eliminar este registro?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('delete', id);
                    }
                });
            });
        });
    </script>
@endpush