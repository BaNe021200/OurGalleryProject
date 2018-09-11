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





    public function item($item)
    {

        $dir = 'img/'.$item.'/thumbs/';


        $bg_ramdom2 = mt_rand(1, 2);
        $bg_ramdom3 = mt_rand(1, 3);
        $bg_ramdom6 = mt_rand(1, 6);

        $thumbs = $this->getDoctrine()
            ->getRepository(Thumbnails::class)
            ->findByDirname($dir);


        $tManager = new ThumbnailManager();
        $tables = $tManager->readImgId($item);

        return $this->render('images/'.$item.'/'.$item.'.html.twig',[

            'thumbs' => $thumbs,
            'tables' => $tables,
            'bg_ramdom' => $bg_ramdom2,
            'bg_ramdom3' => $bg_ramdom3,
            'bg_ramdom6' =>$bg_ramdom6,


        ]);
    }

    public function Pulls()
    {

        return ($this->item('Pulls'));
    }

    public function Creapulka()
    {

        return ($this->item('Creapulka'));
    }

    public function Delicious_Sev()
    {

        return ($this->item('Delicious_Sev'));
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

    public function openExplo($item)
    {
     $explo=  exec("C:\WINDOWS\\explorer.exe /e,/select,C:\wamp64\www\PhpTraining\pinterest\pinterest2\public\img\\".$item."\\thumbs");

        return $this->redirect($this->item($item));


    }



    public function listImages($item)
    {
        $tManager = new ThumbnailManager();
        $tables = $tManager->readImgId($item);

        return $this->render('images/'.$item.'/delete'.$item.'.html.twig',[
            'tables' => $tables,

        ]);

    }
    public function listDoudounes()
    {
        return ($this->listImages('Doudounes'));
    }






}
