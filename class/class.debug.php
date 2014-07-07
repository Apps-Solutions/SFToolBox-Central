<?php

class Debug {
	
	var $msg;
	var $debugOn;
	
	function Debug() {
		$this->I_Init();
	}
	
	private function I_Init() {
		$this->msg = "";
		$this->debugOn = 0;
	}
	
	function DebugMessage( $msg ){
		if ( $this->debugOn ){
			$this->msg .= $msg . "<br />";
		}
	}
	
	function DebugEnabled(){
		return ($this->debugOn ? 1 : 0);
	}

	function SetDebug( $debugOn ){
		$this->debugOn = $debugOn;
	}
	
	function Dump(){
		if ( $this->debugOn ){
			print("<div class=\"dbgText\">" . $this->msg . "</div>");
		}
	}
	
	function DumpArray( $arrayName, $a ){
		foreach( $a as $k => $v ){
			$this->DebugMessage( "$arrayName.[$k] = [$v]" );
		}
	}	
}
?>
