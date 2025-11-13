<?php

namespace App\Livewire;

use App\Models\Region;
use Livewire\Component;
use App\Models\Delegacion;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Delegaciones extends Component
{
    use WithPagination;
    public $delegacion_id, $region_id, $delegacion, $nivel, $sede;
    public $search = '', $modalOpen = false;

    protected $rules = [
        'region_id' => 'required|exists:regiones,id',
        'delegacion' => 'required|string|max:100',
        'nivel' => 'required|string|max:100',
        'sede' => 'required|string|max:100',
    ];

    public function mount()
    {
        //
    }

    public function render()
    {
        $query = Delegacion::with('region')
            ->where(function($q){
                $q->where('delegacion','like',"%{$this->search}%")
                    ->orWhere('nivel','like',"%{$this->search}%")
                    ->orWhere('sede','like',"%{$this->search}%");
            });

        $delegaciones = $query->orderBy('delegacion','asc')->paginate(25);
        $regiones = Region::orderBy('region','asc')->get();

        return view('livewire.delegaciones',[
            'delegaciones' => $delegaciones,
            'regiones' => $regiones
        ]);
    }

    public function openModal()
    {
        $this->resetInput();
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
    }

    public function save()
    {
        $this->validate();

        Delegacion::updateOrCreate(
            ['id' => $this->delegacion_id],
            [
                'region_id' => $this->region_id,
                'delegacion' => $this->delegacion,
                'nivel' => $this->nivel,
                'sede' => $this->sede,
            ]
        );

        $this->dispatch('swal', [
            'title' => $this->delegacion_id ? 'Delegación actualizada' : 'Delegación creada',
            'icon' => 'success'
        ]);

        $this->closeModal();
        $this->resetInput();        
    }

    public function edit($id)
    {
        $d = Delegacion::findOrFail($id);
        $this->delegacion_id = $d->id;
        $this->region_id = $d->region_id;
        $this->delegacion = $d->delegacion;
        $this->nivel = $d->nivel;
        $this->sede = $d->sede;
        $this->modalOpen = true;
    }

    public function delete($id)
    {
        Delegacion::find($id)?->delete();

        $this->dispatch('swal', [
            'title' => 'Delegación eliminada',
            'icon' => 'success'
        ]);
    }
        
    private function resetInput()
    {
        $this->delegacion_id = null;
        $this->region_id = null;
        $this->delegacion = '';
        $this->nivel = '';
        $this->sede = '';
    }    
}