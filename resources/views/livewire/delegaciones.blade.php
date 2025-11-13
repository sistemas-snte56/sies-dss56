<main class="py-12">
    <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                {{-- 🔹 Encabezado --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-700">Gestión de Delegaciones</h2>
                    <button
                        wire:click="openModal"
                        class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                        + Nueva Delegación
                    </button>
                </div>

                {{-- 🔹 Alertas de sesión (opcional) --}}
                @if (session()->has('message'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                {{-- 🔹 Campo de búsqueda --}}
                <div class="flex justify-between items-center mb-4">
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Buscar delegación, nivel, sede o región..."
                        class="border rounded-lg px-3 py-2 w-full md:w-1/3 focus:ring-2 focus:ring-orange-400 @if($search) border-orange-400 @endif"
                    >
                    <span class="text-sm text-gray-400 ml-3" wire:loading>Buscando...</span>
                </div>

                {{-- 🔹 Tabla de datos --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-700 text-sm">
                            <tr>
                                <th class="p-3 border-b">ID</th>
                                <th class="p-3 border-b">Región</th>
                                <th class="p-3 border-b">Delegación</th>
                                <th class="p-3 border-b">Nivel</th>
                                <th class="p-3 border-b">Sede</th>
                                <th class="p-3 border-b text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($delegaciones as $d)
                                <tr class="hover:bg-gray-50 border-b text-sm">
                                    <td class="p-3">{{ $d->id }}</td>
                                    <td class="p-3">{{ $d->region->region ?? '-' }}</td>
                                    <td class="p-3">{{ $d->delegacion }}</td>
                                    <td class="p-3">{{ $d->nivel }}</td>
                                    <td class="p-3">{{ $d->sede }}</td>
                                    <td class="p-3 text-center">
                                        <button
                                            wire:click="edit({{ $d->id }})"
                                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2">
                                            ✏️ Editar
                                        </button>
                                        <button
                                            wire:click="$dispatch('confirmarEliminacion', { id: {{ $d->id }} })"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            🗑️ Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">No hay registros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-4">
                        {{ $delegaciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔹 Modal para Crear/Editar --}}
    @if($modalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg p-6 relative">
                <button wire:click="closeModal" class="absolute top-3 right-4 text-gray-600 text-2xl">&times;</button>

                <h3 class="text-lg font-semibold mb-4">
                    {{ $delegacion_id ? 'Editar Delegación' : 'Nueva Delegación' }}
                </h3>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-600 mb-1">Región</label>
                            <select wire:model="region_id" class="w-full border rounded px-3 py-2">
                                <option value="">-- Seleccionar región --</option>
                                @foreach($regiones as $r)
                                    <option value="{{ $r->id }}">{{ $r->region }}</option>
                                @endforeach
                            </select>
                            @error('region_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-600 mb-1">Delegación</label>
                            <input type="text" wire:model="delegacion" class="w-full border rounded px-3 py-2">
                            @error('delegacion') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-600 mb-1">Nivel</label>
                            <input type="text" wire:model="nivel" class="w-full border rounded px-3 py-2">
                            @error('nivel') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-600 mb-1">Sede</label>
                            <input type="text" wire:model="sede" class="w-full border rounded px-3 py-2">
                            @error('sede') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-400 text-white rounded">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</main>

{{-- 🔹 Scripts SweetAlert2 --}}
@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Toasts
            @this.on('swal', (data) => {
                const payload = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    title: payload.title ?? 'Acción realizada',
                    icon: payload.icon ?? 'success',
                    timer: 1500,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            });

            // Confirmación de eliminación
            window.addEventListener('confirmarEliminacion', (event) => {
                const id = event.detail.id;
                Swal.fire({
                    title: '¿Seguro que deseas eliminar esta delegación?',
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
