<?php

$rowCount = ceil(sqrt(count($_POST)-1));
$colCount = ceil((count($_POST)-1)/$rowCount);

// Image variables
foreach ($_POST as $icon => $i) {
	$imgPath = ROOT.ICONDIR.$icon.'.png';
	if (is_file($imgPath)) {
		$imgSize = getimagesize($imgPath);
		$iconWidth = $imgSize[0];
		$iconHeight = $imgSize[0];
		break;
	}
}
$imgMatrix = array();
$imgWidth = $colCount*$iconWidth;
$imgHeight = $rowCount * $iconHeight;
$img = imagecreatetruecolor($imgWidth, $imgHeight);
imagesavealpha($img, true);
$trans = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $trans);

// CSS variables
$cssStructure = '.icon {'."\r\n";
$cssStructure.= "\t".'background:url(iconsprite.png);'."\r\n";
$cssStructure.= "\t".'width:'.$iconWidth.'px;'."\r\n";
$cssStructure.= "\t".'height:'.$iconHeight.'px;'."\r\n";
$cssStructure.= "\t".'display:block;'."\r\n";
$cssStructure.= "\t".'float:left;'."\r\n";
$cssStructure.= "\t".'color:transparent;'."\r\n";
$cssStructure.= "\t".'color:#fff;'."\r\n";
$cssStructure.= "\t".'text-indent:-9999em;'."\r\n";
$cssStructure.= "\t".'cursor:pointer;'."\r\n";
$cssStructure.= "\t".'margin:12px;'."\r\n";
$cssStructure.= '}'."\r\n";

// HTML variables
$htmlStructure = '<!DOCTYPE html>'."\r\n";
$htmlStructure.= '<html lang="en">'."\r\n";
$htmlStructure.= '<head>'."\r\n";
$htmlStructure.= "\t".'<meta charset="utf-8" />'."\r\n";
$htmlStructure.= "\t".'<meta name="viewport" content="width=device-width, user-scalable=no">'."\r\n";
$htmlStructure.= "\t".'<title>Icon Sprite Generator</title>'."\r\n";
$htmlStructure.= "\t".'<link rel="stylesheet" href="icon.css" media="screen" />'."\r\n";
$htmlStructure.= '</head>'."\r\n";
$htmlStructure.= '<body>'."\r\n";

$row = 0;
$col = 0;

foreach ($_POST as $icon => $i) {
	if ($i == 'on') {
		$iconImg = $icon.'.png';
		if ($row == 0) {
			$imgMatrix[$col] = array();
		}
		$imgMatrix[$col][$row] = $icon;
		
		// Image creation
		$src_im = imagecreatefrompng(ROOT.ICONDIR.$iconImg);
		$dst_x = $col*$iconWidth;
		$dst_y = $row*$iconHeight;
		imagecopy($img, $src_im, $dst_x, $dst_y, 0, 0, $iconWidth, $iconHeight);
		
		// CSS code
		$cssStructure.= '.'.$icon.' {'."\r\n";
		$cssStructure.= "\t".'background-position:'.($dst_x*-1).'px '.($dst_y*-1).'px;'."\r\n";
		$cssStructure.= '}'."\r\n";

		// HTML code
		$htmlStructure.= "\t".'<a class="icon '.$icon.'"></a>'."\r\n";
		
		$row++;
		if ($row == $rowCount) {
			$col++;
			$row = 0;
		}
	}
}

$htmlStructure.= '</body>'."\r\n";
$htmlStructure.= '</html>'."\r\n";

// Create containing directory

$dirName = ROOT.'/temp_'.(microtime(true)*10000).'_'.rand();
if (mkdir($dirName)) {
	
	// Create image
	$imgName = $dirName.'/iconsprite.png';
	$imgCreate = imagepng($img, $imgName);
	
	// Create CSS
	$cssName = $dirName.'/icon.css';
	$cssHandle = fopen($cssName, 'w') or die('Can\'t open file');
	$cssCreate = fwrite($cssHandle, $cssStructure);
	fclose($cssHandle);
	
	// Create HTML
	$htmlName = $dirName.'/index.html';
	$htmlHandle = fopen($htmlName, 'w') or die('Can\'t open file');
	$htmlCreate = fwrite($htmlHandle, $htmlStructure);
	fclose($htmlHandle);
	
	if($imgCreate && $cssCreate && $htmlCreate) {
		// Create ZIP file
		$zipName = 'iconsprite.zip';
		$zipFile = new ZipArchive();
		if ($zipFile->open($dirName.'/'.$zipName, ZipArchive::CREATE)) {
			$zipFile->addFile($imgName, 'iconsprite.png');
			$zipFile->addFile($cssName, 'icon.css');
			$zipFile->addFile($htmlName, 'index.html');
			$zipFile->close();
			$zipSuccess = true;
		} else {
			$zipSuccess = false;
		}
		if ($zipSuccess === true) {
			header('Content-Type: application/zip'); 
			header('Content-Disposition: attachment; filename="'.$zipName.'"');
			header("Content-Length: ".filesize($dirName.'/'.$zipName));
			if(readfile($dirName.'/'.$zipName) !== false) {
				//if (unlink($imgName) && unlink($cssName) && unlink($htmlName) && unlink($dirName.'/'.$zipName)) {
				//	rmdir($dirName);
				//}
			} else {
				$error = array(
					'message' => 'Unfortunately the zip file could not be created.',
					'code' => 5
				);
			}
		} else {
			$error = array(
				'message' => 'Unfortunately the zip file could not be created.',
				'code' => 5
			);
		}
	} else {
		$error = array(
			'message' => 'Unfortunately the files could not be created.',
			'code' => 4
		);
	}

} else {
	// Send back error
	$error = array(
		'message' => 'Unfortunately the directory could not be created.',
		'code' => 3
	);
}
?>