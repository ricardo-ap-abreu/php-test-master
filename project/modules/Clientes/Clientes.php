<?php
class Clientes {

	private $db;

	private function __construct() {
		$core = Core::getInstance();
		$this->db = $core->loadModule('database'); // Carrega o módulo responsável pela conexão com banco de dados
	}

    /**
     * Apenas instancia a própria classe ao inicia-la no construct
     *
     * @return Clientes|null
     */
	public static function getInstance() {
		static $inst = null;
		if($inst === null) {
			$inst = new Clientes();
		}
		return $inst;
	}

    /**
     * Obtem a lista de clientes, faz a formatação do campo de data e retorna como array para a view
     *
     * @return array
     */
	public function obterClientes() {
		$array = array();

		$sql = $this->db->query("SELECT * FROM clientes");
		if($sql->rowCount() > 0) {
			$array = $sql->fetchAll();
		}

		foreach ($array as &$cliente){
		    $cliente['nascimento'] = date('d/m/Y', strtotime($cliente['nascimento']));
        }

		return $array;
	}

    /**
     * Valida os campos recebidos via post e tenta cadastrar um cliente ao banco de dados.
     */
    public function criarCliente()
    {
        $campos = $_POST;

        $this->validaCampo($campos['nome'],'Nome');
        $this->validaCampo($campos['nascimento'],'Nascimento');
        $this->validaCampo($campos['telefone'],'Telefone');
        $this->validaCampo($campos['cpf'],'CPF');
        $this->validaCampo($campos['rg'],'RG');

        try {
            $sql = $this->db->prepare("INSERT INTO clientes (nome, nascimento, telefone, cpf, rg) VALUES (:nome, :nascimento, :telefone, :cpf, :rg)");
            $sql->bindValue(":nome", htmlspecialchars($campos['nome']));
            $sql->bindValue(":nascimento", htmlspecialchars($campos['nascimento']));
            $sql->bindValue(":telefone", htmlspecialchars($campos['telefone']));
            $sql->bindValue(":cpf", htmlspecialchars($campos['cpf']));
            $sql->bindValue(":rg", htmlspecialchars($campos['rg']));
            $sql->execute();

            echo json_encode(['sucesso' => true]);
        } catch (Exception $e){
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    /**
     * Valida os campos recebidos via post e tenta alterar um cliente ao banco de dados.
     *
     * @param $id
     */
    public function alterarCliente($id)
    {
        $campos = $_POST;

        $this->validaCampo($campos['nome'],'Nome');
        $this->validaCampo($campos['nascimento'],'Nascimento');
        $this->validaCampo($campos['telefone'],'Telefone');
        $this->validaCampo($campos['cpf'],'CPF');
        $this->validaCampo($campos['rg'],'RG');

        try {
            $sql = $this->db->prepare("UPDATE clientes SET nome = :nome, nascimento = :nascimento, telefone = :telefone, cpf = :cpf, rg = :rg WHERE id = :id");
            $sql->bindValue(":nome", htmlspecialchars($campos['nome']));
            $sql->bindValue(":nascimento", htmlspecialchars($campos['nascimento']));
            $sql->bindValue(":telefone", htmlspecialchars($campos['telefone']));
            $sql->bindValue(":cpf", htmlspecialchars($campos['cpf']));
            $sql->bindValue(":rg", htmlspecialchars($campos['rg']));
            $sql->bindValue(":id", $id);
            $sql->execute();

            echo json_encode(['sucesso' => true]);
        } catch (Exception $e){
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    /**
     * Tenta deletar um cliente
     */
    public function deletarCliente()
    {
        $id = $_POST['id'];

        try {
            $sql = $this->db->prepare("DELETE FROM clientes WHERE id = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            echo json_encode(['sucesso' => true]);
        } catch (Exception $e){
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    /**
     * Valida se um campo está vazio e retorna mensagem de erro caso positivo
     *
     * @param $campo
     * @param $nome
     */
    private function validaCampo($campo, $nome)
    {
        if(empty($campo)){
            echo json_encode(['erro' => 'Campo '.$nome.' é obrigatório.']);
            die();
        }
    }

    /**
     * Retorna os dados de um cliente baseado em seu id, se o cliente não existir o redireciona à dashboard
     *
     * @param $id
     * @return array
     */
    public function obterCliente($id) {
        $array = array();

        $sql = $this->db->prepare("SELECT * FROM clientes WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetch();
        } else {
            header('Location: /dashboard');
        }

        return $array;
    }
















}