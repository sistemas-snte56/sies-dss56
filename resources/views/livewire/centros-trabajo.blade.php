<x-admin-section title="Centros de Trabajo">

    {{-- 🔎 Buscador + boton --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">

        <input type="text"
            wire:model.live.debounce.500ms="search"
            placeholder="Buscar centro o clave..."
            class="border rounded-lg px-3 py-2 w-full md:w-1/3 focus:ring-2 focus:ring-orange-300" />

        <button wire:click="openModal"
            class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
            + Nuevo Centro
        </button>

    </div>

    {{-- 📋 Tabla --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 text-gray-700 text-sm">
                <tr>
                    <th class="p-3 border-b">ID</th>
                    <th class="p-3 border-b">Nombre CT</th>
                    <th class="p-3 border-b">Clave</th>
                    <th class="p-3 border-b">CP</th>
                    <th class="p-3 border-b">Colonia</th>
                    <th class="p-3 border-b">Municipio</th>
                    <th class="p-3 border-b">Activo</th>
                    <th class="p-3 border-b text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centros as $c)
                    <tr class="hover:bg-gray-50 border-b text-sm">
                        <td class="p-3">{{ $c->id }}</td>
                        <td class="p-3">{{ $c->nombre_ct }}</td>
                        <td class="p-3">{{ $c->clave_ct }}</td>
                        <td class="p-3">{{ $c->codigo_postal }}</td>
                        <td class="p-3">{{ $c->colonia->nombre ?? '-' }}</td>
                        <td class="p-3">{{ $c->colonia->municipio->nombre ?? '-' }}</td>
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
            {{ $centros->links() }}
        </div>
    </div>

    {{-- 🧳 Modal Crear/Editar --}}
    @if ($modalOpen)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl p-6 relative">

                {{-- Botón cerrar --}}
                <button wire:click="closeModal" class="absolute top-3 right-4 text-gray-600 text-2xl">&times;</button>

                <h3 class="text-lg font-semibold mb-4">
                    {{ $centro_id ? 'Editar Centro de Trabajo' : 'Nuevo Centro de Trabajo' }}
                </h3>

                {{-- FORMULARIO --}}
                <form wire:submit.prevent="save" class="space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Nombre CT --}}
                        <div>
                            <label class="block text-gray-600 mb-1">Nombre del Centro</label>
                            <input type="text" wire:model="nombre_ct" class="w-full border rounded px-3 py-2">
                            @error('nombre_ct') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Clave CT --}}
                        <div>
                            <label class="block text-gray-600 mb-1">Clave CT</label>
                            <input type="text" wire:model="clave_ct" class="w-full border rounded px-3 py-2">
                            @error('clave_ct') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Código Postal --}}
                        <div>
                            <label class="block text-gray-600 mb-1">Código Postal</label>
                            <input type="text" wire:model.debounce.500ms="codigo_postal"
                                class="w-full border rounded px-3 py-2"
                                maxlength="5">
                            @error('codigo_postal') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Select Colonias --}}
                        <div>
                            <label class="block text-gray-600 mb-1">Colonia</label>

                            <select wire:model="colonia_id" class="w-full border rounded px-3 py-2">
                                <option value="">-- Seleccione una colonia --</option>

                                @foreach ($coloniasDelCP as $col)
                                    <option value="{{ $col->id }}">{{ $col->nombre }}</option>
                                @endforeach
                            </select>

                            @error('colonia_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Municipio Auto --}}
                        <div>
                            <label class="block text-gray-600 mb-1">Municipio</label>
                            <input type="text" class="w-full border rounded px-3 py-2 bg-gray-100" 
                                wire:model="municipio"
                                readonly>
                        </div>

                        {{-- Calle --}}
                        <div>
                            <label class="block text-gray-600 mb-1">Calle</label>
                            <input type="text" wire:model="calle" class="w-full border rounded px-3 py-2">
                        </div>

                        {{-- Número exterior --}}
                        <div>
                            <label class="block text-gray-600 mb-1">Número Exterior</label>
                            <input type="text" wire:model="numero_exterior" class="w-full border rounded px-3 py-2">
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
                            Guardar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

</x-admin-section>
