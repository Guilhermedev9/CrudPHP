<?php 

Class pessoas{

	private $pdo;

//-------------------------------------------------------------------conexão com o banco--------------------------------------------------------------------
	public function __construct($dbname,$host,$user,$senha)
    {
   	  try
    {
     	$this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
    }
    catch (PDOExcepection $e) {
        echo "Erro com banco de dados: ".$e->getMessage();
       exit();
    }
    catch (Exception $e) {
        echo"Erro generico:".$e->getMessage();
        exit();  
    }
}

    //--------------------------------------FUNÇÂO PARA BUSCAR DADOS E COLOCAR NO CANTO DIREITO----------------------------------------
    public function buscarDados(){
    	$res = array();  
    	$cmd = $this->pdo->query("SELECT * FROM pessoas ORDER BY nome");
    	$res = $cmd->fetchALL(PDO::FETCH_ASSOC);
    	return $res;
    }
    public function cadastrarPessoas($nome, $telefone, $email)
    {
        //ANTES DE CADASTRAR VAMOS VERIFICAR SE JÁ TEM O EMAIL CADASTRADO
       $cmd = $this->pdo-> prepare("SELECT id from pessoas Where email = :e");
       $cmd->bindValue (":e",$email); 
       $cmd->execute();
       if ($cmd->rowCount() > 0)// email ja existe no banco
       {
        return false;
       }else
       {
        $cmd = $this->pdo->prepare("INSERT INTO pessoas (nome, telefone, email) VALUES (:n, :t, :e)");
        $cmd->bindValue(":n",$nome);
        $cmd->bindValue(":t",$telefone);
        $cmd->bindValue(":e",$email);
        $cmd->execute();
        return true;
       }
    }

    public function excluirPessoas($id){
        $cmd = $this->pdo->prepare("DELETE FROM pessoas WHERE id =:id");
        $cmd->bindValue(":id",$id);
        $cmd->execute(); 
    }

    //BUSCAR DADOS DE UMA PESSOA
    public function buscarDadosPessoas($id){

        $res = array();
        $cmd = $this->pdo->prepare("SELECT * FROM pessoas WHERE id = :id");
        $cmd->bindValue(":id",$id);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }



    //ATUALIZAR DADOS NO BANCO DE DADOS
    public function atualizarDados($id, $nome, $telefone, $email){

        $cmd = $this->pdo->prepare("UPDATE pessoas SET nome = :n, telefone = :t, email = :e WHERE id = :id");
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":t", $telefone);
        $cmd->bindValue(":e", $email);
        $cmd->bindValue(":id", $id);
        $cmd->execute();
        
    }
}
    ?>