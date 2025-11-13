@props(['title' => 'Sección'])

<section class="py-12">
    <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                {{-- 🔹 Encabezado del módulo --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-700">{{ $title }}</h2>
                    {{ $actions ?? '' }}
                </div>

                {{-- 🔹 Contenido dinámico --}}
                <div>
                    {{ $slot }}
                </div>

            </div>
        </div>
    </div>
</section>

{{-- 🔹 Scripts globales para toasts y confirmaciones --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        // Notificaciones rápidas
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

        // Confirmación de eliminación
        window.addEventListener('confirmarEliminacion', (event) => {
            const id = event.detail.id;
            Swal.fire({
                title: '¿Seguro que deseas eliminar este registro?',
                text: "Esta acción no se puede deshacer.",
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
