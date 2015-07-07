<?php
/**
* Class upload image
* @author JosT
* @date   Jun 23 2015
*/
class Upload
{
    public function file_image($source,$folder)
	{
		$flag = true;
		if(!empty($_FILES['image']['name'])){
			// Create folder
			if(!is_dir($source.$folder)){
				mkdir($source.$folder, 0770);
			}
			$path     = $source.$folder."/";
			$image    = $_FILES['image']['name'];
			$tmp_name = $_FILES['image']['tmp_name'];
			// Check type file image
			$type = substr($_FILES['image']['type'], 6);
			if($type == "png" || $type == "jpeg" || $type == "jpg"){
				move_uploaded_file($tmp_name, $path.$image);
			} else {
				$flag = false;
				die("Type $type not supported !!!");
			}
		} else {
			$flag = false;
		}
		return $flag;
	}
	/**
	 * [upload multi file]
	 * @param $source [Đường dẫn nơi chứa file upload]
	 * @param $folder [Tên thư mục sẽ tạo khi upload]
	 */
	public function upload_multi_file($source,$folder)
	{
		$flag = true;
		if(!empty($_FILES['images']['name'][0])){
			// Check folder
			// Create folder
			if(!is_dir($source.$folder)){
				mkdir($source.$folder, 0770);
			} else {
				$this->delete_folder($source.$folder);
				mkdir($source.$folder, 0770);
			}
			// Check type file image
			foreach ($_FILES['images']['type'] as $key => $value) {
				$type = substr($value, 6);
				if($type == "png" || $type == "jpeg" || $type == "jpg"){
					//Loop through each file
					for ($i=0; $i < count($_FILES['images']['name']) ; $i++) { 
						$tmpPath = $_FILES['images']['tmp_name'][$i];
						$images    = $_FILES['images']['name'][$i];
						if ($tmpPath != null) {
							$path     = $source.$folder."/";
							move_uploaded_file($tmpPath, $path.$images);
						}
					}
				} else {
					$flag = false;
					die("Type $type not supported !!!");
				}
			}
		} else {
			$flag = false;
		}
		return $flag;
	}

	public function delete_file($dirname, $file)
	{
		if (!is_dir($dirname."/".$file)) {
			unlink($dirname."/".$file);
		}
	}

	/**
	 * Delete folder
	 * @param   $dirname [folder cần xóa]
	 */
	public function delete_folder($dirname) 
	{
		$dir_handle = "";
	    if (is_dir($dirname)){
	        $dir_handle = opendir($dirname);
	    }
		if (!$dir_handle){
		    return false;
		}
		while($file = readdir($dir_handle)) {
		    if ($file != "." && $file != ".."){
	            if (!is_dir($dirname."/".$file)){
	                unlink($dirname."/".$file);
	            } else{
	                $this->delete_folder($dirname.'/'.$file);
	            }
		    }   
		 }
		 closedir($dir_handle);
		 rmdir($dirname);
		 return true;
	}

	/**
	 * Delete multi folder
	 * @param  array  $data [các folder cần xóa]
	 * @param  string $path [dường dẫn tới nơi chứa folder cần xóa]
	 */
	public function delete_multi_folder($path, $data = array())
	{
		if(!empty($data)) {
			foreach ($data as $value) {
				$this->delete_folder($path.$value);
			}
		}
	}
}