<?php

namespace App\Livewire;

use App\Models\NivelAcademico;
use Livewire\Component;
use Livewire\WithPagination;

class NivelesAcademicos extends Component
{
    use WithPagination;

    public $search = '';
    public $modalOpen = false;

    public $nivel_id;
    public $nombre;
    public bool $activo = true;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $niveles = NivelAcademico::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'asc')
            ->paginate(20);

        return view('livewire.niveles-academicos', [
            'niveles' => $niveles,
        ]);
    }

    /** Nuevo nivel */
    public function create()
    {
        $this->resetInput();
        $this->modalOpen = true;
    }

    /** limpia inputs */
    public function resetInput()
    {
        $this->nivel_id = null;
        $this->nombre = '';
        $this->activo = true;

        $this->resetValidation();
    }

    /** Editar */
    public function edit($id)
    {
        $nivel = NivelAcademico::findOrFail($id);

        $this->nivel_id = $nivel->id;
        $this->nombre = $nivel->nombre;
        $this->activo = $nivel->activo;

        $this->modalOpen = true;
    }

    /** Guardar o actualizar */
    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'activo' => 'boolean',
        ]);

        NivelAcademico::updateOrCreate(
            ['id' => $this->nivel_id],
            [
                'nombre' => mb_strtoupper($this->nombre, 'UTF-8'),
                'activo' => $this->activo,
            ]
        );

        $this->dispatch('swal', [
            'title' => $this->nivel_id ? 'Nivel actualizado' : 'Nivel creado',
            'icon' => 'success',
        ]);

        $this->modalOpen = false;
        $this->resetInput();
    }

    /** Eliminar */
    public function delete($id)
    {
        NivelAcademico::findOrFail($id)->delete();

        $this->dispatch('swal', [
            'title' => 'Nivel eliminado',
            'icon' => 'success',
        ]);
    }
}
