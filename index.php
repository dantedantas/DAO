<meta charset="UTF-8">

<?php 

require_once("config.php");

//$sql = new Sql();

//Agora fica muito mais fácil executar comandos no banco:
//$usuarios = $sql->select("SELECT * FROM tb_usuario");

//echo json_encode($usuarios);

//$fulano = new Usuario();

//Executaremos o método que retorna um usuário cujo ID é passado por parâmetro
//$fulano->loadById(6);

//Abaixo, colocamos dentro do json_encode o resultado do método estático getList, que retorna uma lista com todos os dados da tabela. Lembrando a sintaxe para chamar métodos estáticos:
//NomeDaClasse::Metodo()

//echo json_encode(Usuario::getList());

//Abaixo executaremos um método que carrega uma lista de logins semelhantes ao login pesquisado (passado como parâmetro)

//echo json_encode(Usuario::search("e"));

//Abaixo, executaremos o método login: Ele recebe como parâmetro um usuário e senha, verifica se tem no banco e, se tiver, ele carrega os valores nos atributos do objeto. Se ele não encontrar, exibe uma mensagem de erro.
//$usuario = new Usuario();
//$usuario->login("Ana", "3ic92l");

//echo $usuario;

//Usaremos o método insert() para inserir um novo usuário no Banco
//Primeiro, criaremos um novo objeto do tipo usuario. Como criamos um método construtor que já receberá o login e senha, o passaremos como parâmetro.
//$aluno = new Usuario("aluno2", "degv");

//Considerando que esses valores que passamos como parâmetro não existem no Banco de Dados ainda, somente no objeto. Para colocarmos no Banco, executaremos sobre esse objeto o método insert.
//$aluno->insert();

//O método insert() retorna o ID criado, além dos outros valores e coloca no objeto. Vamos ver se isso realmente aconteceu ao imprimir o objeto
//echo $aluno;

//Abaixo, utilizaremos o método update criado para realizar alterações em um usuário já existente

//$user = new Usuario();

//Para fazer o update, primeiro precisaremos carregar as informações do usuário desejado, do Banco para o objeto.
//$user->loadById(8);

//Agora, o método update. Vamos deixar o mesmo usuário, só mudaremos a senha.
//$user->update("aluno2", "joile");

//Para conferir se tudo deu certo, imprimiremos na tela
//echo $user;

//Abaixo, utilizaremos o método delete() para remover um registro do banco.

$blah = new Usuario();

$blah->loadById(2);
$blah->delete();

echo $blah;

?>