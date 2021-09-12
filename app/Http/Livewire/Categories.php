<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class Categories extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $name;
    public $picture;
    public $modelId;
    public $link_img;



    /**
     * Livewire validation rules override
     */
    public function rules(){
        return [
            'name'=>['required',Rule::unique('categories','name')],
            'picture'=>'required|image|max:2048',
        ];
    }

    /**
     * Livewire custom messages for errors in validation rules
     */
    protected $messages = [
        'name.required' => 'Polje za naziv ne sme ostati prazno.',
        'name.unique'=>'Polje naziv mora biti jedinstveno.',
        'picture.required' => 'Odaberite sliku za kategoriju.',
        'picture.max'=>'Slika ne sme prelaziti veličinu od 2 megabajta',
        'picture.image'=>'Odabrani fajl mora biti slika.',
    ];


    /**
     * Function for deleting a category.
     */
    public function delete(){
        Category::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    /**
     *  Shows the delete confirmation modal.
     */
    public function deleteShowModal($id){
        $this->modelId = $id;
        $this->loadModel();
        $this->picture = "";
        $this->modalConfirmDeleteVisible= true;
    }

    /**
     * Update function takes inputs and updates changed data
     */
    public function update(){
        $name_list =Category::where('name','!=',$this->name)->pluck('name');
        $this->validate([
            'name'=>['required',Rule::notIn($name_list)],
            'picture'=>'required|image|max:2048',
        ]);
        $this->picture->storeAs('images/category/',$this->name.".png");
        Category::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    /**
     * Show the form modal for creating or updating
     */
    public function  updateShowModal($id){
        $this->resetValidation();
        $this->reset();
        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->loadModel();
        $this->picture = "";
    }

    /**
     * Function creates new entry in table of Category model
     */
    public function create(){
        $this->validate();
        $this->picture->storeAs('images/category/',$this->name.".png");
        Category::create($this->modelData());
        $this->modalFormVisible = false;
    }

    /**
     * Function that will serve as model opening for creating or updating this model
     */
    public function createShowModal(){
        $this->reset();
        $this->modalFormVisible = true;
    }

    /**
     * Loads the model data of this component.
     */
    public function loadModel(){
        $data = Category::find($this->modelId);
        $this->name = $data->name;
        $this->picture = $data->picture;
    }

    /**
     * The data for the model mapped in this component.
     */
    public function modelData(){
        return [
            'name'=>$this->name,
            'picture'=>$this->name.".png",
        ];
    }

    /**
     * The read function.
     */
    public function read(){
        return Category::paginate(5);
    }
    public function render()
    {
        return view('livewire.categories',[
            'data'=>$this->read(),
        ]);
    }
}
