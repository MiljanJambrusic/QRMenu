<?php

namespace App\Http\Livewire;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \App\Actions\Fortify\PasswordValidationRules;

class Users extends Component
{
    use PasswordValidationRules;
    use WithPagination;
    public $modalFormVisible = false;
    public $modalConfirmDeleteVisible = false;
    public $name;
    public $email;
    public $role;
    public $password;
    public $modelId;

    public $pwVissible = "password";



    /**
     * Livewire validation rules override
     */
    public function rules(){
        return [
            'name'=>['required'],
            'email'=>['required',Rule::unique('users','email')],
            'password'=>['required',$this->passwordRules()],
            'role'=>'required',
        ];
    }

    /**
     * Livewire custom messages for errors in validation rules
     */
    protected $messages = [
        'name.required' => 'Polje za naziv ne sme ostati prazno.',
        'name.unique' => 'Naziv mora biti jedinstven.',
        'email.required' => 'Polje za email adresu ne sme ostati prazno.',
        'password.required' => 'Molimo vas unesite šifru koju će korisnik koristiti.',
        'role.required'=>'Odaberite privilegije za korisnika.'
    ];


    /**
     * Function creates new entry in table of Tables model
     */
    public function create(){
        $this->validate();
        User::create($this->modelData());
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
     * Function that updates users email
     */
    public function updateEmail(){
        $this->validate([
            'name'=>['required',Rule::unique('users','name')]
        ]);
        User::find($this->modelId)->update(['name'=>$this->name]);
        $this->modalFormVisible = false;
    }
    /**
     * Function that updates users password
     */
    public function updatePassword(){
        $this->validate([
            'password'=>['required',$this->passwordRules()],
        ]);
        User::find($this->modelId)->update(['password'=>Hash::make($this->password)]);
        $this->modalFormVisible = false;
    }
    /**
     * Function that updates users role
     */
    public function updateRole(){
        $this->validate([
            'role'=>['required'],
        ]);
        User::find($this->modelId)->update(['role'=>$this->role]);
        $this->modalFormVisible = false;
    }
    /**
     * Function that updates users name
     */
    public function updateName(){
        $this->validate([
            'name'=>['required',Rule::unique('users','name')]
        ]);
        User::find($this->modelId)->update(['name'=>$this->name]);
        $this->modalFormVisible = false;
    }
    /**
     * Update function takes inputs and updates changed data
     */
    public function update(){
        $email_list =User::where('email','!=',$this->email)->pluck('email');
        $this->validate([
            'name'=>['required'],
            'email'=>['required',Rule::notIn($email_list)],
            'password'=>['required',$this->passwordRules()],
            'role'=>'required',
        ]);
        User::find($this->modelId)->update($this->modelData());
        $this->modalFormVisible = false;
    }

    /**
     * Function for deleting a table.
     */
    public function delete(){
        User::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
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
     *  Shows the delete confirmation modal.
     */
    public function deleteShowModal($id){
        $this->modelId = $id;
        $this->loadModel();
        $this->modalConfirmDeleteVisible= true;
    }

    /**
     * Loads the model data of this component.
     */
    public function loadModel(){
        $data = User::find($this->modelId);
        $this->name = $data->name;
        $this->email = $data->email;
        $this->role = $data->role;
    }


    /**
     * The read function.
     */
    public function read(){
        return User::where('role','<>','sadmin')->paginate(5);
    }
    /**
     * The data for the model mapped in this component.
     */
    public function modelData(){
        return [
            'name'=>$this->name,
            'email'=>$this->email,
            'password'=>Hash::make($this->password),
            'role'=>$this->role,
            'current_team_id'=>1,
        ];
    }

    public function render()
    {
        return view('livewire.users',[
            'data'=>$this->read(),
        ]);
    }

}
