<?php
class Enderecos
{

    private $db;

    private function __construct()
    {
        $core = Core::getInstance();
        $this->db = $core->loadModule('database'); // Carrega o módulo responsável pela conexão com banco de dados
    }

    /**
     * Apenas instancia a própria classe ao inicia-la no construct
     *
     * @return Enderecos|null
     */
    public static function getInstance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Enderecos();
        }
        return $inst;
    }

    /**
     * Obtem a lista de enderecos, faz a formatação do campo de endereço e retorna como array para a view
     *
     * @return array
     */
    public function obterEnderecos($id) {
        $array = array();

        $sql = $this->db->prepare("
            SELECT * FROM enderecos 
            JOIN cliente_enderecos
            ON cliente_enderecos.id_endereco = enderecos.id
            WHERE cliente_enderecos.id_cliente = :id
        ");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }

        foreach ($array as &$endereco){
            $endereco['endereco'] = $endereco['endereco'].', '.$endereco['numero'].' - '.$endereco['bairro'];
        }

        return $array;
    }

    /**
     * Valida os campos recebidos via post e tenta cadastrar um cliente ao banco de dados.
     */
    public function criarEndereco($id)
    {
        $campos = $_POST;

        $this->validaCampo($campos['endereco'],'Endereço');
        $this->validaCampo($campos['numero'],'Número');
        $this->validaCampo($campos['bairro'],'Bairro');
        $this->validaCampo($campos['cep'],'CEP');
        $this->validaCampo($campos['cidade'],'Cidade');
        $this->validaCampo($campos['estado'],'Estado');

        try {
            $sql = $this->db->prepare("INSERT INTO enderecos (endereco, numero, bairro, cep, cidade, estado) 
                                       VALUES (:endereco, :numero, :bairro, :cep, :cidade, :estado)");
            $sql->bindValue(":endereco", htmlspecialchars($campos['endereco']));
            $sql->bindValue(":numero", htmlspecialchars($campos['numero']));
            $sql->bindValue(":bairro", htmlspecialchars($campos['bairro']));
            $sql->bindValue(":cep", htmlspecialchars($campos['cep']));
            $sql->bindValue(":cidade", htmlspecialchars($campos['cidade']));
            $sql->bindValue(":estado", htmlspecialchars($campos['estado']));
            $sql->execute();

            $enderecoId = $this->db->lastInsertId();

            $sql = $this->db->prepare("INSERT INTO cliente_enderecos (id_cliente, id_endereco) VALUES (:clienteId, :enderecoId)");
            $sql->bindValue(":clienteId", $id);
            $sql->bindValue(":enderecoId", $enderecoId);
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
    public function atualizarEndereco($id)
    {
        $campos = $_POST;

        $this->validaCampo($campos['endereco'],'Endereço');
        $this->validaCampo($campos['numero'],'Número');
        $this->validaCampo($campos['bairro'],'Bairro');
        $this->validaCampo($campos['cep'],'CEP');
        $this->validaCampo($campos['cidade'],'Cidade');
        $this->validaCampo($campos['estado'],'Estado');

        try {
            $sql = $this->db->prepare("UPDATE enderecos SET endereco = :endereco, numero = :numero, bairro = :bairro, cep = :cep, cidade = :cidade, estado = :estado WHERE id = :id");
            $sql->bindValue(":endereco", htmlspecialchars($campos['endereco']));
            $sql->bindValue(":numero", htmlspecialchars($campos['numero']));
            $sql->bindValue(":bairro", htmlspecialchars($campos['bairro']));
            $sql->bindValue(":cep", htmlspecialchars($campos['cep']));
            $sql->bindValue(":cidade", htmlspecialchars($campos['cidade']));
            $sql->bindValue(":estado", htmlspecialchars($campos['estado']));
            $sql->bindValue(":id", $id);
            $sql->execute();

            echo json_encode(['sucesso' => true]);
        } catch (Exception $e){
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    /**
     * Retorna os dados de um endereço baseado em seu id, se o endereço não existir o redireciona à lista de endereços do cliente
     *
     * @return array
     */
    public function obterEndereco($id, $idCliente) {
        $array = array();

        $sql = $this->db->prepare("SELECT * FROM enderecos WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $array = $sql->fetch();
        } else {
            header('Location: /enderecos/'.$idCliente);
        }

        return $array;
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
     * Tenta deletar um endereço
     */
    public function deletarEndereco()
    {
        $id = $_POST['id'];

        try {
            $sql = $this->db->prepare("DELETE FROM enderecos WHERE id = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            $sql = $this->db->prepare("DELETE FROM cliente_enderecos WHERE id_endereco = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            echo json_encode(['sucesso' => true]);
        } catch (Exception $e){
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }
}