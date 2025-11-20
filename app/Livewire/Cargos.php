<?php

namespace App\Livewire;

use App\Models\Cargo;
use Livewire\Component;
use Livewire\WithPagination;

class Cargos extends Component
{

    use WithPagination;

    public $search = '';
    public $modalOpen = false;

    public $cargo_id;
    public $nombre;
    public bool $activo = true;
    public bool $isEditing = false;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        // $this->resetInputFields();
        $this->modalOpen = true;
    }

    public function resetInputFields()
    {
        $this->cargo_id = null;
        $this->nombre = '';
        $this->activo = true;
    }

    // Cerramos el modal
    public function closeModal()
    {
        $this->modalOpen = false;
        $this->resetInputFields();
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'activo' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del cargo es obligatorio.',
            'nombre.string' => 'El nombre del cargo debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del cargo no debe exceder los 255 caracteres.',
            'activo.boolean' => 'El campo activo debe ser verdadero o falso.',
        ];
    }

    // Método para renderizar la vista    
    public function render()
    {
        $cargos = Cargo::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'asc')
            ->paginate(20);

        return view('livewire.cargos', [
            'cargos' => $cargos,
        ]);
    }

    // Nuevo metodo para crear 
    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->modalOpen = true;
    }    

    
    // Crear nuevo cargo o actualizar existente    
    public function save()
    {
        $this->validate();

        Cargo::updateOrCreate(
            ['id' => $this->cargo_id],
            [
                'nombre' => mb_strtoupper($this->nombre, 'UTF-8'),
                'activo' => $this->activo,
            ]
        );

        $this->dispatch('swal', [
            'title' => $this->cargo_id ? 'Cargo actualizado' : 'Cargo creado',
            'icon' => 'success',
        ]);

        $this->closeModal();
    }


    /*
        // Cargar los datos de un cargo para editarlo
        public function store()
        {
            // Hacemos la validación de los datos
            $this->validate();

            // Crear un nuevo cargo en la base de datos
            Cargo::create([
                'nombre' => mb_strtoupper($this->nombre, 'UTF-8'),
                'activo' => $this->activo,
            ]);

            $this->dispatch('swal', [
                'title' => 'Cargo creado',
                'text' => 'El cargo ha sido creado exitosamente.',
                'icon' => 'success',
            ]);

            $this->closeModal();
        }
    */

    /*
        // Actualizar un cargo
        public function update()
        {
            // Hacemos la validación de los datos
            $this->validate();

            // Actualizar el cargo en la base de datos
            $cargo = Cargo::findOrFail($this->cargo_id);
            $cargo->update([
                'nombre' => mb_strtoupper($this->nombre, 'UTF-8'),
                'activo' => $this->activo,
            ]);

            $this->dispatch('swal', [
                'title' => 'Cargo actualizado',
                'text' => 'El cargo ha sido actualizado exitosamente.',
                'icon' => 'success',
            ]);

            $this->closeModal();
        }

    */
    public function edit($id)
    {
        $cargo = Cargo::findOrFail($id);
        $this->cargo_id = $id;
        $this->nombre = $cargo->nombre;
        $this->activo = $cargo->activo;
        $this->isEditing = true;
        
        $this->dispatch('$refresh'); // ← fuerza render
        $this->openModal();
    }



    // Eliminar un cargo
    public function delete($id)
    {
        Cargo::findOrFail($id)->delete();

        $this->dispatch('swal', [
            'title' => 'Cargo eliminado',
            'icon' => 'success'
        ]);
    }    
}
