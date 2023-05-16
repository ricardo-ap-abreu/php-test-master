<?php
class Login
{
    private $db;
    private $helper;

    private function __construct()
    {
        $core = Core::getInstance();
        $this->db = $core->loadModule('database'); // Carrega o módulo responsável pela conexão com banco de dados
        $this->helper = $core->loadModule('helper'); // Carrega o arquivo helper responsável por hash da senha
    }

    /**
     * Apenas instancia a própria classe ao inicia-la no construct
     *
     * @return Login|null
     */
    public static function getInstance() {
        static $inst = null;
        if($inst === null) {
            $inst = new Login();
        }
        return $inst;
    }

    /**
     * Valida os campos recebidos via post e tenta cadastrar um usuário ao banco de dados.
     */
    public function criarUsuario()
    {
        $campos = $_POST;

        $this->validaNome($campos['nome']);
        $this->validaSenha($campos['senha'], $campos['resenha']);
        $this->validaEmail($campos['email']);

        try {
            $sql = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $sql->bindValue(":nome", htmlspecialchars($campos['nome']));
            $sql->bindValue(":email", htmlspecialchars($campos['email']));
            $sql->bindValue(":senha", $this->helper->hash($campos['senha']));
            $sql->execute();

            $_SESSION['login'] = $campos['email'];

            echo json_encode(['sucesso' => true]);
        } catch (Exception $e){
            echo json_encode(['erro' => $e->getMessage()]);
        }

    }

    /**
     * Verifica se o usuário está logado e o redireciona para a página de login caso não esteja
     */
    public function logado()
    {
        if (!$_SESSION['login']){
            header('Location: /');
        }
    }

    /**
     * Desloga o usuário e o leva de volta á página de login
     */
    public function deslogarUsuario()
    {
        unset($_SESSION['login']);
        header('Location: /');
    }

    /**
     * Efetua a ação de validar os dados e logar o usuário
     */
    public function logarUsuario()
    {
        $campos = $_POST;

        $usuario = $this->validaEmailLogin($campos['email']);
        $this->validaSenhaLogin($campos['senha'], $usuario['senha']);

        $_SESSION['login'] = $campos['email'];

        echo json_encode(['sucesso' => true]);
    }

    /**
     * Obtem dados de um cliente
     *
     * @return mixed
     */
    public function obterCliente()
    {
        $sql = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $sql->bindValue(":email", $_SESSION['login']);
        $sql->execute();

        return $sql->fetch();
    }

    /**
     * Valida os campos recebidos via post e tenta cadastrar um usuário ao banco de dados.
     */
    public function alterarUsuario()
    {
        $campos = $_POST;

        $this->validaNome($campos['nome']);
        if($campos['senha']) {
            $this->validaSenha($campos['senha'], $campos['resenha']);
        }

        try {
            $sql = $this->db->prepare("UPDATE usuarios SET nome = :nome WHERE email = :email");

            if($campos['senha']) {
                $sql = $this->db->prepare("UPDATE usuarios SET nome = :nome, senha = :senha WHERE email = :email");
                $sql->bindValue(":senha", $this->helper->hash($campos['senha']));
            }
            
            $sql->bindValue(":nome", htmlspecialchars($campos['nome']));
            $sql->bindValue(":email", htmlspecialchars($campos['email']));
            $sql->execute();

            $_SESSION['login'] = $campos['email'];

            echo json_encode(['sucesso' => true]);
        } catch (Exception $e){
            echo json_encode(['erro' => $e->getMessage()]);
        }

    }

    /**
     * Verifica se os campos de senha e confirmação são compatíveis
     *
     * @param $senha
     * @param $confirmacao
     */
    private function validaSenha($senha, $confirmacao)
    {
        if($senha !== $confirmacao){
            echo json_encode(['erro' => 'Senha e confirmação devem ser iguais.']);
            die();
        }
    }

    /**
     * Verifica se o campo nome veio preenchido
     *
     * @param $nome
     */
    private function validaNome($nome)
    {
        if(empty($nome)){
            echo json_encode(['erro' => 'Campo nome é obrigatório.']);
            die();
        }
    }

    /**
     * Verifica se o e-mail já foi cadastrado no sistema para evitar redundância
     *
     * @param $email
     */
    private function validaEmail($email)
    {
        $sql = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $sql->bindValue(":email", htmlspecialchars($email));
        $sql->execute();

        if($sql->rowCount() > 0) {
            echo json_encode(['erro' => 'E-mail já cadastrado no sistema.']);
            die();
        }
    }

    /**
     * Retorna os dados de usuário se o e-mail existir no banco
     *
     * @param $email
     * @return mixed
     */
    private function validaEmailLogin($email)
    {
        $sql = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $sql->bindValue(":email", htmlspecialchars($email));
        $sql->execute();

        if($sql->rowCount() === 0) {
            echo json_encode(['erro' => 'E-mail não cadastrado no sistema']);
            die();
        }

        return $sql->fetch();
    }

    /**
     * Verifica o hash do campo senha do registro do banco, com a senha digitada pelo usuário para validar se são compatíveis.
     *
     * @param $senha
     * @param $hash
     */
    private function validaSenhaLogin($senha, $hash)
    {
        if(!$this->helper->check($senha, $hash)){
            echo json_encode(['erro' => 'Senha incorreta!']);
            die();
        }
        return;
    }
}
