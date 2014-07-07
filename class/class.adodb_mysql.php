<?php
/**
* MySQL DB Connection Class
* @link			
* @since        14/05/2014
* @author		Manuel Fernández
*/

class ADODB_mysql {
	var $connectionID;   //variable de ID de la conexion
	var $bd_conexion; 	 //conexion de BD
	var $_query_result;  //resultado de la consulta
	var $error;		 //arreglo de errores
	var $registros;		 //arreglo de registros

	/**
	* ADODBmysql()    
	* Constructor de la clase
	* @param         $bd_host: 		DB Host
					 $bd_user. 		DB User
					 $bd_pasword. 	DB Password
					 $bd_data_base. DB Data Base
	* @return        $bd_conexion 	DB Connection
	*/ 
	function ADODB_mysql($bd_host, $bd_user, $bd_password, $bd_data_base){
		$bd_conexion	= mysql_connect($bd_host, $bd_user,$bd_password)
					or die('Could not connect to '.$bd_host.' server <br> Message: '.mysql_error() );
					
		mysql_select_db($bd_data_base)
					or die('Could not select to '.$bd_data_base.' database <br> Message: '.mysql_error() );
		
		$this->connectionID = $bd_conexion;
		return $bd_conexion;
	} // ADODB_mysql
	
	
	/**
	* clean_query()  Queries the DataBase
	* @param       $inQuery String: Consulta a realizar
	* @return        -> false if an error occurred
					 -> records array
	*/
	private function clean_query( $query ){
		$query = trim( $query );
		if (substr( strtolower($query), 0, 7 ) === "select "){ 
			$query = str_replace( array('insert', 'update', 'delete', 'truncate', 'drop', 'create'), '', $query);
			return $query;
		} else {
			return "SELECT 0";
		}
	}
	
	/**
	* query()  		Queries the DataBase
	* @param       $inQuery String: Consulta a realizar
	* @return        -> false if an error occurred
					 -> records array
	*/
	public function query( $inQuery ){ 

		// Clean variables
		$this->error 		= array();
		$this->registros 	= array();
		$this->_query_result= false;  
		$query = $this->clean_query( trim($inQuery) ); 
		// Always include the link identifier (in this case $this->connectionID) in mysql_query
		$query_result = mysql_query( $query, $this->connectionID) or $this->get_error();
		if( $query_result === false ){  //existió un error
			$this->error[] = mysql_error();
			return false;
		} 
		
		$this->_query_result = $query_result;
		while ($rows = mysql_fetch_assoc($query_result)){
			$this->registros[] = $rows;
		}
		return $this->registros; 
	} 
	
	
	/**
	* execute() 	 Executes a query in the DataBase
	* @param       $inQuery String: Consulta a realizar
	* @return        -> false if an error occurred
					 -> true if query was successfull
	*/
	public function execute( $inQuery ){ 

		// Clean variables
		$this->_error 		= array(); 
		$this->_query_result= false; 

		// Always include the link identifier (in this case $this->connectionID) in mysql_query
		$query_result = mysql_query( $inQuery, $this->connectionID) or $this->get_error();
		if( $query_result === false ){  //existió un error
			$this->error[] = mysql_error();
			return false;
		}
		if( strstr(strtolower($inQuery) , 'select ') )  { //El query es un SELECT
			$this->_query_result = $query_result;
			while ($rows = mysql_fetch_assoc($query_result)){
				$this->registros[] = $rows;
			}
			return $this->registros;
		}
		else		//El query es un INSERT, UPDATE, DELETE
			return true;
	}
	
	/**
	* get_error()	Returns DB Error
	* @param		NULL
	* @return  		error array
	*/
	function get_error(){
		$this->_error = mysql_error();
		return $this->_error;
	}
	
	/**
	* get_last_id()   	Regresa el ultimo ID insertado en la BD
	* @param	      	NULL
	* @return       		-> ultimo id inertado
	*/
	function get_last_id()	{
		// get the last insert id
		$query            = 'SELECT LAST_INSERT_ID() AS last_insert_id';//'select SCOPE_IDENTITY() AS last_insert_id';
		$query_result     = mysql_query($query) 
									or die('Query failed: '.$query.'<br>Message: '.mysql_error()); 
		$query_result    = mysql_fetch_object($query_result);
		return $query_result->last_insert_id;
	} 
} # ADODBmysql
?>