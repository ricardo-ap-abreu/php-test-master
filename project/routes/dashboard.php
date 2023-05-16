<?php
$this->get('/dashboard', function($arg){
    $tpl = $this->core->loadModule('template');
    $login = $this->core->loadModule('login');
    $login->logado();

    $clientes = $this->core->loadModule('clientes');

    $array = array();
    $array['clientes'] = $clientes->obterClientes();

    $tpl->render('dashboard', $array);
});