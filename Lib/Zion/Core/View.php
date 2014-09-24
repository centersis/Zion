<?php

namespace Zion\Core;

class View
{

    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function __destruct()
    {
        echo '<p><a href="?action=clicked">' . $this->model->string . "</a></p>";
    }

}
