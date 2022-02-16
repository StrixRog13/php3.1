<?php
require 'system/controller/controller.php';
Controller::run();
?>


//DefaultCommand.php
<?php
/*
 * Дефолтная команда
 */
 
class DefaultCommand extends Command{
    function doExecute($request)
    {
        $request->addFeedback("Дефолтная команда");
        include("./application/views/main_view.php");
    }
}
?>

//controller.php

<?php
/*
 * переписываю контроллер как единую точку входа для шаблона Front Controller
 * Зандстра стр.295
 */
require_once(__DIR__ . '/applicationHelper.php');
require_once ('./system/registry/applicationRegistry.php');
require_once ('./system/command/commandResolver.php');
class Controller
{
    private $applicationHelper;
    private function __construct() {}
 
    static function run(){
        $instance = new Controller();
        $instance->init();
        $instance->handleRequest();
    }
 
    function init(){
        $applicationHelper = ApplicationHelper::instance();
        $applicationHelper->init();
    }
 
    function handleRequest(){
        $request = applicationRegistry::getRequest();
        $cmd_r = new CommandResolver();
        $cmd = $cmd_r->getCommand($request);
        $cmd->execute($request);
    }
 
}
?>

//applicationHelper.php

<?php
/*
 * считывание необходимых данных для работы, Singleton. Зандстра стр.297
 */
class ApplicationHelper{
    private static $instance;
    private $config = ("./config/config.php");
    private $db = ("./system/db/database.class.php");
    private $parameters = array();
 
    private function __construct() {}
 
    static function instance(){
        if(is_null(self::$instance) ){
            self::$instance = new self;
        }
        return self::$instance;
    }
 
    public function init(){
        require $this->db;
        $db = new Db($this->getOptions("host"),$this->getOptions("db_name"),$this->getOptions("user"),$this->getOptions("pass"));
    }
    // конфиги
    public function getOptions($name = ''){
        if (! file_exists($this->config)){
            throw new Exception("Файл с параметрами конфигурации не найден!");
        }
       $this->parameters = require $this->config;
        if(isset($this->parameters[$name])){
            return $this->parameters[$name];
        }
        return $this->parameters;
    }
}
?>

//request.php

<?php
 
Class Request{
    private $properties;
    private $feedback = array();
 
    function __construct(){
        $this->init();
    }
 
    public function init(){
        if (isset ($_SERVER['REQUEST_METHOD'])){
            $this->properties = $_REQUEST;
            return;
        }
        foreach($_SERVER['argv'] as $arg){
            if (strpos($arg,'=')){
                list($key,$val) = explode("=", $arg);
                $this->setProperty($key,$val);
            }
        }
    }
 
    public function getProperty($key){
        if(isset($this->properties[$key])){
            return $this->properties[$key];
        }
        return null;
    }
 
    public function setProperty($key,$val){
        $this->properties[$key] = $val;
    }
 
    public function addFeedback ($msg){
        array_push($this->feedback, $msg);
    }
 
    public function getFeedback(){
        return $this->feedback;
    }
 
    function getFeedbackString($separtator="\n"){
        return implode($separtator,$this->feedback);
    }
}
 
?>

//registry.php

<?php
/*
 * Паттерн Registry. Зандстра стр.285
 */
abstract class Registry{
    abstract protected function get ($key);
    abstract protected function set($key,$val);
}
?>

//applicationRegistry.php

<?php
/*
 * класс-потомок от Registry. Осуществляет получение запроса. Зандстра стр.290
 * вообще чето СЛОЖНААА СЛОЖНАА, но там полное описание в книжке
 */
require_once(__DIR__.'/registry.php');
require_once ('./system/controller/request.php');
Class ApplicationRegistry extends Registry{
    private static $instance = null;
    private $freezedir = "data";
    private $values = array();
    private $mtitems = array();
 
    private function __construct(){}
 
    static function instance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
 
    protected function get($key){
        $path = $this->freezedir. DIRECTORY_SEPARATOR. $key;
        if(file_exists($path)){
            clearstatcache(); // сбросим кэш
            $mtime = filemtime($path);
            if(! isset($this->mtitems[$key])){
                $this->mtitems[$key] = 0;
            }
            if($mtime > $this->mtitems[$key]){
                $data = file_get_contents($path);
                $this->mtitems[$key] = $mtime;
                return ($this->values[$key] = unserialize($data));
            }
        }
        if(isset($this->values[$key])){
            return $this->values[$key];
        }
        return null;
    }
 
    protected function set($key, $val){
        $this->values[$key] = $val;
        $path = $this->freezedir. DIRECTORY_SEPARATOR . $key;
        file_put_contents($path,serialize($val));
        $this->mtitems[$key] = time();
    }
 
    static function getDSN(){
        return self::instance()->get('dsn');
    }
 
    static function setDSN($dsn){
        return self::instance()->set('dsn',$dsn);
    }
 
    static function getRequest(){
        $inst = self::instance();
        if(is_null($inst->request)){
            $inst->request = new Request();
        }
        return $inst->request;
    }
 
 
}
?>

//command.php

<?php
/*
 * Зандстра стра 300
 */
abstract class Command{
    final function __construct(){ }
 
    function execute($request){
        $this->doExecute($request);
    }
 
    abstract function doExecute($request);
}
 
 
?>

//commandResolver.php

<?php
/*
 *  Выбираем нужную команду для запроса. Зандстра стр.299
 */
require_once ('./system/command/command.php');
 
class CommandResolver{
    private static $base_cmd = null;
    private static $default_cmd = null;
 
    public function __construct()
    {
        if (is_null(self::$base_cmd)) {
            self::$base_cmd = new ReflectionClass("Command");
            self::$default_cmd = new DefaultCommand();
        }
    }
        function getCommand($request){
            $cmd = $request->getProperty('cmd');
            $sep = DIRECTORY_SEPARATOR;
            if (! $cmd){
                return self::$default_cmd;
            }
 
            $cmd = str_replace(array('.',$sep),"",$cmd);
            $filepath = "application($sep)command($sep)($cmd).php";
            $classname = "application\\command\\$cmd";
            if(file_exists($filepath)){
                @require_once ($filepath);
                if(class_exists($classname)){
                    $cmd_class = new ReflectionClass($classname);
                        if($cmd_class->isSubclassOf(self::$base_cmd)){
                            return $cmd_class->newInstance();
                        }else{
                            $request->addFeedback("Объект Command команды '$cmd' не найден!");
                        }
                }
            }
            $request->addFeedback("Команда '$cmd' не найдена!");
            return clone self::$default_cmd;
        }
}
 
?>