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
                if(!getimagesize($file)){
                    $this->resize();
                    die();
                }
                $image_type = (exif_imagetype($file));
                switch($image_type){
                    case 1:
                        $ext = '.gif';
                        break;
                    case 2:
                        $ext = '.jpg';
                        break;
                    case 3:
                        $ext = '.png';
                        break;
                    case 6:
                        $ext = '.bmp';
                        break;
                    default:
                        $ext = '.jpg';
                        break;
                }

                $url = str_replace('.http', 'http',$file);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                $file = curl_exec($ch);
                curl_close($ch);

                if(!$file){
                    $this->resize();
                    die();
                }
                file_put_contents('./uploads/resise'.$ext,$file);
               $this->resize('/uploads/resise'.$ext);
            }else{
                $this->resize($file);
            }
        }else{
            $this->resize();
        }
    }

    private function resize($file = false){


        if(!$file || !file_exists('.'.$file)){
            $file = theme_url().'img/no_image.png';
        }

        $config['image_library'] = 'imagick';
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
}
