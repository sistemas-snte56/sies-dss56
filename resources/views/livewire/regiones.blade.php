<main>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Regiones
        </h2>
    </x-slot>    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-4 text-gray-700">Gestión de Regiones</h2>
                
                        @if (session()->has('message'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                                {{ session('message') }}
                            </div>
                        @endif
                
                        @push('scripts')
                            <script>
                                document.addEventListener('livewire:initialized', () => {
                                    @this.on('swal', (data) => {
                                        // En Livewire 3, 'data' llega como un array con los parámetros del dispatch
                                        const payload = Array.isArray(data) ? data[0] : data;
                
                                        Swal.fire({
                                            title: payload.title ?? 'Evento realizado',
                                            icon: payload.icon ?? 'success',
                                            // timer: 1500,
                                            // toast: true,
                                            // position: 'top-end',
                                            // showConfirmButton: false,
                                            // timerProgressBar: true
                                        });
                                    });
                                });
                
                                // ⚠️ Confirmación de eliminación
                                window.addEventListener('confirmarEliminacion', (event) => {
                                    const id = event.detail.id;
                                    Swal.fire({
                                        title: '¿Seguro que deseas eliminar esta región?',
                                        text: "Esta acción no se puede deshacer.",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Sí, eliminar',
                                        cancelButtonText: 'Cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Ejecuta el método delete(id) de Livewire
                                            @this.call('delete', id);
                                        }
                                    });
                                });                
                            </script>
                        @endpush      
                
                        <div class="flex justify-between mb-4">
                            <input type="text" wire:model.live="search" placeholder="Buscar región o sede..." class="border rounded-lg px-3 py-2 w-1/3">
                            <button wire:click="openModal" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">+ Nueva Región</button>           
                        </div>
                
                
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse bg-white shadow rounded-lg border-gray-300">
                                <thead>
                                    <tr class="bg-gray-200 text-gray-700">
                                        <th class="px-4 py-2 text-base font-bold text-center">ID</th>
                                        <th class="px-4 py-2 text-base font-bold text-center">Región</th>
                                        <th class="px-4 py-2 text-base font-bold text-center">Sede</th>
                                        <th class="px-4 py-2 text-base font-bold text-center w-80">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($regiones as $region)
                                        <tr class="odd:bg-white even:bg-gray-100">
                                            <td class="px-4 py-2 text-base text-gray-800">{{ $region->id }}</td>
                                            <td class="px-4 py-2 text-base text-gray-800">{{ $region->region }}</td>
                                            <td class="px-4 py-2 text-base text-gray-800">{{ $region->sede }}</td>
                                            <td class="px-4 py-2 text-base text-gray-800">
                                                <button wire:click="edit({{ $region->id }})" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Editar</button>
                                                
                                                <button
                                                    wire:click="$dispatch('confirmarEliminacion', { id: {{ $region->id }} })"
                                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-4 text-center text-gray-500">Sin registros</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                
                
                        <div class="mt-4">
                            {{ $regiones->links() }}
                        </div>
                
                        <!-- Modal -->
                        @if ($modalOpen)
                            <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                                    <h3 class="text-lg font-semibold mb-4">{{ $region_id ? 'Editar Región' : 'Nueva Región' }}</h3>
                
                                    <form wire:submit.prevent="save">
                                        <div class="mb-3">
                                            <label class="block text-gray-600 mb-1">Nombre de la Región</label>
                                            <input type="text" wire:model="region" class="w-full border rounded-lg px-3 py-2">
                                            @error('region') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                        </div>
                
                                        <div class="mb-3">
                                            <label class="block text-gray-600 mb-1">Sede</label>
                                            <input type="text" wire:model="sede" class="w-full border rounded-lg px-3 py-2">
                                            @error('sede') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                        </div>
                
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cancelar</button>
                                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>