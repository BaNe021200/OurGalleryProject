<?php

namespace App\Controller;

use App\Model\ImageManager;
use App\Model\ThumbnailManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Thumbnails;
use App\Entity\Images;
use App\Controller\ImagesController;

class DownloadController extends Controller
{

    public function download($item)
    {
        return $this->render('images/'.$item.'/download'.$item.'.html.twig',[

        ]);
    }


    public function upload($item)
    {
        $messages=[];

        foreach ($_FILES as $file)
        {
            if($file['error']== UPLOAD_ERR_NO_FILE)
            {
                continue;
            }


            $destinationPath='img/'.$item.'/'.$file['name'];
            $temporaryPath= $file['tmp_name'];
            if(move_uploaded_file($temporaryPath,$destinationPath))
            {
                $messages[] = "le fichier ".$file['name']." a été correctement uploadé";
                $entityManager = $this->getDoctrine()->getManager();
                $image = new Images();


                $image->setDirname('img/'.$item);
                $image->setBasename($file['name']);
                $entityManager->persist($image);
                $entityManager->flush();
                $imageId= $image->getId();

                $thumbnail = new Thumbnails();
                $thumbnail->setImagesId($imageId);
                $thumbnail->setDirname('img/'.$item.'/thumbs/');
                $thumbnail->setBasename($file['name']);
                $entityManager->persist($thumbnail);
                $entityManager->flush();




            }
            else
            {
                $messages[] = "le fichier ".$file['name']." n'a pas été correctement uploadé";
            }
            //$this->thumbNails(500,300,$item);
            $this->cropImagesUpload($item);


        }
        return $this->render('images/'.$item.'/success'.$item.'.html.twig',[
            'message' => $messages,
        ]);
    }

    /*public function getImages($item){

        $messages= [];
        $imgs = glob('img/'.$item.'/*.jpg');
        foreach ($imgs as $img)
        {
            $imgBasename[] = basename($img);
        }
        //var_dump($imgBasename);

        $thumbs = glob('img/'.$item.'/thumbs/*.jpg');
        foreach ($thumbs as $thumb)
        {
            $thumbBasename[] = basename($thumb);
        }
        $data = array_diff($imgBasename,$thumbBasename);

        if(!empty($data))
        {
            foreach ($data as $datum)
            {
                $picture= 'img/'.$item.'/'.$datum ;
                $image = imagecreatefromjpeg($picture);
                $size = min(imagesx($image), imagesy($image));
                $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
                if ($im2 !== FALSE) {
                    imagejpeg($im2, 'img/'.$item.'/thumbs/' . $datum);
                }
                $imageManager = new ImageManager();
                $imageItem = new Images();


                $imageItem->setDirname('img/'.$item);
                $imageItem->setBasename($datum);
                $images = $imageManager->create($imageItem);
                foreach ($images as $image)
                {
                    //var_dump($image->getId());
                    $imageId= $image->getId();

                    $thumbnailManager = new ThumbnailManager();
                    $thumbnail = new Thumbnails();
                    $thumbnail->setImagesId($imageId);
                    $thumbnail->setDirname('img/'.$item.'/thumbs/');
                    $thumbnail->setBasename($datum);
                    $thumbnail = $thumbnailManager->create($thumbnail);
                }

                if ($thumbnail)
                {
                    $messages[]= "vos photos sont à jour";
                }else
                {
                    $messages[]="la mise à jour a échoué";
                }
                return $this->render('images/'.$item.'/success'.$item.'.html.twig',[
                    'message' => $messages,
                ]);



            }
        }else{
            $messages[]="Il n'y a rien à mettre à jour";
            return $this->render('images/'.$item.'/success'.$item.'.html.twig',[
                'message' => $messages,
            ]);
        }

    }*/

    public function getImages($item){
       return $this->cropUpdate($item);


    }

    public function cropImages($item,$pictures){



        $images=$pictures;
        foreach ($images as $image){


            $src= $image;
            $infoName= pathinfo($src);
            $cropName=$infoName['basename'];
            $image = imagecreatefromjpeg($src);
            $size = min(imagesx($image), imagesy($image));
            $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
            if ($im2 !== FALSE) {
                imagejpeg($im2, 'img/'.$item.'/thumbs/' . $cropName);
            }
                $entityManager = $this->getDoctrine()->getManager();
                $image = new Images();


                $image->setDirname('img/'.$item);
                $image->setBasename($cropName);
                $entityManager->persist($image);
                $entityManager->flush();
                $imageId= $image->getId();

                $thumbnail = new Thumbnails();
                $thumbnail->setImagesId($imageId);
                $thumbnail->setDirname('img/'.$item.'/thumbs/');
                $thumbnail->setBasename($cropName);
                $entityManager->persist($thumbnail);
                $entityManager->flush();



                if ($thumbnail)
                {
                    $messages[]= "vos photos sont à jour";
                }else
                {
                    $messages[]="la mise à jour a échoué";
                }

                return $this->render('images/'.$item.'/success'.$item.'.html.twig',[
                    'message' => $messages,
                ]);




        }

    }

    public function cropImagesUpload($item){



        $images=glob('img/'.$item.'/*.jpg');
        foreach ($images as $image){


            $src= $image;
            $infoName= pathinfo($src);
            $cropName=$infoName['basename'];
            $image = imagecreatefromjpeg($src);
            $size = min(imagesx($image), imagesy($image));
            $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
            if ($im2 !== FALSE) {
                imagejpeg($im2, 'img/'.$item.'/thumbs/' . $cropName);

                /*$cropFile = new App\Model\Projet5_images();
                $cropFile
                    ->setUserId(intval($_COOKIE['ID']))
                    ->setDirname('users/img/user/'.$_COOKIE['username'].'/crops')
                    ->setFilename($cropName.'-cropped')
                    ->setExtension('jpg');
                $cropManager=new App\Model\ImagesManager();
                $addCroppedFile = $cropManager->create($cropFile);*/


            }
        }

    }

    public function cropButton(){



        $images=glob('img/crops/*.jpg');var_dump($images);
        foreach ($images as $image){


            $src= $image;
            $infoName= pathinfo($src);
            $cropName=$infoName['basename'];
            $image = imagecreatefromjpeg($src);
            $size = min(imagesx($image), imagesy($image));
            $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
            if ($im2 !== FALSE) {
                imagejpeg($im2, 'img/crops/cropThumbs/' . $cropName);

                /*$cropFile = new App\Model\Projet5_images();
                $cropFile
                    ->setUserId(intval($_COOKIE['ID']))
                    ->setDirname('users/img/user/'.$_COOKIE['username'].'/crops')
                    ->setFilename($cropName.'-cropped')
                    ->setExtension('jpg');
                $cropManager=new App\Model\ImagesManager();
                $addCroppedFile = $cropManager->create($cropFile);*/






            }
        }
        return $this->render('download.html.twig');
    }

    public function cropUpdate($item)
    {

        $messages = [];
        $imgs = glob('img/' . $item . '/*.jpg');
        foreach ($imgs as $img) {
            $imgBasename[] = basename($img);

        }

        //var_dump($imgBasename);die;
        $thumbs = glob('img/' . $item . '/thumbs/*.jpg');
        foreach ($thumbs as $thumb) {
            $thumbBasename[] = basename($thumb);
        }



        @$data = array_diff(@$imgBasename, @$thumbBasename);

        if(empty($imgBasename)){
            $messages[]="Votre dossier photos est vide !";
            return $this->render('images/' . $item . '/success' . $item . '.html.twig', [
                'message' => $messages,
            ]);
        }elseif(empty($thumbBasename)){
            $messages[]="Votre dossier est vide. Avant de pouvoir le mettre à jour il vous faut utiliser la commande dowload au moins une fois pour uploader une photo. Dès la deuxième photos vous pourrez utiliser la commande update";
            return $this->render('images/' . $item . '/success' . $item . '.html.twig', [
                'message' => $messages,
            ]);


        }else
            {



        foreach ($data as $datum) {
            $pictures[] = 'img/' . $item . '/' . $datum; //var_dump($data);die;
        }


        if (!empty($data)) {



            $images = $pictures;
            foreach ($images as $image) {


                $src = $image;
                $infoName = pathinfo($src);
                $cropName = $infoName['basename'];//var_dump($image);die;
                $image = imagecreatefromjpeg($src);
                $size = min(imagesx($image), imagesy($image));
                $im2 = imagecrop($image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
                if ($im2 !== FALSE) {
                    imagejpeg($im2, 'img/'.$item.'/thumbs/' . $cropName);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $image = new Images();


                $image->setDirname('img/'.$item);
                $image->setBasename($cropName);
                $entityManager->persist($image);
                $entityManager->flush();
                $imageId= $image->getId();

                $thumbnail = new Thumbnails();
                $thumbnail->setImagesId($imageId);
                $thumbnail->setDirname('img/'.$item.'/thumbs/');
                $thumbnail->setBasename($cropName);
                $entityManager->persist($thumbnail);
                $entityManager->flush();




            }
            if($thumbnail)
            {
                $messages[]="Vos photos sont maintenant à jours";
            }

        }else {
            $messages[] = "Il n'y a rien à mettre à jour";
        }
            return $this->render('images/' . $item . '/success' . $item . '.html.twig', [
                'message' => $messages,
            ]);
        }
    }

    public function cropcenter($image){
        //$images=glob('users/img/user/'.$_COOKIE['username'].'/*.jpg');

        $src= $image;

        $infoName= pathinfo($src);
        $cropName=$infoName['filename'];
        $image = imagecreatefromjpeg($src);
        $crop_width = imagesx($image);
        $crop_height = imagesy($image);

        $size = min($crop_width, $crop_height);


        if($crop_width >= $crop_height) {
            $newx= ($crop_width-$crop_height)/2;

            $im2 = imagecrop($image, ['x' => $newx, 'y' => 0, 'width' => $size, 'height' => $size]);
        }
        else {
            $newy= ($crop_height-$crop_width)/2;

            $im2 = imagecrop($image, ['x' => 0, 'y' => $newy, 'width' => $size, 'height' => $size]);
        }


        imagejpeg($im2, 'users/img/user/'.$_COOKIE['username'].'/crops/'.$cropName.'-cropped-center.jpg',90);


    }




    public function downloadPulls()
    {

        return ($this->download('Pulls'));
    }


    public function uploadPulls()
    {
        return($this->upload("Pulls"));
    }

    public function downloadCreapulka()
    {

        return ($this->download('Creapulka'));
    }

    public function uploadCreapulka()
    {
        return($this->upload("Creapulka"));
    }



    public function downloadFursWomen()
    {

        return ($this->download("fursWomen"));
    }

    public function downloadFursMen()
    {

        return ($this->download("fursMen"));
    }

    public function uploadFursWomen()
    {
        return($this->upload("fursWomen"));
    }

    public function uploadFursMen()
    {
        return($this->upload("fursMen"));
    }

    public function downloadDoudounes()
    {

        return ($this->download("Doudounes"));
    }

    public function uploadDoudounes()
    {
        return($this->upload("Doudounes"));
    }

    public function downloadWax()
    {

        return ($this->download("Wax"));
    }

    public function uploadWax()
    {
        return($this->upload("Wax"));
    }

    public function updateFurWomen()
    {
        return $this->getImages('fursWomen');
        //return ($this->update('fursWomen'));
    }

    public function updateDoudounes()
    {
        return $this->getImages('doudounes');
        // return ($this->update('doudounes'));
    }

    public function updatePulls()
    {
        return $this->getImages('Pulls');
        //return ($this->update('Pulls'));
    }

    public function updateCreapulka()
    {
        return $this->getImages('Creapulka');
        //return ($this->update('Pulls'));
    }


}
