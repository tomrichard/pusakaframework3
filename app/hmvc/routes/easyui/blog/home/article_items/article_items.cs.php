<?php 
use Pusaka\Easyui\Service\Component;

class CounterCS extends Component {
	
	public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function render()
    {
    	return view($this->compact());
    }

}