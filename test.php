<?PHP

class Test{
	
	public function __call($name, $arguments){
		return array($name => $arguments);
	
	}

}

$test = new Test();

var_dump($test->olakwnfelkajweklfjlwkjelkmg("Asdfasdf"));

?>