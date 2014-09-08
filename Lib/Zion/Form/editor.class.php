<?php
class Editor
{
    public $InstanceName;
    public $Width;
    public $Height;
    public $ToolbarSet;
    public $Value;
    public $Config;
    public $Rand;

    public function __construct()
    {
        $this->Config = array();
        $this->Rand = mt_rand();
    }

    public function CreateHtml()
    {
        if(is_numeric($this->Width))
            $this->Width.= 'px';
        
        if(is_numeric($this->Height))
            $this->Height.= 'px';
        
        $Html .='
        <textarea name="'.$this->InstanceName.$this->Rand.'" id="'.$this->InstanceName.$this->Rand.'">'.$this->Value.'</textarea>
        <input type="hidden" name="'.$this->InstanceName.'" id="'.$this->InstanceName.'" value="" class="sis_ck" referencia="'.$this->InstanceName.$this->Rand.'" />
        <script> CKEDITOR.replace( \''.$this->InstanceName.$this->Rand.'\', {fullPage: false,allowedContent: true,extraPlugins: \'wysiwygarea\',
        height:\''.$this->Height.'\',width:\''.$this->Width.'\'});</script>    
        ';
        
        return $Html;
    }
}