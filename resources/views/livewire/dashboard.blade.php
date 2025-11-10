<main>
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Bienvenido al SIES-DSS56 🎓
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <h2 class="text-sm uppercase text-gray-500">Regiones</h2>
                <p class="text-3xl font-bold text-orange-600">0</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <h2 class="text-sm uppercase text-gray-500">Delegaciones</h2>
                <p class="text-3xl font-bold text-blue-600">0</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <h2 class="text-sm uppercase text-gray-500">Participantes</h2>
                <p class="text-3xl font-bold text-green-600">0</p>
            </div>
        </div>

        <div class="mt-6 text-gray-700">
            <p>Bienvenido, <strong>{{ auth()->user()->name }}</strong>.</p>
        </div>
    </div>
</main>