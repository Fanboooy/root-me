<?php

/**
 * dirScan.php v1.0
 * PHP Version 5.6+
 * @link http://cambiamentico.altervista.org/
 * @author Costacurta Nereo (m.b.c.)
 * @copyright 2016
 * @license GPL v3
 * @note This program is distributed in the hope that it will be useful -
 * WITHOUT ANY WARRANTY, without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.
 * @description recursive directory scan in php: output text or xml;
 * can be customized with filters (regexp)
 */

Class SCANDIR {
	//url base path
	public $path = "";
	//contains folders and files
	public $folders = [], $files = [];
	//contain no-includes
	private $filters = [], $regexp = "";
	//for tabs (readability)
	private $deep = 0, $tabs = "";

	/* parameters:
		$path : path to file (relative path are allowed)
		$deep : how may tabs for printing beauty sake :)
		$filter : allowed types. if empty all files are listed
	*/
	function __construct(
		$path="./",
		$deep=0,
		$filters=["\\.html","\\.php"]
	){
		$this->path = $path;
		$this->filters = $filters;
		$this->regexp = '/'.implode('$|',$filters).'$/';
		$this->deep = $deep;
		for ($i=0; $i<=$deep; $i++){
			$this->tabs .= "\t";
		}
		return $this->scan();
	}

	private function scan(){
		$allfiles = scandir($this->path);
		foreach ($allfiles as $i => $d){
			if ($d == '.' || $d == '..') continue;
			if (is_dir($this->path.$d)){
				$this->folders[] = $d;
			}
			else{
				if (empty($this->filters)) $this->files[] = $d;
				elseif (preg_match($this->regexp,$d)) $this->files[] = $d;
			}
		}
		return $this;
	}

	public function toText(){
		//print files, then folders
		foreach ($this->files as $i => $f){
			echo $this->tabs.$f."\n";
		}
		foreach ($this->folders as $i => $f){
			echo $this->tabs.$f."/\n";
			$SD = new SCANDIR($this->path.$f.'/', $this->deep+1);
			$SD->toText();
		}
	}

	public function toXML(){
		if (empty($this->folders) && empty($this->files)){
			return false;
		}

		$t = $this->tabs;

		//print files, then folders
		echo $t.'<scan>'."\n";

		if (!empty($this->files)){
			echo $t.'  <files>'."\n";
				foreach ($this->files as $i => $f){
					echo $t.'    <name>'.utf8_encode($f).'</name>'."\n";
				}
			echo $t.'  </files>'."\n";
		}

		if (!empty($this->folders)){
			echo $t.'  <folders>'."\n";
				foreach ($this->folders as $i => $f){
					echo $t.'    <folder>'."\n".
						$t.'      <name>'.$f.'/</name>'."\n";
							$SD = new SCANDIR($this->path.$f.'/', $this->deep+1, $this->filters);
							$SD->toXML();
					echo $t.'    </folder>'."\n";
				}
			echo $t.'  </folders>'."\n";
		}

		echo $t.'</scan>'."\n";
	}
}

$dirToScan = './';
$ScanDir = new SCANDIR($dirToScan, 0, ["\\.html","\\.php","\\.jpg","\\.png"]);

if (isset($_GET['plaintext'])){
	header('Content-Type: text/plain');
	echo $dirToScan."\n";
	$ScanDir->toText();
}
elseif (isset($_GET['xml'])){
	header('Content-Type: application/xml');
	echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n".
		'<scan>'."\n".
			'<folders>'."\n".
				'<name>'.$dirToScan."</name>"."\n";
				$ScanDir->toXML();
	echo '</folders>'."\n".
		'</scan>';
}

?>
