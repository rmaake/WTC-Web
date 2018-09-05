<?php
class Home extends Controller
{
    public function index($data)
    {
        $this->view('index', $data);
    }
}
?>