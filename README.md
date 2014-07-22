#PHP CRON JOB

Este é um framework para auxílio de execução de rotinas em php.


## Exemplo de utilização:

<pre>
// src/Crons/TesteCron.php
namespace Crons;

use \CronRunner\Cron\CronAbstract;

class TesteCron extends CronAbstract {

    protected function _init() {
        //do some initial defines here
    }

    public function run() {
        // do your logical run here
    }
}
</pre>
Chamando a tarefa:
<pre>
    php runner.php --name Teste
</pre>

## Definido os argumentos passados para a tarefa:

O framework prevê três tipos de definições:

### Agumentos do tipo Flag
São argumentos que tem valor booleando (true ou false).

<pre>
...
    public function init() {
        $this->_getArgs()->setFlag(array('teste'));
    }
...
</pre>

para pegar o valor da flag:
<pre>
...
    public function run() {
        $teste = $this->_getArgs()->getArgByName('teste');
    }
...
</pre>
Na chamada da rotina faremos da seguinte forma:
<pre>
    // valor do stributo é verdadeiro
    php runner.php --name Teste --teste

    // valor do atributo é falso
    php runner.php --name Teste --no-teste
</pre>

### Agumentos do tipo Valorado
São argumentos que tem valor passado por linha de comando

<pre>
...
    public function init() {
        $this->_getArgs()->setValued(array('teste'));
    }
...
</pre>

para pegar o valor do atributo:
<pre>
...
    public function run() {
        $teste = $this->_getArgs()->getArgByName('teste');
    }
...
</pre>
Na chamada da rotina faremos da seguinte forma:
<pre>
    // valor do teste é a string "algum valor"
    php runner.php --name Teste --teste "algum valor"
</pre>

### Definição de argumentos obrigatórios
<pre>
...
    public function init() {
        $this->_getArgs()->setRequired(array('teste'));
    }
...
</pre>
Se não passarmos <code>--teste</code> na linha de comando o seguinte erro é apresentado:
<pre>
php src/runner.php --name Teste
Please inform '--teste' information
</pre>

## Trava de execução
Existem muitos processos que necessitam de travas de execução, ou seja, podemos ter a necessidade de não executar a rotina simultaneamente, ou podemos ter a dependência de outra execução.
Para isto existe um callback <code>isLocked</code> que indica se o processo esta travado ou não, e em caso positivo inibe a execução da tarefa solicitada.

Para que a trava seja habilitada, basta sobreescrever o método <code>isLocked</code> que por padrão devolve false.
<pre>
...
    public function isLocked() {
        // do some logical here
        return true;
    }
...
</pre>

## Utilizacao sem pré-definições
Caso você queira não utilizar as predefinições de <code>CronAbstract</code>, você pode criar suas próprias classes de execução, desde que elas sigam as seguintes regras:

1.  Estar no namespace Crons.
2.  Utilizar o Sufixo "Cron" no nome da tarefa
3.  Implementar a interace \CronRunner\Cron\CronInterface
4.  Estar no diretório <code>src/Crons</code> ou fazer require manual do arquivo
