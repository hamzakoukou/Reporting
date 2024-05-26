<?php

require_once 'connection.php';


$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true); // Create directory with permissions
}



if (isset($_FILES['refFile'], $_FILES['annexeFile'])) {
    $refFilePath = $upload_dir . 'Fichier de correspondance de noms.xlsx'; //Reference file path
    $annexeFilePath = $upload_dir . 'Annexe.xlsx'; // Annexe file path
  $refFile = $_FILES['refFile']['tmp_name'];
  $annexeFile = $_FILES['annexeFile']['tmp_name'];
  if (!file_exists($refFilePath) || !file_exists($annexeFilePath)) {
      move_uploaded_file($refFile, $refFilePath);
      move_uploaded_file($annexeFile, $annexeFilePath);

      // Pass database connection details to the Python script
      $command = escapeshellcmd("python3 verify_data.py " . escapeshellarg($refFilePath) . " " . escapeshellarg($annexeFilePath) . " " . escapeshellarg($host) . " " . escapeshellarg($db_name) . " " . escapeshellarg($username) . " " . escapeshellarg($password));
      $output = shell_exec($command);
      if ($output === null) {
          echo 'Error: Python script did not execute.';
          exit();
      }
      $result = json_decode($output, true);

      if ($output) {
          echo 'Success: File uploaded and processed successfully.';
      } else {
          echo 'Failure: File upload or processing failed.';
      }
  }
}

// Processing General Files //Collaborator & Production & Direct Charges
if (isset($_FILES['collaborators'], $_FILES['production'], $_FILES['charges'])) {
  $collaboratorsPath = $upload_dir . $_FILES['collaborators']['name'];
  $productionPath = $upload_dir . $_FILES['production']['name'];
  $chargesPath = $upload_dir . $_FILES['charges']['name'];

  
    move_uploaded_file($_FILES['collaborators']['tmp_name'], $collaboratorsPath) ;
    move_uploaded_file($_FILES['production']['tmp_name'], $productionPath) ;
    move_uploaded_file($_FILES['charges']['tmp_name'], $chargesPath); 
    
    {  
      // Call Python script to verify data and check names
      $command = escapeshellcmd("python3 verify_general_files.py " . escapeshellarg($collaboratorsPath) . " " . escapeshellarg($productionPath) . " ". escapeshellarg($chargesPath) . " ". escapeshellarg($refFilePath) . " " . escapeshellarg($host) . " " . escapeshellarg($db_name) . " " . escapeshellarg($username) . " " . escapeshellarg($password));
      $output = shell_exec($command);
      $result = json_decode($output, true);

      if ($output === null) {
        echo 'Error: Python script did not execute.';
        exit();
    }
    $result = json_decode($output, true);

    if ($output) {
        echo 'Success: File uploaded and processed successfully.';
    } else {
        echo 'Failure: File upload or processing failed.';
    }

    } 
}


// Check if the file has been uploaded
if (isset($_FILES['incharges'])) {
    $inchargesPath = $upload_dir . $_FILES['incharges']['name'];
    $collaboratorsPath = $upload_dir . 'Fichier collaborateurs.xlsx';
    move_uploaded_file($_FILES['incharges']['tmp_name'], $inchargesPath);

    $command = escapeshellcmd("python3 preprocess_incharges.py " . escapeshellarg($inchargesPath) . " " . escapeshellarg($host) . " " . escapeshellarg($db_name) . " " . escapeshellarg($username) . " " . escapeshellarg($password). " " . escapeshellarg($collaboratorsPath));
    $output = shell_exec($command);
    if ($output === null) {
        echo 'Error: Python script did not execute.';
        exit();
    }
    if ($output) {
        echo 'Success: File uploaded and processed successfully.';
    } else {
        echo 'Failure: File upload or processing failed.';
    }
}

