<?php

namespace App\Livewire;

use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;

class Regiones extends Component
{
    use WithPagination;

    public $region_id, $region, $sede, $search = '';
    public $modalOpen = false;

    protected $rules = [
        'region' => 'required|string|max:255',
        'sede' => 'required|string|max:255',
    ];
    
    public function render()
    {
        $regiones = Region::where('region', 'like', '%' . $this->search . '%')
            ->orWhere('sede', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.regiones',[
            'regiones' => $regiones
        ])->layout('layouts.app');
    }

    public function openModal()
    {
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
    }

    public function resetInputFields()
    {
        $this->region = '';
        $this->sede = '';
        $this->region_id = null;
    }

    public function save()
    {
        $this->validate();

        Region::updateOrCreate(['id' => $this->region_id], [
            'region' => $this->region,
            'sede' => $this->sede,
        ]);

        // session()->flash('message', 
        //     $this->region_id ? 'Región actualizada exitosamente.' : 'Región creada exitosamente.');

        // Dispatch a SweetAlert event    
        $this->dispatch('swal', [
                'title' => $this->region_id ? 'Región actualizada' : 'Región creada',
                'icon' => 'success'
            ]);            

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $region = Region::findOrFail($id);
        $this->region_id = $id;
        $this->region = $region->region;
        $this->sede = $region->sede;

        $this->openModal();
    }
    public function delete($id)
    {
        Region::find($id)->delete();
        // session()->flash('message', 'Región eliminada exitosamente.');

        $this->dispatch('swal', [
            'title' => 'Región eliminada correctamente',
            'icon' => 'success'
        ]);        
    }

}
