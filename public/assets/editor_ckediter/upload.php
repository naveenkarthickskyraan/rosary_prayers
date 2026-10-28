<?php

//upload.php

if(isset($_FILES['upload']['name']))
{
   
    $file = $_FILES['upload']['tmp_name'];
    $file_name = $_FILES['upload']['name'];
    $file_name_array = explode(".", $file_name);
    $extension = end($file_name_array);
    $new_image_name = rand() . '.' . $extension;
    // chmod('upload', 0777);
    $allowed_extension = array("jpg", "gif", "png","jpeg", "mp4", "mp3" , "webp");
    
    $function_number = $_GET['CKEditorFuncNum'];
    
    $upload_path = "../../uploads/ckeditor_tmp_files/";
    
    require_once("../../ckeditor_config.php");
    
    $base_path = BASE_URL ."uploads/ckeditor_tmp_files/";
    
    if(in_array($extension, $allowed_extension))
    {
        move_uploaded_file($file, $upload_path . $new_image_name);
        
        $url = $base_path . $new_image_name;
        $message = '';
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('$function_number','$url', '$message');</script>";
    }
    else
    {
        $url = 'uploads/ckeditor_tmp_files/' . $new_image_name;
        $message = 'Invalid File format';
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('$function_number','$url', '$message');</script>";
    }
}

?>