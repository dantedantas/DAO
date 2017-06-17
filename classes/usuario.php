<?php 

/*

CRIANDO A CLASSE USUÁRIO

Essa é a classe da tabela já existente no Banco de Dados dbphp7. Serão criados os atributos das colulas e métodos para acessa-los.

*/

class Usuario {

	//Atributos
	private $idusuario;
	private $deslogin;
	private $dessenha;
	private $dtcadastro;

	//Métodos Getters e Setters
	public function getIdUsuario (){
		return $this->idusuario;
	}

	public function getDesLogin (){
		return $this->deslogin;
	}

	public function getDesSenha (){
		return $this->dessenha;
	}

	public function getDtCadastro (){
		return $this->dtcadastro;
	}

	public function setIdUsuario($value){
		$this->idusuario = $value;
	}

	public function setDesLogin($value){
		$this->deslogin = $value;
	}

	public function setDesSenha($value){
		$this->dessenha = $value;
	}

	public function setDtCadastro($value){
		$this->dtcadastro = $value;
	}

	//Outros métodos

	//Abaixo, um método que recebe como parâmetro a ID de um usuário e faz um SELECT dos outros atributos daquele ID. Este será o método responsável por tirar os atributos do banco e colocar nos atributos da classe.

	public function loadById($id){
		//Instanciando a classe Sql
		$sql = new Sql();

		//Usando o método da classe Sql, que criamos para fazer selects
		$result = $sql->select("SELECT * FROM tb_usuario WHERE idusuario = :ID", array(
			//Lembrando de como se cria um array com referências: Usando igual e sinal de maior.
				":ID" => $id
			));

		/*

		Esse método seleciona todas as linhas que tem um determinado idusuario, ou seja, só selecionará uma linha, pois idusuario é uma chave primária.

		Mas mesmo só selecinando uma linha, o método select() que criamos na classe Sql retornará um array de arrays, pois assim o definimos. 

		*/

		//Agora, precisamos conferir se esse SELECT realmente retornou algo. Talvez o ID pesquisado não exista. Faremos isso com um IF e usando o método ISSET (se não é nulo, retorna true)

		if (isset($result[0])){
			//Como é uma matriz, sua posição 0 tem um array que é a linha que solicitamos.

			//Os campos encontrados serão setados nos atributos da classe através do método setData
			$this->setData($result[0]);
		}
	}

	//Abaixo, criaremos o método mágico __toString. Esse método formata os atributos quando eles forem convertidos para String

	public function __toString (){
		//Neste caso, ele retornará um arquivo JSON, que conterá um array com as informações
		return json_encode(array(
			"idusuario"=>$this->getIdUsuario(),
			"deslogin"=>$this->getDesLogin(),
			"dessenha"=>$this->getDesSenha(),
			"dtcadastro"=>$this->getDtCadastro()->format("d/m/Y H:i:s")
			));
	}

	//O método estático abaixo retorna uma lista com todos os usuários da tabela

	public static function getList (){
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_usuario ORDER BY idusuario;");
	}

	//Criando um novo método estático para pesquisar por um determinado login no Banco. O login que será pesquisado é passado como parâmetro:

	public static function search($login){
		//Instanciando a classe Sql
		$sql = new Sql();

		//Abaixo faremos um comando SELECT que seleciona tudo da tabela usuário onde o deslogin é de acordo com o conteúdo do id SEARCH, ordenando pelo deslogin.
		return $sql->select("SELECT * FROM tb_usuario WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(
			//Qual é o conteúdo do id SEARCH? Colocamos que é tudo o que contenha o login passado como parâmetro, não importanto o início nem o fim, mas sim um conteúdo. Como isso é uma pesquisa, pode ser que não saia exatamente igual, por isso não seremos exigentes.
			":SEARCH" => "%" .$login . "%"
			));
	}

	//A função abaixo será responsável por realizar um login. Recebendo como parâmetro o login e a senha, retornará os dados do usuário.
	public function login($login, $password){
		$sql = new Sql();

		//Abaixo, colocaremos na variável user o resultado da pesquisa por login e senha
		
		$results = $sql->select("SELECT * FROM tb_usuario WHERE deslogin = :LOGIN AND dessenha = :PASSWORD", array(
			":LOGIN"=>$login,
			":PASSWORD"=>$password
		));

		//Faremos um if para verificar se a pesquisa realmente retornou algo
		if (count($results) > 0) {
		 	
		 	//Agora, passamos a primeira linha da matriz results como parâmetro para o método local setData
			$this->setData($results[0]);
		} else {
			//Pode ser que os parâmetros passados para login estejam errados. Por isso colocaremos um erro neste else
			throw new Exception("Login e/ou senha inválidos.");
		}
	}



	//O método setData recebe como parâmetro um array de dados, e coloca-os nos atributos do objeto, com os métodos set.
	public function setData($data){
		$this->setIdUsuario($data['idusuario']);
		$this->setDesLogin($data['deslogin']);
		$this->setDesSenha($data['dessenha']);
		$this->setDtCadastro(new DateTime ($data['dtcadastro']));
		//No setDtCadastro foi usado o método DateTime para que a data venha formatada
	}



	//Método de Insert: Criando um novo usuário
	public function insert(){
		$sql = new Sql();

		/*

		Em vez de usarmos o método query, usaremos o select e passaremos como parâmetro uma procedure, que irá inserir o LOGIN e a SENHA dos IDs. 
		
		Criaremos essa procedure no Banco de Dados (Uma procedure é como uma função em MySQL que contém um conjunto de instruções). Quando a procedure executar, no fim de sua execução ela chamará uma função no Banco de Dados que irá retornar qual foi o ID gerado na tabela. Ou seja, teremos todos os valores do novo usuário criado...
		*/
		$results = $sql->select("CALL sp_usuario_insert(:LOGIN, :SENHA)", array(
				":LOGIN"=>$this->getDesLogin(),
				":SENHA"=>$this->getDesSenha()
			));

		//E chamaremos o método setData para colocar todos esses valores no objeto
		if (count($results)>0){
			$this->setData($results[0]);
		}
	}

	//Abaixo, um método construtor que serve para que, assim que um objeto for criado, já sejam setados o login e a senha. A passagem destes parâmetros será opcional, para que sejam usados apenas quando se estiver criando um novo usuário no banco
	public function __construct ($login = "", $senha = ""){
		$this->setDesLogin($login);
		$this->setDesSenha($senha);
	}

	//Método de UPDATE: Esse método serve para realizar alterações em algum cadastro já existente no banco. Recebe como parâmetro os novos login e senha.
	public function update($login, $senha){

		//Aqui, os valores passados são setados como atributos do objeto.
		$this->setDesLogin($login);
		$this->setDesSenha($senha);

		//Nas linhas abaixo, são colocadas no banco os logins e senhas do objeto.
		$sql = new Sql();
		$sql->query("UPDATE tb_usuario SET deslogin = :LOGIN, dessenha = :SENHA WHERE idusuario = :ID", array(
				":LOGIN"=>$this->getDesLogin(),
				":SENHA"=>$this->getDesSenha(),
				":ID"=>$this->getIdUsuario()
			)
		);
	}

	//Método DELETE: Criaremos o método que irá excluir um registo do banco. 

	public function delete(){
		$sql = new Sql();

		$sql->query("DELETE FROM tb_usuario WHERE idusuario = :ID", array(
			":ID"=>$this->getIdUsuario()
			));

		//Agora que os dados foram apagados do Banco, vamos fazer com que eles também sejam apagados do objeto
		$this->setIdUsuario(0);
		$this->setDesLogin("");
		$this->setDesSenha("");
		$this->setDtCadastro(new DateTime());
	}
}

?>