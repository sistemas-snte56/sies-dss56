<x-admin-section title="Cargos">
    {{-- 🔎 Buscador + boton --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">

        <input type="text"
            wire:model.live.debounce.500ms="search"
            placeholder="Buscar cargo..."
            class="border rounded-lg px-3 py-2 w-full md:w-1/3 focus:ring-2 focus:ring-orange-300" />

        <button wire:click="create"
            class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
            + Nuevo cargo
        </button>
    </div>


    {{-- 📋 Tabla --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 text-gray-700 text-sm">
                <tr>
                    <th class="p-3 border-b">ID</th>
                    <th class="p-3 border-b">Cargo</th>
                    <th class="p-3 border-b">Activo</th>
                    <th class="p-3 border-b text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cargos as $c)
                    <tr class="hover:bg-gray-50 border-b text-sm">
                        <td class="p-3">{{ $c->id }}</td>
                        <td class="p-3">{{ $c->nombre }}</td>
                        <td class="p-3">
                            @if ($c->activo)
                                <span class="text-green-600 font-semibold">Activo</span>
                            @else
                                <span class="text-gray-500">Inactivo</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <button wire:click="edit({{ $c->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2">
                                ✏️ Editar
                            </button>
                            <button
                                wire:click="$dispatch('confirmarEliminacion', { id: {{ $c->id }} })"
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

        {{-- Paginación --}}
        <div class="p-4">
            {{ $cargos->links() }}
        </div>
    </div>    


    {{-- 🧳 Modal Crear/Editar --}}
    @if ($modalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl p-6 relative">

                {{-- Botón cerrar --}}
                <button wire:click="closeModal" class="absolute top-3 right-4 text-gray-600 text-2xl">&times;</button>

                <h3 class="text-lg font-semibold mb-4">
                    {{ $isEditing ? 'Editar Cargo' : 'Nuevo Cargo' }}
                </h3>

                {{-- FORMULARIO --}}
                <form wire:submit.prevent="save" class="space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Nombre del cargo --}}
                        <div>
                            <label class="block text-gray-600 mb-1"  style="color: #ee7a00; font-size: 16px; font-weight: bold; margin-top: 4px;">Nombre del cargo</label>
                            <input type="text" wire:model="nombre" class="w-full border rounded px-3 py-2">
                            @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Estado activo --}}
                        <div class="flex items-center gap-2 mt-4">
                            <input type="checkbox" wire:model="activo">
                            <label class="text-gray-600">Activo</label>
                        </div>

                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-2 mt-5">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-400 text-white rounded">
                            Cancelar
                        </button>

                        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded">
                            {{ $isEditing ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif    
</x-admin-section>