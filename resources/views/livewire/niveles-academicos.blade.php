<x-admin-section title="Niveles Académicos">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <input type="text"
            wire:model.live.debounce.500ms="search"
            placeholder="Buscar nivel académico..."
            class="border rounded-lg px-3 py-2 w-full md:w-1/3 focus:ring-2 focus:ring-orange-300">

        <button wire:click="create"
            class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">
            + Nuevo nivel
        </button>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 text-gray-700 text-sm">
                <tr>
                    <th class="p-3 border-b">ID</th>
                    <th class="p-3 border-b">Nivel académico</th>
                    <th class="p-3 border-b">Activo</th>
                    <th class="p-3 border-b text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($niveles as $n)
                    <tr class="hover:bg-gray-50 border-b text-sm">
                        <td class="p-3">{{ $n->id }}</td>
                        <td class="p-3">{{ $n->nombre }}</td>
                        <td class="p-3">
                            {!! $n->activo
                                ? '<span class="text-green-600 font-semibold">Activo</span>'
                                : '<span class="text-gray-500">Inactivo</span>' !!}
                        </td>
                        <td class="p-3 text-center">
                            <button wire:click="edit({{ $n->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2">
                                ✏️ Editar
                            </button>

                            <button wire:click="$dispatch('confirmarEliminacion', { id: {{ $n->id }} })"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">No hay registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $niveles->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if ($modalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl p-6 relative">

                <button wire:click="$set('modalOpen', false)"
                    class="absolute top-3 right-4 text-gray-600 text-2xl">&times;</button>

                <h3 class="text-lg font-semibold mb-4">
                    {{ $nivel_id ? 'Editar Nivel Académico' : 'Nuevo Nivel Académico' }}
                </h3>

                <form wire:submit.prevent="save" class="space-y-4">

                    <div>
                        <label class="block text-gray-600 font-semibold mb-1" style="color:#ee7a00;">
                            Nombre del nivel académico
                        </label>

                        <input type="text" wire:model="nombre"
                            class="w-full border rounded px-3 py-2">

                        @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="activo">
                        <label>Activo</label>
                    </div>

                    <div class="flex justify-end gap-2 mt-5">
                        <button type="button" wire:click="$set('modalOpen', false)"
                            class="px-4 py-2 bg-gray-400 text-white rounded">
                            Cancelar
                        </button>

                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded">
                            Guardar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    @endif

</x-admin-section>
