<?php
echo UploadImage();
 function UploadImage()
 {
     $image = '';
     $error_message = '';
     $ip = $_POST['ip'];
     $list_allow_ip = array('123.30.51.36');
     

     if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $folder = $_POST['file'];
         $file_error = $_POST['file_error'];
         $file_time = $_POST['file_time'];
         $file_upload = 'upload/' . $folder . '/' . $file_time;
         if (!file_exists('upload/' . $folder)) {
             mkdir('upload/' . $folder);
         }
         if (!file_exists('upload/' . $folder . '/' . $file_time)) {
             mkdir('upload/' . $folder . '/' . $file_time);

         }
         if(isset($_POST["filename"]) && $file_error == 0){
             $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "jpeg" => "image/jpeg");
             // Lấy thông tin file bao gồm tên file, loại file, kích cỡ file
             $filename = $_POST["filename"];
             $filetype = $_POST["file_type"];
             $filesize = $_POST["file_size"];
             $filedata = $_POST["filedata"];
             $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
             // Nếu không đúng định dạng file thì báo lỗi
             if(!in_array($ip, $list_allow_ip)){
                return json_encode( array('error_message' =>  'IP bị chặn', 'image' => $image ),true);
             }
             if(!array_key_exists($ext, $allowed)) {
                
                  return json_encode( array('error_message' =>  'Sai định dạng file', 'image' => $image ),true);
             }
             $max_size = 10 * 1024 * 1024;
             if($filesize > $max_size) {
                return json_encode( array('error_message' =>  'Kích thước ảnh không vượt quá 10MB', 'image' => $image ),true);
                
             };
             if(in_array($filetype, $allowed))
             {
                 if(file_exists($file_upload.'/'. $filename)){
                     $error_message = 'Ảnh đã tồn tại';
                 } else{
                     // Hàm move_uploaded_file sẽ tiến hành upload file lên thư mục upload

                     copy($filedata, $file_upload.'/' .$filename);
                     // Thông báo thành công
                     $error_message ='';
                     $image = $filename;

                 }

             }else{
                 $error_message = 'Có vấn đề xảy ra khi upload file';
                 // Kiểm tra xem file đã tồn tại chưa, nếu rồi thì báo lỗi, không thì tiến hành upload


             }

         }
         else{
             $error_message = $_POST['file_error'];

         }
         return json_encode( array('error_message' =>  $error_message , 'image' => $image ),true);

     }
 }




?>