<?php
$this->get('/enderecos/{id}', function($arg){
    $tpl = $this->core->loadModule('template');
    $login = $this->core->loadModule('login');
    $login->logado();

    $enderecos = $this->core->loadModule('enderecos');

    $array = array();
    $array['enderecos'] = $enderecos->obterEnderecos($arg['id']);
    $array['id_cliente'] = $arg['id'];

    $tpl->render('enderecos/enderecos', $array);
});

$this->get('/adicionar-endereco/{id}', function($arg){
    $tpl = $this->core->loadModule('template');
    $login = $this->core->loadModule('login');
    $login->logado();

    $array = array();
    $array['id_cliente'] = $arg['id'];

    $tpl->render('enderecos/adicionar-endereco', $array);
});

$this->post('/adicionar-endereco/{id}', function($arg){
    $login = $this->core->loadModule('login');
    $login->logado();

    $enderecos = $this->core->loadModule('enderecos');

    return $enderecos->criarEndereco($arg['id']);
});

$this->get('/editar-endereco/{cliente}/{id}', function($arg){
    $tpl = $this->core->loadModule('template');
    $login = $this->core->loadModule('login');
    $login->logado();

    $enderecos = $this->core->loadModule('enderecos');

    $array = array();
    $array['endereco'] = $enderecos->obterEndereco($arg['id'], $arg['cliente']);
    $array['id_cliente'] = $arg['cliente'];

    $tpl->render('enderecos/editar-endereco', $array);
});

$this->post('/editar-endereco/{cliente}/{id}', function($arg){
    $login = $this->core->loadModule('login');
    $login->logado();

    $enderecos = $this->core->loadModule('enderecos');

    return $enderecos->atualizarEndereco($arg['id']);
});

$this->post('/deletar-endereco', function($arg){
    $login = $this->core->loadModule('login');
    $login->logado();

    $enderecos = $this->core->loadModule('enderecos');

    return $enderecos->deletarEndereco();
});