<?php
class home extends core {
    
    public function __construct() {
    }

    public function index($someParameter='') {
        sanitze($someParameter);
        
        //load the class 'calculator' into $this->calc
        $this->load('calculator', 'calc');
        
        //Some calculator values
        $valueOne = 3;
        $valueTwo = 4;
        $sum = $this->calc->sum($valueOne, $valueTwo);
        
        //Load the view and pass some variables to it
        $this->data['content'] = $this->view('main/frontpage', array(
            'valueOne'      => $valueOne,
            'valueTwo'      => $valueTwo,
            'sum'           => $sum,
            'someParameter' => $someParameter
        ));
    }
    
}
?>