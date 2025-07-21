<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\Setting;

class SettingPage extends Component
{

    use WithFileUploads;
    //Data untuk form
    public $name;
    public $address;
    public $telephone;
    public $number_of_tables;

    public $fileLogo;
    public $fileFavicon;

    public function mount(){
        $setting = Setting::first();
        if($setting){
            $this->fill([
                "id" => $setting->id,
                "name" => $setting->name,
                "address" => $setting->address,
                "telephone" => $setting->telephone,
                "number_of_tables" => $setting->number_of_tables,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pages.setting-page');
    }

    //Menyimpan data form
    public function save(){
        //upload gambar
        if($this->fileLogo){
            $this->fileLogo->storeAs('public/setting', 'logo.png');
        }
        if($this->fileFavicon){
            $this->fileFavicon->storeAs('public/setting', 'favicon.png');
        }

        //Menyimpan data
         $setting = Setting::first();
         if($setting){
            $setting->name = $this->name;
            $setting->address = $this->address;
            $setting->telephone = $this->telephone;
            $setting->number_of_tables = $this->number_of_tables;
            $setting->update();
         }

         $this->dispatch('show-message', msg:'Data berhasil disimpan');
    }
}
