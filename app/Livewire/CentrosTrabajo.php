<?php

namespace App\Livewire;

use App\Models\Colonia;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CentroTrabajo;
use App\Models\Municipio;

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
    public $municipio_nombre = null;
    public $municipio_id;
    public $calle;
    public $num_ext;
    public bool $activo = false;


    /** @var \Illuminate\Support\Collection */
    public $coloniasDelCP;
    public $municipios;

    protected $paginationTheme = 'tailwind';

    // Inicialización de los datos
    public function mount($centro_id = null)
    {
        if ($centro_id) {
            $ct = CentroTrabajo::find($centro_id);
            $this->coloniasDelCP = Colonia::where('codigo_postal', $ct->codigo_postal)->get();
            $this->colonia_id = $ct->colonia_id;
        } else {
            $this->coloniasDelCP = collect();
        }
        $this->municipios = collect();
    }

    // Reglas de validación
    public function rules()
    {
        return [
            'nombre_ct' => 'required|string|max:200',
            'clave_ct' => 'required|string|max:50',
            'codigo_postal' => 'required|numeric',
            'colonia_id' => 'required|exists:colonias,id',
            'calle' => 'nullable|string|max:200',
            'num_ext' => 'nullable|string|max:20',
        ];
    }

    // Mensajes personalizados para la validación
    public function messages()
    {
        return [
            'nombre_ct.required' => 'El campo Nombre es obligatorio.',
            'clave_ct.required' => 'El campo Clave de C.T. es obligatorio.',
            'codigo_postal.required' => 'El campo Código postal es obligatorio.',
            'codigo_postal.numeric' => 'El campo Código postal debe ser numérico.',
            'colonia_id.required' => 'El campo Colonia es obligatorio.',
        ];
    }

    // Método que se ejecuta cuando se actualiza el código postal
    public function updatedCodigoPostal($value)
    {
        $this->coloniasDelCP = Colonia::where('codigo_postal', $value)
            ->with('municipio')
            ->orderBy('nombre', 'asc')
            ->get();

        $this->municipios = $this->coloniasDelCP->pluck('municipio')->unique('id');

        $this->colonia_id = $this->coloniasDelCP->count() === 1 ? $this->coloniasDelCP->first()->id : null;
        $this->municipio_id = $this->municipios->count() === 1 ? $this->municipios->first()->id : null;
        $this->municipio_nombre = $this->municipios->count() === 1 ? $this->municipios->first()->nombre : null;
    }

    // Cuando se actualiza la búsqueda, reseteamos la página
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Abrir el modal de edición o creación
    public function openModal()
    {
        $this->resetErrorBag();
        $this->resetFields();
        $this->modalOpen = true;
    }

    // Resetear los campos
    public function resetFields()
    {
        $this->centro_id = null;
        $this->nombre_ct = '';
        $this->clave_ct = '';
        $this->codigo_postal = '';
        $this->colonia_id = null;
        $this->municipio_nombre = null;
        $this->municipio_id = null;
        $this->calle = '';
        $this->num_ext = '';
        $this->activo = false;
        $this->coloniasDelCP = collect();
        $this->municipios = collect();
    }

    // Cerrar el modal
    public function closeModal()
    {
        $this->modalOpen = false;
        $this->resetFields();
    }

    // Guardar el centro de trabajo
    public function save()
    {
        $this->validate();

        CentroTrabajo::updateOrCreate(
            ['id' => $this->centro_id],
            [
                'nombre_ct' => strtoupper(mb_convert_encoding($this->nombre_ct, 'UTF-8', 'auto')),
                'clave_ct' => strtoupper(mb_convert_encoding($this->clave_ct, 'UTF-8', 'auto')),
                'codigo_postal' => $this->codigo_postal,
                'colonia_id' => $this->colonia_id,
                'calle' => strtoupper(mb_convert_encoding($this->calle, 'UTF-8', 'auto')),
                'numero_exterior' => $this->num_ext,
                'activo' => $this->activo,
            ]
        );

        $this->dispatch('swal', [
            'title' => $this->centro_id ? 'Centro actualizado' : 'Centro creado',
            'icon' => 'success'
        ]);

        $this->closeModal();
    }

    // Editar un centro de trabajo
    public function edit($id)
    {
        $ct = CentroTrabajo::findOrFail($id);
        
        $this->centro_id = $ct->id;
        $this->nombre_ct = $ct->nombre_ct;
        $this->clave_ct = $ct->clave_ct;
        $this->codigo_postal = $ct->codigo_postal;
        $this->coloniasDelCP = Colonia::where('codigo_postal', $ct->codigo_postal)->get();
        $this->colonia_id = $ct->colonia_id;
        $this->municipio_nombre = $ct->colonia->municipio->nombre ?? null;
        $this->calle = $ct->calle;
        $this->num_ext = $ct->numero_exterior;
        $this->activo = $ct->activo;
        // dd($this->activo);
        $this->modalOpen = true;
    }

    // Eliminar un centro de trabajo
    public function delete($id)
    {
        CentroTrabajo::findOrFail($id)->delete();

        $this->dispatch('swal', [
            'title' => 'Centro eliminado',
            'icon' => 'success'
        ]);
    }

    // Renderizar la vista
    public function render()
    {
        $centros = CentroTrabajo::with('colonia.municipio')
            ->where('nombre_ct', 'like', "%{$this->search}%")
            ->orWhere('clave_ct', 'like', "%{$this->search}%")
            ->paginate(30);

        return view('livewire.centros-trabajo', [
            'centros' => $centros
        ]);
    }
}
