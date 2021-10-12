<?php

  class RestServer
	{
		public $serviceClass;
	
		public function __construct($serviceClass)
		{
			$this->serviceClass = $serviceClass;
		}
	
		public function handle()
		{
			//if (array_key_exists("method", array_change_key_case($_REQUEST, CASE_LOWER)))
			{
				$rArray = array_change_key_case($_REQUEST, CASE_LOWER);
                                if(! isset($rArray["method"]) || $rArray["method"] == "")
					$rArray["method"] = "AyudaMetodosListar";
				$method = $rArray["method"];
				error_log("Metodo : " . $method);
				if (method_exists($this->serviceClass, $method))
				{
					$ref = new ReflectionMethod($this->serviceClass, $method);
					$params = $ref->getParameters();
					$pCount = count($params);
					$pArray = array();
					$paramStr = "";
					
					$i = 0;

					$existen = 0;
					foreach ($params as $param)
					{
						$pArray[strtolower($param->getName())] = null;
						$paramStr .= $param->getName();
						if ($i != $pCount-1)
						{
							$paramStr .= ", ";
						}
						if (isset($rArray[strtolower($param->getName())]))
							$existen++;
						
						$i++;
					}

					foreach ($pArray as $key => $val)
					{
						$pArray[strtolower($key)] = $rArray[strtolower($key)];
					}
	
					if (count($pArray) == $pCount && $pCount == $existen)
					{
						echo call_user_func_array(array($this->serviceClass, $method), $pArray);
					}
					else
					{
						error_log("El metodo '". $method ."' requiere los parametros : ". $paramStr);
						echo json_encode(array('Error' => "0","ErrorDescripcion" => "El metodo '". $method ."' requiere los parametros : ". $paramStr));
					}
				}
				else
				{
					error_log("The method " . $method . " does not exist.");
					echo json_encode(array('Error' => "0","ErrorDescripcion" => "El metodo '". $method ."' no existe"));
				}
			}
/*
			else
			{
				echo json_encode(array('error' => 'No method was requested.'));
			}
*/
		}
	}

?>
