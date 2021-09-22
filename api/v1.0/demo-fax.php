<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Test</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<style>
    .bs-example{
        margin: 20px;        
    }
</style>
</head>
<body>
<div class="bs-example">
<form id="data" method="post" enctype="multipart/form-data">
  <div class="form-group" >
    <label for="exampleFormControlInput1">Title</label>
    <input type="text" name="title" class="form-control" id="title" placeholder="name">
  </div>
  <div class="form-group">
    <label for="exampleFormControlInput1">Fax Number</label>
    <input type="text" name="number" class="form-control" id="number" placeholder="1234">
  </div>
  <div class="form-group">
    <label for="exampleFormControlFile1">File Upload</label>
    <input type="file" name="file" id="file" class="form-control-file" id="exampleFormControlFile1">
  </div>
  <div class="form-group">
    <label for="tru_allowed">Retry</label>
    <select class="form-control" name="tru_allowed" id="tru_allowed">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
    </select>
  </div>
  <button type="submit"> submit</button>
</form>
</div>
<script>


$("form#data").submit(function(e) {
    e.preventDefault();    
    // var formData = new FormData(this);

    // var form_data = new FormData();                  
    // form_data.append('file', $('#file')[0].files[0]);
    // form_data.append('title', $('#title').val());
    // form_data.append('number', $('#number').val());
    // form_data.append('tru_allowed', $('#tru_allowed').val());
    // $.ajax({
    //     url: 'fax-details.php', // point to server-side PHP script 
    //     dataType: 'text',  // what to expect back from the PHP script, if anything
    //     cache: false,
    //     contentType: false,
    //     processData: false,
    //     data: form_data,                         
    //     type: 'post',
    //     success: function(php_script_response){
    //         console.log(php_script_response) // display response from the PHP script, if any
    //     }
    //  });
    const blobFile = $('#file')[0].files[0];
    let formData = new FormData();
    formData.append("file", blobFile);
     $.ajax({
        url: 'https://myfax.mconnectapps.com/api/messages/documents/62/media',    //my servlet url
        type: 'PUT',
        processData: false,
        contentType: false,
        data: {
            data: formData
        }, 
        success: function (php_script_response) {
          console.log(php_script_response);
            console.log("profile pic updated!");
        }
    });



    // let formData = new FormData();
    // formData.append("fileToUpload", $('#file')[0].files[0]);
    // const xhr = new XMLHttpRequest();
    // xhr.open('PUT', 'http://myfax.mconnectapps.com/api/messages/documents/62/media');
    // xhr.onload = () => {
    //     console.log("profile updated");
    // };
    // xhr.send(formData);

});
</script>
</body>
</html>                            