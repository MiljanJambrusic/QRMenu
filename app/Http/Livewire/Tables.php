<?php

namespace App\Http\Livewire;

use App\Models\Table;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Tables extends Component
{
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $modalPreviewQR = false;
    public $name;
    public $num_seats;
    public $qr_num;
    public $modelId;
    public $qrlink;
    public $pre_link = "http://localhost:8000/qrmenu?qr=";


    /**
     * Livewire validation rules override
     */
    public function rules(){
        return [
            'name'=>['required',Rule::unique('tables','name')],
            'num_seats'=>'required|min:1',
            'qr_num'=>'required',
        ];
    }

    /**
     * Livewire custom messages for errors in validation rules
     */
    protected $messages = [
        'name.required' => 'Polje za naziv stola ne sme ostati prazno.',
        'num_seats.required' => 'Polje za unos broja stolica ne sme ostati prazno.',
        'qr_num.required' => 'Generišite novi qr kod.',
    ];

    /**
     * Function that corrects minimum value of seats for tables
     * If value is lower than 1, value will be set to 1 after click
     */
    public function updatedNumSeats(){
        if((integer)$this->num_seats<1){
            $this->num_seats = 1;
        }
    }

    public function generateQR(){
        $this->qr_num = Str::random();
    }


    /**
     * The read function.
     */
    public function read(){
        return Table::paginate(10);
    }


    public function generateQrCode()
    {
        QrCode::size(500)
            ->format('png')
            ->generate('facebook.com', public_path('images/qrcode.png'));
    }

    /**
     * Function creates new entry in table of Tables model
     */
    public function create(){
        $this->validate();
        Table::create($this->modelData());
        $this->modalFormVisible = false;
    }


    /**
     *  Shows the delete confirmation modal.
     */
    public function deleteShowModal($id){
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible= true;
    }

    /**
     * Shows the preview QR code modal.
     */
    public function previewQR($id){
        /*$this->generateQrCode();*/

        $this->modelId = $id;
        $data = Table::find($this->modelId);
        $this->qr_num = $data->qr_num;
        $this->name = $data->name;
        $this->qrlink = $this->pre_link.$this->qr_num;
        $this->modalPreviewQR= true;

    }

    /**
     * Function for deleting a table.
     */
    public function delete(){
        Table::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    /**
     * Update function takes inputs and updates changed data
     */
    public function update(){
        $this->validate();
        Table::find($this->modelId)->update($this->modelData());
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
    }

    /**
     * Loads the model data of this component.
     */
    public function loadModel(){
        $data = Table::find($this->modelId);
        $this->name = $data->name;
        $this->num_seats = $data->num_seats;
        $this->qr_num = $data->qr_num;
    }


    /**
     * The data for the model mapped in this component.
     */
    public function modelData(){
        return [
          'name'=>$this->name,
          'num_seats'=>$this->num_seats,
          'qr_num'=>$this->qr_num
        ];
    }

    /**
     * Function that will serve as model opening for creating or updating this model
     */
    public function createShowModal(){
        $this->reset();
        $this->modalFormVisible = true;
    }

    public function render()
    {
        return view('livewire.tables',[
            'data'=>$this->read(),
        ]);
    }
}
