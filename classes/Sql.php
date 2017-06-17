<?php 

/*

PDO e DAO - Data Access Object

É uma forma de trabalhar em PHP com Orientação a Objetos e Banco de Dados.

Quando trabalhamos programando pra Web, temos algumas camadas:

- Banco de Dados;
- PHP que conversa com o Banco de Dados;
- O front-end, com o qual o usuário interage.

Geralmente, quando trabalhamos dessa forma, estamos sempre manipulando o Banco. Com a Orientação a Objeto, conseguimos organizar isso melhor. Por exemplo, depois que as tabelas e campos forem criados, criamos classes no PHP para fazer esta abstração, ou seja, pra acessar essas tabelas e para manipular os dados. 

Então, durante o dia a dia, não precisaremos ir sempre ao Banco. Utilizaremos as classes para realizar essa conexão. 

Vantagens:

- Ganhamos facilidade na hora de trabalhar em equipe. Na equipe podemos ter um programador sênior que cria as classes, e programadores júnior que aprendem só a usar as classes. 

- Temos abstração de banco - Ou seja, se o Banco mudar, se mudar como a classe conversa com o Banco, mudamos apenas a classe e não várias partes do código.

- Segurança - Não é preciso dar a senha do banco para todos que vão manipulá-lo, é preciso dar apenas as classes. 

- Organizamos do código e evitamos retrabalho.

Nestas aulas utilizaremos muita orientação a objeto, principalmente herança e métodos construtores. 

*/

/*

Nas próximas linhas criaremos classes que fazem aquilo que tivemos que repetir nos últimos 4 exemplos.

*/

//Criaremos a classe Sql que extenderá da classe PDO: Tudo que a PDO faz, esta também fará

class Sql extends PDO {

	//Criaremos o atributo privado $conn (variável que criávamos diversas vezes)
	private $conn;

	//No método construtor, sempre era necessário passar o mesmo parâmetro. Com este método nesta classe que extende da PDO, já colocaremos o parâmetro embutido. Faremos isso porque utilizaremos apenas um banco e um host, com o mesmo usuário e senha, mas também poderia ter parâmetros aqui
	public function __CONSTRUCT(){
		$this->conn = new PDO("mysql:host=localhost;dbname=dbphp7", "root", "");
	}

	//O método abaixo recebe um statement já pronto e troca um ID por um valor. É um método privado, pois só é usado dentro da classe e dentro do método setParams

	private function setParam($statement, $key, $value){
		$statement->bindParam($key, $value);
	}

	//O método abaixo executa o método setParam dentro de um foreach, repetidas vezes. Ele recebe a variável $statement, que terá seus IDs substituídos, e um array de valores com referência, onde o título é o ID e o valor é o valor que será atribuído ao ID.
	public function setParams($statement, $parameters = array()){
		foreach ($parameters as $key => $value) {
			$this->setParam($statement, $key, $value);
		}
	}

	//Abaixo, criamos o método query, que resumirá o uso do método prepare. Dentro da classe estará a variável $stmt. Será passado como parâmetro o statement e as variáveis que correspondem às IDs que podem existir no comando. Este método também executa o comando preparado. Este método é para comandos SQL que se tratam apenas de uma execução e que não exibem nada na tela.

	public function query($rowQuery, $params = array()){
		$stmt = $this->conn->prepare($rowQuery);

		$this->setParams($stmt, $params);
 		
 		$stmt->execute();
		
		return $stmt;
	}

	//Para comandos SQL que exibirão algo na tela, existirá o método select. Ele recebe como parâmetro a linha SQL e um array que informa as IDs da linha SQL e seus valores. Ele já formata a exibição, tirando os números do array e deixando somente as chaves.

	public function select($rowQuery, $params = array()){
		$stmt = $this->query($rowQuery, $params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}

?>