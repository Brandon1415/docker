<?php
require '../constants/settings.php'; 
require '../constants/check-login.php';
require '../constants/db_config.php';

if (isset($_GET['empid'])) {
    $empid = $_GET['empid'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Array para almacenar PDFs convertidos
        $pdfFiles = [];
        $index = 0;

        // Consulta para obtener certificados académicos
        $stmt = $conn->prepare("SELECT certificate FROM tbl_academic_qualification WHERE member_no = :empid");
        $stmt->bindParam(':empid', $empid);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $binaryData = $row['certificate'];
            $pdfFileName = "certificado_academico_" . $empid . "_" . $index . ".pdf";
            file_put_contents($pdfFileName, $binaryData);
            $pdfFiles[] = $pdfFileName;
            $index++;
        }

        // Consulta para obtener certificados de capacitación
        $stmt = $conn->prepare("SELECT certificate FROM tbl_training WHERE member_no = :empid");
        $stmt->bindParam(':empid', $empid);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $binaryData = $row['certificate'];
            $pdfFileName = "certificado_capacitacion_" . $empid . "_" . $index . ".pdf";
            file_put_contents($pdfFileName, $binaryData);
            $pdfFiles[] = $pdfFileName;
            $index++;
        }

        // Consulta para obtener certificados de calificación profesional
        $stmt = $conn->prepare("SELECT certificate FROM tbl_professional_qualification WHERE member_no = :empid");
        $stmt->bindParam(':empid', $empid);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $binaryData = $row['certificate'];
            $pdfFileName = "certificado_profesional_" . $empid . "_" . $index . ".pdf";
            file_put_contents($pdfFileName, $binaryData);
            $pdfFiles[] = $pdfFileName;
            $index++;
        }

        // Consulta para obtener otros archivos adjuntos
        $stmt = $conn->prepare("SELECT attachment FROM tbl_other_attachments WHERE member_no = :empid");
        $stmt->bindParam(':empid', $empid);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $binaryData = $row['attachment'];
            $pdfFileName = "adjunto_" . $empid . "_" . $index . ".pdf";
            file_put_contents($pdfFileName, $binaryData);
            $pdfFiles[] = $pdfFileName;
            $index++;
        }

        // Crear el archivo .zip
        $zipFileName = "certificados_$empid.zip";
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
            foreach ($pdfFiles as $pdfFile) {
                $zip->addFile($pdfFile, basename($pdfFile));
            }
            $zip->close();

            // Eliminar los archivos PDF temporales
            foreach ($pdfFiles as $pdfFile) {
                unlink($pdfFile);
            }

            // Enviar el archivo .zip para su descarga
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            header('Content-Length: ' . filesize($zipFileName));

            readfile($zipFileName);

            // Eliminar el archivo .zip temporal después de enviarlo
            unlink($zipFileName);
            exit;
        } else {
            echo "Error al crear el archivo .zip.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("location:./");	
}
?>
