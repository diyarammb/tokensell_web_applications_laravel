<?php
if(isset($_POST['submit']))
{
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
     
    $fileExt = expload('.',$fileName ); 
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg','jpeg','png');
    
    if(in_array($fileActualExt,$allowed )){
        if($fileError === 0)
        {
            if($fileSize < 1000000){
                $fileDestination = 'images/'.$fileName;
                move_uploaded_file($fileTmpName, $fileDestination);
                header("Location: Upload.php?uploadedsuccess");
            }else
            {
                echo "your file size is too big";
            }
            
        }else
        {
            echo "there was error while uploading file";
        }
        
    }
    else{
        echo "you cannot upload file of this type";
    }
?php>