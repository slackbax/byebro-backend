<?php
/**
 * Clase para Configurar el cliente
 * @Filename: Config.class.php
 * @version: 2.0
 * @Author: flow.cl
 * @Email: csepulveda@tuxpan.com
 * @Date: 28-04-2017 11:32
 * @Last Modified by: Carlos Sepulveda
 * @Last Modified time: 28-04-2017 11:32
 */
 
 $COMMERCE_CONFIG = array(
 	"APIKEY" => "60F504F4-4BFB-4B73-96BD-1L968CB05B77", // Registre aquí su apiKey
 	"SECRETKEY" => "2447d56ab3638487541d615dcfafd66b3ba3978c", // Registre aquí su secretKey
 	"APIURL" => "https://sandbox.flow.cl/api", // Producción EndPoint o Sandbox EndPoint
 	"BASEURL" => "http://localhost/byebro" //Registre aquí la URL base en su página donde instalará el cliente
 );
 
 class Config {
 	
	static function get($name) {
		global $COMMERCE_CONFIG;
		if(!isset($COMMERCE_CONFIG[$name])) {
			throw new Exception("The configuration element thas not exist", 1);
		}
		return $COMMERCE_CONFIG[$name];
	}
 }
