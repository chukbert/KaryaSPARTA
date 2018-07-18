<?php if(!isset($_POST["btnSubmit"])) { ?>
<html>
	<head>
		<title>Pengatur Foto Buku Angkatan UNIX 2017</title>
	</head>
	<body>
		<h1> Pengatur Foto Buku Angkatan </h1>
		<p> Aplikasi website ini digunakan untuk membantu kita, Angakatan UNIX 2017, dalam hal pengerjaan tugas buku angkatan.</p>
		<p> Kenapa bisa begitu? Karena aplikasi website ini berfungsi untuk mengubah foto-foto hasil wawancara kita (UNIX 2017) yang sebelumnya ukurannya masih acak-acakan menjadi ukuran 4x6 (cm) dan hasilnya kita peroleh dalam bentuk pdf. Sekian penjelasan singkat mengenai aplikasi website ini. Selamat mencoba ^__^</p>
		<p> Note: </p> 
		<p> -"Maksimal hanya sampai 20 foto dalam satu kali upload".</p>
		<p> -"Untuk hasil yang lebih baik, format foto dalam keadaan portrait".</p>
		<form method="post" enctype="multipart/form-data" name="formUploadFile">		
			<label>Select file(s) to upload (.jpg or .jpeg) 		:</label>
			<input type="file" name="files[]" multiple="multiple" />
			<input type="submit" value="Upload File" name="btnSubmit"/>
		</form>
	</body>
</html>
<?php
} else {

require('fpdf181/fpdf.php');

function crop($image, $filename) {
    $image = imagecreatefromjpeg($image);
    $thumb_width = 400;
    $thumb_height = 600;
    $width = imagesx($image);
    $height = imagesy($image);
    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;

    if ( $original_aspect >= $thumb_aspect ) {
        $new_height = $thumb_height;
        $new_width = $width / ($height / $thumb_height);
    } else {	
   // If the thumbnail is wider than the image
       $new_width = $thumb_width;
       $new_height = $height / ($width / $thumb_width);
    }

    $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
// Resize and crop
    imagecopyresampled($thumb,
                         $image,
                         0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                         0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                         0, 0,
                       $new_width, $new_height,
                       $width, $height);
    imagejpeg($thumb, $filename, 80);
}
$data = $_FILES["files"]["tmp_name"];
$numberImages = count($data);
$pdf = new FPDF('P','cm','A4');
$numberPages = ceil($numberImages / 16);
$img = $numberImages;
while ($numberPages > 0) {
	$pdf->AddPage();
	$i = 0; $y = 1.5;
	while ($i < 4) {
		$j = 0; $x = 1.5;
		while ($j < 4) {
			crop($data[$img - $numberImages], $data[$img - $numberImages] . '.jpg');
			$pdf->Image($data[$img - $numberImages] . '.jpg', $x, $y, 4, 6);
			$x = $x + 4.5; $j++; $numberImages--;
			if ($numberImages <= 0) {
		    	break;
			}
		}
		$y = $y + 6.5; $i++;
		if ($numberImages <= 0) {
		    	break;
			}
	}
	$numberPages--;
}
$pdf->Output();
}
?>