<?php
$this->get('/adicionar-cliente', function($arg){
    $tpl = $this->core->loadModule('template');
    $login = $this->core->loadModule('login');
    $login->logado();

    $tpl->render('clientes/adicionar-cliente');
});

$this->post('/adicionar-cliente', function($arg){
    $login = $this->core->loadModule('login');
    $login->logado();

    $clientes = $this->core->loadModule('clientes');
    return $clientes->criarCliente();
});

$this->get('/editar-cliente/{id}', function($arg){
    $tpl = $this->core->loadModule('template');
    $login = $this->core->loadModule('login');
    $login->logado();

    $clientes = $this->core->loadModule('clientes');

    $array = array();
    $array['cliente'] = $clientes->obterCliente($arg['id']);

    $tpl->render('clientes/editar-cliente', $array);
});

$this->post('/editar-cliente/{id}', function($arg){
    $login = $this->core->loadModule('login');
    $login->logado();

    $clientes = $this->core->loadModule('clientes');

    return $clientes->alterarCliente($arg['id']);
});

$this->post('/deletar-cliente', function($arg){
    $login = $this->core->loadModule('login');
    $login->logado();

    $clientes = $this->core->loadModule('clientes');
    return $clientes->deletarCliente();
});