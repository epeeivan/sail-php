<?php
// trait file {
// 	function upload($index, $destination, $maxsize = FALSE, $extensions = FALSE, $name) {
// 		//Test1: fichier correctement uploadé
// 		if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) {
// 			return FALSE;
// 		}

// 		//Test2: taille limite

// 		if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) {
// 			return FALSE;
// 		}

// 		//Test3: extension

// 		$ext = substr(strrchr($_FILES[$index]['name'], '.'), 1);

// 		if ($extensions !== FALSE AND !in_array($ext, $extensions)) {
// 			return FALSE;
// 		}

// 		//Déplacement
// 		$this->setFile($name . $this->lastInsertId() . '.' . $ext);
// 		/*echo $_FILES[$index]['tmp_name'];*/

// 		return move_uploaded_file($_FILES[$index]['tmp_name'], 'appGen/Content/upload/' . $name . $this->lastInsertId() . '.' . $ext);

// 	}

// }

?>