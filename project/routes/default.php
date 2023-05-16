<?php
$this->get('', function($arg){
    $tpl = $this->core->loadModule('template');

    $tpl->render('home');
});

$this->post('', function($arg){
    $login = $this->core->loadModule('login');
    return $login->logarUsuario();
});

$this->get('/criar-usuario', function($arg){
    $tpl = $this->core->loadModule('template');

    $tpl->render('criar-usuario');
});

$this->post('/criar-usuario', function($arg){
    $login = $this->core->loadModule('login');
    return $login->criarUsuario();
});

$this->get('/perfil', function($arg){
    $tpl = $this->core->loadModule('template');
    $login = $this->core->loadModule('login');
    $login->logado();

    $array = array();
    $array['cliente'] = $login->obterCliente();

    $tpl->render('perfil', $array);
});

$this->post('/perfil', function($arg){
    $login = $this->core->loadModule('login');
    $login->logado();

    return $login->alterarUsuario();
});

$this->get('/logout', function($arg){
    $login = $this->core->loadModule('login');
    $login->deslogarUsuario();
});

$this->loadRouteFile('dashboard');
$this->loadRouteFile('clientes');
$this->loadRouteFile('enderecos');
