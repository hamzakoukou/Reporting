<!DOCTYPE html>
<html>
<head>
  <title>Excel File Upload</title>
  <style>
    /* Optional styling for a cleaner look */
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    h1 {
      margin-bottom: 10px;
    }
    .file-input {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <h1>Upload Excel Files</h1>
  <form action="process_upload.php" method="post" enctype="multipart/form-data">
    <div class="file-input">
      <label for="file1">Fichier Collaborateur:</label>
      <input type="file" id="file1" name="files[]" multiple required>
    </div>
    <div class="file-input">
      <label for="file2">Fichier des Charges Directs:</label>
      <input type="file" id="file2" name="files[]" multiple required>
    </div>
    <div class="file-input">
      <label for="file3">Fichier de Production:</label>
      <input type="file" id="file3" name="files[]" multiple required>
    </div>
    <div class="file-input">
      <label for="file4">Fichier des Charges InDirects:</label>
      <input type="file" id="file4" name="files[]" multiple required>
    </div>
    <br>
    <input type="submit" value="Upload">
  </form>
</body>
</html>
