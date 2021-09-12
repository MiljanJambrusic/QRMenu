<?php

namespace App\Http\Livewire;

use App\Helper\Helper;
use App\Models\Article;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PHPUnit\TextUI\Help;

class Articles extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $name;
    public $subcategory_id;
    public $category_id;
    public $category_name;
    public $subcategory_name;
    public $picture;
    public $akcija;
    public $cena;
    public $akcijska;
    public $modelId;
    public $term;
    public $opis;
    public $subcategory_list = [];


    protected $listeners = ['populateSubCategory'];

    /**
     * Livewire validation rules override
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('articles', 'name')],
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'cena' => 'required|gt:1',
            'picture' => 'required|image|max:2048',
        ];
    }

    /**
     * Livewire custom messages for errors in validation rules
     */
    protected $messages = [
        'name.required' => 'Polje za naziv ne sme ostati prazno.',
        'name.unique' => 'Polje naziv mora biti jedinstveno.',
        'picture.required' => 'Odaberite sliku za kategoriju.',
        'picture.max' => 'Slika ne sme prelaziti veličinu od 2 megabajta',
        'picture.image' => 'Odabrani fajl mora biti slika.',
        'category_id.required' => 'Morate odabrati kategoriju.',
        'subcategory_id.required' => 'Morate odabrati potkategoriju.',
        'cena.required' => 'Morate odabrati regularnu cenu.',
        'cena.gt' => 'Unesite validnu cenu, veću od 1.',
    ];

    public function populateSubCategory()
    {
        if ($this->category_id) {
            $this->subcategory_list = SubCategory::select('id', 'name')->where('category_id', '=', $this->category_id)->get();
        }
    }


    /**
     * Function for deleting an article.
     */
    public function delete(){
        Article::destroy($this->modelId);
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
    public function update()
    {
        $name_list = Article::where('name', '!=', $this->name)->pluck('name');
        $this->validate([
            'name' => ['required', Rule::notIn($name_list)],
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'cena' => 'required|gt:1',
        ]);
        Article::find($this->modelId)->update($this->modelDataForUpdate());
        $this->modalFormVisible = false;
    }

    /**
     * Show the form modal for creating or updating
     */
    public function updateShowModal($id)
    {
        $this->resetValidation();
        $this->reset(
            ['name','subcategory_id','category_name','category_id','subcategory_name','picture','akcija','akcijska','modelId','subcategory_list','cena','opis']);

        $this->modelId = $id;
        $this->modalFormVisible = true;
        $this->picture = "";
        $this->loadModel();

    }


    /**
     * Function creates new entry in table of Articles model
     */
    public function create()
    {
        $this->validate();
        $this->picture->storeAs('images/articles/', $this->name . ".png");
        Article::create($this->modelData());
        $this->modalFormVisible = false;
    }

    /**
     * Function that will serve as model opening for creating or updating this model
     */
    public function createShowModal()
    {
        $this->reset();
        $this->modalFormVisible = true;
    }

    /**
     * Loads the model data of this component.
     */
    public function loadModel()
    {
        $data = Article::find($this->modelId);
        $this->name = $data->name;
        $this->category_id = $data->category_id;
        $this->subcategory_id = $data->subcategory_id;
        $this->akcija = $data->akcija;
        $this->cena = $data->cena;
        $this->akcijska = $data->akcijska;
        $this->picture = $data->picture;
        $this->opis = $data->opis;
        $this->populateSubCategory();
    }


    /**
     * The data for the model mapped in this component.
     */
    public function modelDataForUpdate()
    {
        if (!$this->akcijska) {
            $this->akcijska = 0;
        }
        return [
            'name' => $this->name,
            'subcategory_id' => $this->subcategory_id,
            'category_id' => $this->category_id,
            'cena' => $this->cena,
            'akcijska' => $this->akcijska,
            'akcija' => $this->akcija,
            'opis'=>$this->opis
        ];
    }
    /**
     * The data for the model mapped in this component.
     */
    public function modelData()
    {
        if (!$this->akcijska) {
            $this->akcijska = 0;
        }
        if (!$this->akcija) {
            $this->akcija = 0;
        }
        return [
            'name' => $this->name,
            'subcategory_id' => $this->subcategory_id,
            'category_id' => $this->category_id,
            'cena' => $this->cena,
            'akcijska' => $this->akcijska,
            'akcija' => $this->akcija,
            'picture' => $this->name . ".png",
            'opis'=>$this->opis
        ];
    }


    public function getArticles(){
        $data = DB::table('articles')->join('categories', 'categories.id', '=', 'articles.category_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'articles.subcategory_id')
            ->select('articles.*', 'categories.name as category_name', 'sub_categories.name as subcategory_name')
            ->where('articles.name','like','%akcija 3%')
            ->paginate(10);
        echo $data;
        return view('livewire.articles',
            ['data' => $data]);
    }

    /**
     * The read function.
     */
    public function read()
    {
        if($this->term!=""){
            return DB::table('articles')
                ->join('categories', 'categories.id', '=', 'articles.category_id')
                ->join('sub_categories', 'sub_categories.id', '=', 'articles.subcategory_id')
                ->select('articles.*', 'categories.name as category_name', 'sub_categories.name as subcategory_name','categories.name as category_name')
                ->where('articles.name','like','%'.$this->term.'%')
                ->orWhere('sub_categories.name','like','%'.$this->term.'%')
                ->orWhere('categories.name','like','%'.$this->term.'%')
                ->paginate(10);
        }else{
            return DB::table('articles')->join('categories', 'categories.id', '=', 'articles.category_id')
                ->join('sub_categories', 'sub_categories.id', '=', 'articles.subcategory_id')
                ->select('articles.*', 'categories.name as category_name', 'sub_categories.name as subcategory_name')
                ->paginate(10);
        }

    }

    public function render()
    {
        return view('livewire.articles',
            ['data' => $this->read()]);
    }
}
