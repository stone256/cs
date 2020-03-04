<?php
class mymodule_model_mymodel {
    function get_data() {
        $rs = array('greeting' => 'Hello:', 'url' => _X_URL . _url());
        return $rs;
    }
}
