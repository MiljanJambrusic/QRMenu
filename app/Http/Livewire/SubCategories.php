<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\SubCategory;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SubCategories extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $name;
    public $picture;
    public $category_id;
    public $category_name;
    public $modelId;

    /**
     * Livewire validation rules override
     */
    public function rules(){
        return [
            'name'=>['required',Rule::unique('sub_categories','name')],
            'category_id'=>'required',
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
        'category_id.required'=>'Morate odabrati kategoriju.',
    ];


    /**
     * Function for deleting a category.
     */
    public function delete(){
        SubCategory::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    /**
     *  Shows the delete confirmation modal.
     */
    public function deleteShowModal($id)
    {
        $this->modelId = $id;
        $this->loadModel();
        $this->picture = "";
        $this->modalConfirmDeleteVisible = true;
    }


    /**
     * Update function takes inputs and updates changed data
     */
    public function update(){
        $name_list =SubCategory::where('name','!=',$this->name)->pluck('name');
        $this->validate([
            'name'=>['required',Rule::notIn($name_list)],
            'picture'=>'required|image|max:2048',
        ]);
        $this->picture->storeAs('images/sub_category/',$this->name.".png");
        SubCategory::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }
    public function updateOnlyName(){
        $name_list =SubCategory::where('name','!=',$this->name)->pluck('name');
        $this->validate([
            'name'=>['required',Rule::notIn($name_list)],
        ]);
        SubCategory::find($this->modelId)->update(['name'=>$this->name]);
        $this->modalFormVisible = false;
    }

    public function updateOnlyPicture(){
        $this->validate([
            'picture'=>'required|image|max:2048',
        ]);
        $this->picture->storeAs('images/sub_category/',$this->name.".png");
        SubCategory::find($this->modelId)->update(['picture'=>$this->name.".png",]);
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
        $this->picture->storeAs('images/sub_category/',$this->name.".png");
        SubCategory::create($this->modelData());
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
        $data = SubCategory::find($this->modelId);
        $data_category = Category::find($data->category_id);
        $this->name = $data->name;
        $this->picture = $data->picture;
        $this->category_name = $data_category->name;
    }

    /**
     * The data for the model mapped in this component.
     */
    public function modelData(){
        return [
            'name'=>$this->name,
            'category_id'=>$this->category_id,
            'picture'=>$this->name.".png",
        ];
    }

    /**
     * The read function.
     */
    public function read(){

        return DB::table('sub_categories')->join('categories','categories.id','=','sub_categories.category_id')->select('sub_categories.*','categories.name as category_name')->paginate(5);
    }

    public function render()
    {
        return view('livewire.sub-categories',[
            'data'=>$this->read(),
        ]);
    }
}
