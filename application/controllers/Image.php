<?php
/**
 * Developer: Распутний Сергей Викторович
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Image extends CI_Controller
{
    public function index()
    {
        if ($this->input->get('img', true)) {
            $file = $this->input->get('img', true);
            if(filter_var($file, FILTER_VALIDATE_URL)){

                $url = str_replace('.http', 'http',$file);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                $contents = curl_exec($ch);
                curl_close($ch);

                if(!$contents){

                    $this->resize();
                    die();
                }

                $ext = explode('.',$file);
                $ext = end($ext);

                file_put_contents('./uploads/resise.'.$ext,$contents);

                if(!getimagesize('./uploads/resise.'.$ext)){
                    $this->resize();
                    die();
                }

                $this->resize('/uploads/resise.'.$ext);
            }else{
                $this->resize($file);
            }
        }else{
            $this->resize();
        }
    }

    private function resize($file = false){


        if(!$file || !$this->fileExists('.'.$file)){
            $file = theme_url().'img/no_image.png';
        }

        $config['image_library'] = 'gd2';
        $config['source_image'] = '.'.$file;
        $config['maintain_ratio'] = true;
        $config['dynamic_output'] = true;

        if($this->input->get('width')){
            $config['width'] = (int)$this->input->get('width');
        }
        if($this->input->get('height')){
            $config['height'] = (int)$this->input->get('height');
        }

        $this->load->library('image_lib', $config);

        $this->image_lib->resize();
    }

    private function fileExists($path){
        if(is_dir($path) || !file_exists($path)){
            return false;
        }
        return true;
    }
}
