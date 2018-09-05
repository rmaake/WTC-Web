<?php
class App
{
    protected $control = 'home';
    protected $method = 'index';
    protected $params = [];
    public function __construct()
    {
        $url = $this->parseUrl();
        if (file_exists('App/Controls/'. $url[0]. '.php'))
        {
            $this->control = $url[0];
            unset($url[0]);
        }
        require_once 'App/Controls/' .$this->control. '.php';

        $this->control = new $this->control;
        if (isset($url[1]))
        {
            if (method_exists($this->control, $url[1]))
            {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        if ($url)
            $this->params = array_values($url);
        call_user_func([$this->control, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if (isset($_GET['url']))
        {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode("/", $url);
            return ($url);
        }
    }
}
?>