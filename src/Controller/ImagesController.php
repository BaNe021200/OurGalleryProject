<?php

namespace App\Controller;

use App\Entity\Thumbnails;
use App\Entity\Images;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Controller\DownloadController;
use App\Model\ThumbnailManager;
use App\Model\ImageManager;

class ImagesController extends Controller
{




    public function index()
    {
        return $this->render('images/Doudounes/Doudounes.html.twig', [
            'controller_name' => 'ImagesController',
        ]);
    }


    /*public function getImages($item){

        $images= glob('img/'.$item.'/*.jpg');

        foreach ($images as $image)
        {

            $mins = glob('img/'.$item.'/thumbs/*.jpg');
            foreach ($mins as $min)
            {
                $infoMin = pathinfo($min);
                $dirnameMin = $infoMin['dirname'];
                $basenameMin = $infoMin['basename'];
                @$srcMin = $dirnameMin.$basenameMin;
                //var_dump($srcMin);
            }

            if(!file_exists(@$srcMin))
            {
                $this->cropImages($item);
                $infopicture = pathinfo($image);
                $basename = $infopicture['basename'];

                //$entityManager = $this->getDoctrine()->getManager();

                $imageManager = new ImageManager();
                $imageItem = new Images();


                $imageItem->setDirname('img/'.$item);
                $imageItem->setBasename($basename);
                $images = $imageManager->create($imageItem);

                foreach ($images as $image)
                {
                    var_dump($image->getId());
                    $imageId= $image->getId();

                    $thumbnailManager = new ThumbnailManager();
                    $thumbnail = new Thumbnails();
                    $thumbnail->setImagesId($imageId);
                    $thumbnail->setDirname('img/'.$item.'/thumbs/');
                    $thumbnail->setBasename($basename);
                    $thumbnail = $thumbnailManager->create($thumbnail);
                }




            }else{}

        }

    }*/

    /*public function cropImages($item){



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




            }
        }

    }*/


    public function item($item)
    {

        $dir = 'img/'.$item.'/thumbs/';
        $bg_ramdom = mt_rand(1, 2);
        $thumbs = $this->getDoctrine()
            ->getRepository(Thumbnails::class)
            ->findByDirname($dir);
        //$this->getImages($item);

        return $this->render('images/'.$item.'/'.$item.'.html.twig',[

            'thumbs' => $thumbs,
            'bg_ramdom' => $bg_ramdom,

        ]);
    }

    public function Pulls()
    {

        return ($this->item('Pulls'));
    }

    public function FursWomen()
    {
        return ($this->item('fursWomen'));
    }

    public function FursMen()
    {
        return ($this->item('fursMen'));
    }



    public function Doudounes()
    {
        return ($this->item('Doudounes'));
    }

    public function Wax()
    {
        return ($this->item('Wax'));
    }



}
