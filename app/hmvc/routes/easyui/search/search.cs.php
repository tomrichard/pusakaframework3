<?php 
use Pusaka\Easyui\Service\Component;

class SearchCS extends Component {
	
	public $text   = 1;
    public $result = "Type your search...";

    public function search()
    {
        
        if($this->text == "ya") {
            
            $this->result = "Found!";

        }else {
            
            $this->result = "Not Found!";
        
        }

    }

    public function render()
    {
    	return view($this->compact());
    }

}