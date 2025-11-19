<?php

namespace App\Livewire;

use App\Models\Colonia;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CentroTrabajo;

class CentrosTrabajo extends Component
{
    use WithPagination;
    public $search = '';
    public $modalOpen = false;

    public $centro_id;
    public $nombre_ct;
    public $clave_ct;
    public $codigo_postal;
    public $colonia_id;
    public $municipio;
    public $calle;
    public $numero_exterior;
    public $activo = true;

    

    public $coloniasDelCP;
    
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedCodigoPostal()
    {       
        // Limpia el municipio cuando cambia el código postal
        $this->municipio = null;

        // Actualiza las colonias según el nuevo código postal
        $this->coloniasDelCP = Colonia::where('codigo_postal', $this->codigo_postal)->get();

        // Si solo hay una colonia, asignamos automáticamente el municipio y colonia
        if ($this->coloniasDelCP->count() == 1) {
            $this->colonia_id = $this->coloniasDelCP->first()->id;
            $this->municipio = $this->coloniasDelCP->first()->municipio->nombre;
        } else {
            $this->colonia_id = null;  // Restablece la colonia si no hay una única opción
        }        
    }

    public function updatedColoniaId($value)
    {
        // if ($this->colonia_id) {
        //     $colonia = Colonia::find($this->colonia_id);
        //     if ($colonia) {
        //         $this->municipio = $colonia->municipio->nombre;
        //     }
        // }

        $colonia = Colonia::find($value);

        if ($colonia) {
            $this->municipio = $colonia->municipio->nombre;
        }

        // dd("Sí se ejecutó updatedColoniaId con valor: $value");
        
        
    }    

    public function openModal()
    {
        $this->resetFields();
        $this->modalOpen = true;
    }

    public function resetFields()
    {
        $this->centro_id = null;
        $this->nombre_ct = '';
        $this->clave_ct = '';
        $this->codigo_postal = '';
        $this->coloniasDelCP = [];
        $this->colonia_id = null;
        $this->municipio = null;
        $this->calle = '';
        $this->numero_exterior = '';
        $this->activo = true;
    }    

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->resetFields();
    }    

    public function edit($id)
    {
        $ct = CentroTrabajo::findOrFail($id);

        $this->centro_id = $ct->id;
        $this->nombre_ct = $ct->nombre_ct;
        $this->clave_ct = $ct->clave_ct;
        $this->codigo_postal = $ct->codigo_postal;

        $this->coloniasDelCP = Colonia::where('codigo_postal', $ct->codigo_postal)->get();
        $this->colonia_id = $ct->colonia_id;
        $this->municipio = $ct->colonia->municipio->nombre ?? null;

        $this->calle = $ct->calle;
        $this->numero_exterior = $ct->numero_exterior;
        $this->activo = $ct->activo;

        $this->modalOpen = true;
    }


    public function save()
    {
        // dd([
        //     'nombre_ct' => $this->nombre_ct,
        //     'clave_ct' => $this->clave_ct,
        //     'codigo_postal' => $this->codigo_postal,
        //     'colonia_id' => $this->colonia_id,
        // ]);

        $this->validate([
            'nombre_ct' => 'required|string|max:200',
            'clave_ct' => 'required|string|max:50',
            'codigo_postal' => 'required|string|max:5',
            'colonia_id' => 'required|exists:colonias,id',
            'calle' => 'nullable|string|max:200',
            'numero_exterior' => 'nullable|string|max:20',
        ]);

        CentroTrabajo::updateOrCreate(
            ['id' => $this->centro_id],
            [
                'nombre_ct' => strtoupper(mb_convert_encoding($this->nombre_ct, 'UTF-8', 'auto')),
                'clave_ct' => strtoupper(mb_convert_encoding($this->clave_ct, 'UTF-8', 'auto')),
                'codigo_postal' => $this->codigo_postal,
                'colonia_id' => $this->colonia_id,
                'calle' => strtoupper(mb_convert_encoding($this->calle, 'UTF-8', 'auto')),
                'numero_exterior' => $this->numero_exterior,
                'activo' => $this->activo,
            ]
        );

        $this->dispatch('swal', [
            'title' => $this->centro_id ? 'Centro actualizado' : 'Centro creado',
            'icon' => 'success'
        ]);

        $this->closeModal();
    }

    public function delete($id)
    {
        CentroTrabajo::findOrFail($id)->delete();

        $this->dispatch('swal', [
            'title' => 'Centro eliminado',
            'icon' => 'success'
        ]);
    }    

    public function render()
    {
        $centros = CentroTrabajo::with('colonia.municipio')
            ->where('nombre_ct', 'like', "%{$this->search}%")
            ->orWhere('clave_ct', 'like', "%{$this->search}%")
            ->paginate(30);

        return view('livewire.centros-trabajo',[
            'centros' => $centros
        ]);
    }
}
