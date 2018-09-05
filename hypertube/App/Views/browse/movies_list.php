<?php
require_once 'app/models/Movies.php';

$mv = new Movies();
if ($mv->state !== FALSE)
{
    if (isset($_GET['search']))
        $mv->search($_GET['search']);
    else
        $mv->get_movies();
}
?>