<?php

	if($peticion_ajax){
		require_once "../config/SERVER.php";
	}elseif(!isset($peticion_ajax)){
		require_once "../config/SERVER.php";
	}else{
		require_once "./config/SERVER.php";
	}

	class mainModel{

		/*----------  Funcion conectar a BD  ----------*/
		protected static function conectar(){
			$conexion = new PDO(SGBD,USER,PASS);
			$conexion->exec("SET CHARACTER SET utf8");
			return $conexion;
		} /*--  Fin Funcion --*/


		/*----------  Funcion desconectar de DB  ----------*/
		public function desconectar($consulta){
			global $conexion, $consulta;
			$consulta=null;
			$conexion=null;
			return $consulta;
		} /*--  Fin Funcion --*/


		/*----------  Funcion ejecutar consultas simples  ----------*/
		protected static function ejecutar_consulta_simple($consulta){
			$sql=self::conectar()->prepare($consulta);
			$sql->execute();
			return $sql;
		} /*--  Fin Funcion --*/

		public function ejecutar_consulta_simplex($consulta){
			$sql=self::conectar()->prepare($consulta);
			$sql->execute();
			return $sql;
		} /*--  Fin Funcion --*/

		/*----------  Funcion ejecutar consultas multiple  ----------*/
		public function ejecutar_consulta_multiple($consulta,$campo,$id){

			$id=self::decryption($id);
			$id=self::limpiar_cadena($id);

			$query="$consulta WHERE $campo=$id";

			$sql=self::conectar()->prepare($query);
			$sql->execute();
			return $sql;
		} /*--  Fin Funcion --*/

		public function ejecutar_consulta_preparada($consulta,$campo,$id,$campo2,$id2){

			$id=self::decryption($id);
			$id=self::limpiar_cadena($id);

			$id2=self::decryption($id2);
			$id2=self::limpiar_cadena($id2);

			$query="$consulta WHERE $campo=$id AND $campo2=$id2";

			$sql=self::conectar()->prepare($query);
			$sql->execute();
			return $sql;
		} /*--  Fin Funcion --*/

		public function ejecutar_consulta_preparada_X3($consulta,$campo,$id,$campo2,$id2,$campo3,$id3){

			$id=self::decryption($id);
			$id=self::limpiar_cadena($id);

			$id2=self::decryption($id2);
			$id2=self::limpiar_cadena($id2);

			$id3=self::decryption($id3);
			$id3=self::limpiar_cadena($id3);

			$query="$consulta WHERE $campo='$id' AND $campo2='$id2' AND $campo3='$id3'";

			$sql=self::conectar()->prepare($query);
			$sql->execute();
			return $sql;
		} /*--  Fin Funcion --*/

		/*----------  Funcion para ejecutar una consulta INSERT preparada  ----------*/
		protected static function guardar_datos($tabla,$datos){
			$query="INSERT INTO $tabla (";
			$C=0;
			foreach ($datos as $campo => $indice){
				if($C<=0){
					$query.=$campo;
				}else{
					$query.=",".$campo;
				}
				$C++;
			}
			
			$query.=") VALUES(";
			$Z=0;
			foreach ($datos as $campo => $indice){
				if($Z<=0){
					$query.=$indice["campo_marcador"];
				}else{
					$query.=",".$indice["campo_marcador"];
				}
				$Z++;
			}

			$query.=")";
			$sql=self::conectar()->prepare($query);

			foreach ($datos as $campo => $indice){
				$sql->bindParam($indice["campo_marcador"],$indice["campo_valor"]);
			}

			$sql->execute();

			return $sql;
		} /*-- Fin Funcion --*/


		/*---------- Funcion datos tabla ----------*/
        public function datos_tabla($tipo,$tabla,$campo,$id){
			$tipo=self::limpiar_cadena($tipo);
			$tabla=self::limpiar_cadena($tabla);
			$campo=self::limpiar_cadena($campo);
			

			$id=self::decryption($id);
			$id=self::limpiar_cadena($id);


            if($tipo=="Unico"){
                $sql=self::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID");
                $sql->bindParam(":ID",$id);
            }elseif($tipo=="Normal"){
                $sql=self::conectar()->prepare("SELECT $campo FROM $tabla");
            }
			
            $sql->execute();

            return $sql;
		} /*-- Fin Funcion --*/

		/*---------- Funcion datos tabla ----------*/
        public function datos_tabla_doble($tipo,$tabla,$campo,$id,$campo2,$condicion){
			$tipo=self::limpiar_cadena($tipo);
			$tabla=self::limpiar_cadena($tabla);
			$campo=self::limpiar_cadena($campo);
			$campo2=self::limpiar_cadena($campo2);

			$id=self::decryption($id);
			$id=self::limpiar_cadena($id);

			$condicion=self::decryption($condicion);
			$condicion=self::limpiar_cadena($condicion);

            if($tipo=="Unico"){
                $sql=self::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID");
                $sql->bindParam(":ID",$id);
            }elseif($tipo=="Doble"){
                $sql=self::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID AND $campo2=:Condicion");
                $sql->bindParam(":ID",$id);
                $sql->bindParam(":Condicion",$condicion);
            }elseif($tipo=="Normal"){
                $sql=self::conectar()->prepare("SELECT $campo FROM $tabla");
            }
			
            $sql->execute();

            return $sql;
		} /*-- Fin Funcion --*/

		/*---------- Funcion datos tabla ----------*/
        public function datos_tabla_multi_condicion($tipo,$tabla,$campo,$id,$campo2,$condicion,$campo3,$condicion2){
			$tipo=self::limpiar_cadena($tipo);
			$tabla=self::limpiar_cadena($tabla);
			$campo=self::limpiar_cadena($campo);
			$campo2=self::limpiar_cadena($campo2);
			$campo3=self::limpiar_cadena($campo3);

			$id=self::decryption($id);
			$id=self::limpiar_cadena($id);

			$condicion=self::decryption($condicion);
			$condicion=self::limpiar_cadena($condicion);

			$condicion2=self::decryption($condicion2);
			$condicion2=self::limpiar_cadena($condicion2);

            if($tipo=="Unico"){
                $sql=self::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID");
                $sql->bindParam(":ID",$id);
            }elseif($tipo=="Doble"){
                $sql=self::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID AND $campo2=:Condicion");
                $sql->bindParam(":ID",$id);
                $sql->bindParam(":Condicion",$condicion);
            }elseif($tipo=="Triple"){
                $sql=self::conectar()->prepare("SELECT * FROM $tabla WHERE $campo=:ID AND $campo2=:Condicion AND $campo3=:Condicion2");
                $sql->bindParam(":ID",$id);
                $sql->bindParam(":Condicion",$condicion);
                $sql->bindParam(":Condicion2",$condicion2);
            }elseif($tipo=="Normal"){
                $sql=self::conectar()->prepare("SELECT $campo FROM $tabla");
            }
			
            $sql->execute();

            return $sql;
		} /*-- Fin Funcion --*/


		/*----------  Funcion para ejecutar una consulta UPDATE preparada  ----------*/
		protected static function actualizar_datos($tabla,$datos,$condicion){
			$query="UPDATE $tabla SET ";

			$C=0;
			foreach ($datos as $campo => $indice){
				if($C<=0){
					$query.=$campo."=".$indice["campo_marcador"];
				}else{
					$query.=",".$campo."=".$indice["campo_marcador"];
				}
				$C++;
			}

			$query.=" WHERE ".$condicion["condicion_campo"]."=".$condicion["condicion_marcador"];

			$sql=self::conectar()->prepare($query);

			foreach ($datos as $campo => $indice){
				$sql->bindParam($indice["campo_marcador"],$indice["campo_valor"]);
			}

			$sql->bindParam($condicion["condicion_marcador"],$condicion["condicion_valor"]);

			$sql->execute();

			return $sql;
		} /*-- Fin Funcion --*/
		

		/*---------- Funcion eliminar registro ----------*/
        protected static function eliminar_registro($tabla,$campo,$id){
            $sql=self::conectar()->prepare("DELETE FROM $tabla WHERE $campo=:ID");

            $sql->bindParam(":ID",$id);
            $sql->execute();
            
            return $sql;
        } /*-- Fin Funcion --*/


		/*----------  Encriptar cadenas ----------*/
		public function encryption($string){
			$output=FALSE;
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
			$output=base64_encode($output);
			return $output;
		} /*--  Fin Funcion --*/


		/*----------  Desencriptar cadenas  ----------*/
		protected static function decryption($string){
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
			return $output;
		} /*--  Fin Funcion --*/


		/*----------  Limitar cadenas de texto  ----------*/
		public function limitar_cadena($cadena,$limite,$sufijo){
			if(strlen($cadena)>$limite){
				return substr($cadena,0,$limite).$sufijo;
			}else{
				return $cadena;
			}
		} /*--  Fin Funcion --*/


		/*----------  Funcion generar codigos aleatorios  ----------*/
		protected static function generar_codigo_aleatorio($longitud,$correlativo){
			$codigo="";
			$caracter="Letra";
			for($i=1; $i<=$longitud; $i++){
				if($caracter=="Letra"){
					$letra_aleatoria=chr(rand(ord("a"),ord("z")));
					$letra_aleatoria=strtoupper($letra_aleatoria);
					$codigo.=$letra_aleatoria;
					$caracter="Numero";
				}else{
					$numero_aleatorio=rand(0,9);
					$codigo.=$numero_aleatorio;
					$caracter="Letra";
				}
			}
			return $codigo."-".$correlativo;
		} /*--  Fin Funcion --*/


		/*----------  Funcion limpiar cadenas  ----------*/
		protected static function limpiar_cadena($cadena){
			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);
			$cadena=str_ireplace("<script>", "", $cadena);
			$cadena=str_ireplace("</script>", "", $cadena);
			$cadena=str_ireplace("<script src", "", $cadena);
			$cadena=str_ireplace("<script type=", "", $cadena);
			$cadena=str_ireplace("SELECT * FROM", "", $cadena);
			$cadena=str_ireplace("DELETE FROM", "", $cadena);
			$cadena=str_ireplace("INSERT INTO", "", $cadena);
			$cadena=str_ireplace("DROP TABLE", "", $cadena);
			$cadena=str_ireplace("DROP DATABASE", "", $cadena);
			$cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
			$cadena=str_ireplace("SHOW TABLES;", "", $cadena);
			$cadena=str_ireplace("SHOW DATABASES;", "", $cadena);
			$cadena=str_ireplace("<?php", "", $cadena);
			$cadena=str_ireplace("?>", "", $cadena);
			$cadena=str_ireplace("--", "", $cadena);
			$cadena=str_ireplace("^", "", $cadena);
			$cadena=str_ireplace("<", "", $cadena);
			$cadena=str_ireplace(">", "", $cadena);
			$cadena=str_ireplace("[", "", $cadena);
			$cadena=str_ireplace("]", "", $cadena);
			$cadena=str_ireplace("==", "", $cadena);
			$cadena=str_ireplace(";", "", $cadena);
			$cadena=str_ireplace("::", "", $cadena);
			$cadena=trim($cadena);
			$cadena=stripslashes($cadena);
			return $cadena;
		} /*--  Fin Funcion --*/


		/*---------- Funcion verificar datos (expresion regular) ----------*/
		protected static function verificar_datos($filtro,$cadena){
			if(preg_match("/^".$filtro."$/", $cadena)){
				return false;
            }else{
                return true;
            }
		} /*--  Fin Funcion --*/


		/*---------- Funcion verificar fechas ----------*/
		protected static function verificar_fecha($fecha){
			$valores=explode('-',$fecha);
			if(count($valores)==3 && checkdate($valores[1], $valores[2], $valores[0])){
				return false;
			}else{
				return true;
			}
		} /*--  Fin Funcion --*/


		/*---------- Funcion obtener nombre de mes ----------*/
		public function obtener_nombre_mes($mes){
			switch($mes){
				case 1:
					$nombre_mes="enero";
				break;
				case 2:
					$nombre_mes="febrero";
				break;
				case 3:
					$nombre_mes="marzo";
				break;
				case 4:
					$nombre_mes="abril";
				break;
				case 5:
					$nombre_mes="mayo";
				break;
				case 6:
					$nombre_mes="junio";
				break;
				case 7:
					$nombre_mes="julio";
				break;
				case 8:
					$nombre_mes="agosto";
				break;
				case 9:
					$nombre_mes="septiembre";
				break;
				case 10:
					$nombre_mes="octubre";
				break;
				case 11:
					$nombre_mes="noviembre";
				break;
				case 12:
					$nombre_mes="diciembre";
				break;
				default:
					$nombre_mes="No definido";
				break;
			}
			return $nombre_mes;
		} /*--  Fin Funcion --*/
		/*---------- Funcion Redondea decimales ----------*/
		function redondear_centimos($valor) {
			$resultado = round($valor, 2); // Redondea a 2 decimales (centavos)
			return $resultado;
		}

		function formato_moneda_local($valor) {
			$resultado = number_format($valor, 2, ',', '.');
			return $resultado;
		}

		function formato_moneda_inter($valor) {
			$resultado = number_format($valor, 2, '.', ',');
			return $resultado;
		}
		function formato_hora_12($hora) {
			$resultado = date('g:i a', strtotime($hora));
			return $resultado;
		}
		function formato_hora_24($hora) {
			$resultado =  date('H:i', strtotime($hora));
			return $resultado;
		}


		/*----------  Funcion generar select ----------*/
		public function generar_select($datos,$campo_db){
			$check_select='';
			$text_select='';
			$count_select=1;
			$select='';
			foreach($datos as $row){

				if($campo_db==$row){
					$check_select='selected=""';
					$text_select=' (Actual)';
				}

				$select.='<option value="'.$row.'" '.$check_select.'>'.$count_select.' - '.$row.$text_select.'</option>';

				$check_select='';
				$text_select='';
				$count_select++;
			}
			return $select;
		} /*--  Fin Funcion --*/

		/*----------  Funcion generar select ----------*/
		public function generar_select_db($tabla, $campo_id, $campo_mostrar, $selected = null){

			$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM $tabla ORDER BY 1 ASC";
			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);
			$datos = $datos->fetchAll();
			$select='';
			foreach($datos as $rows){
				$campo = '';
				$seleccionado = (($selected == $rows[$campo_id])?"selected":"");
				if(is_array($campo_mostrar)){
					foreach($campo_mostrar as $data){
						$campo .= $rows[$data].' ';
					}
				}else{
					$campo = $rows[$campo_mostrar];
				}

				$select.='<option value="'.$rows[$campo_id].'" '.$seleccionado.'>'.$campo.'</option>';
			}
			
			return $select;
		} /*--  Fin Funcion --*/

		/*----------  Funcion generar select ----------*/
		public function generar_select_db2($tabla, $campo_id, $campo_mostrar, $campo_mostrar2, $selected = null){

			$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM $tabla ORDER BY 1 ASC";
			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);
			$datos = $datos->fetchAll();
			$select='';
			foreach($datos as $rows){
				$campo = '';
				$seleccionado = (($selected == $rows[$campo_id])?"selected":"");
				if(is_array($campo_mostrar)){
					foreach($campo_mostrar as $data){
						$campo .= $rows[$data].' ';
					}
				}else{
					$campo = $rows[$campo_mostrar];
					$campo2 = $rows[$campo_mostrar2];
				}

				$select.='<option value="'.$rows[$campo_id].'" '.$seleccionado.'>'.$campo2.' - '.$campo.'</option>';
			}
			
			return $select;
		} /*--
		/*----------  Funcion generar select ----------*/
		public function generar_select_db_join($tabla, $campo_id, $tabla_join, $campo_join, $campo_mostrar, $selected = null){

			$consulta="SELECT SQL_CALC_FOUND_ROWS $tabla.*  FROM $tabla  LEFT JOIN $tabla_join  ON $tabla_join.$campo_join=$tabla.$campo_id WHERE $tabla_join.$campo_join IS NULL ORDER BY 1 ASC";
			$conexion = mainModel::conectar();
			$datos = $conexion->query($consulta);
			$datos = $datos->fetchAll();
			$select='';
			foreach($datos as $rows){
				$campo = '';
				$seleccionado = (($selected == $rows[$campo_id])?"selected":"");
				if(is_array($campo_mostrar)){
					foreach($campo_mostrar as $data){
						$campo .= $rows[$data].' ';
					}
				}else{
					$campo = $rows[$campo_mostrar];
				}

				$select.='<option value="'.$rows[$campo_id].'" '.$seleccionado.'>'.$campo.'</option>';
			}
			
			return $select;
		} /*--  Fin Funcion --*/
		
		/*--  Fin Funcion --*/


		/*----------  Funcion enviar correo ----------*/

		/*--  Fin Funcion --*/




	}
	//
	