<?php

namespace App\Controller;

use App\Entity\Thumbnails;
use App\Entity\Images;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Controller\DownloadController;
use App\Model\ThumbnailManager;
use App\Model\ImageManager;

class ImagesController extends Controller
{




    public function index(Request $request)
    {

        $dir = 'img/doudounes/thumbs/';


        $bg_ramdom2 = mt_rand(1, 2);
        $bg_ramdom3 = mt_rand(1, 3);
        $bg_ramdom6 = mt_rand(1, 6);

        $em = $this->getDoctrine()->getManager();

        $thumbsQuery= $em->getRepository(Thumbnails::class)

            ->findByDirname($dir);

        $paginator = $this->get('knp_paginator');


        $thumbs = $paginator->paginate($thumbsQuery, $request->query->getInt('page',1),12


        );



        /*$thumbs = $this->getDoctrine()
            ->getRepository(Thumbnails::class)
            ->findByDirname($dir);*/




        return $this->render('images/Doudounes/Doudounes.html.twig',[

            'thumbs' => $thumbs,
            'bg_ramdom' => $bg_ramdom2,
            'bg_ramdom3' => $bg_ramdom3,
            'bg_ramdom6' =>$bg_ramdom6,


        ]);


    }

    public function item($item, Request $request)
    {


        $dir = 'img/'.$item.'/thumbs/';


        $bg_ramdom2 = mt_rand(1, 2);
        $bg_ramdom3 = mt_rand(1, 3);
        $bg_ramdom6 = mt_rand(1, 6);

        $em = $this->getDoctrine()->getManager();

        $thumbsQuery= $em->getRepository(Thumbnails::class)

            ->findByDirname($dir);

        $paginator = $this->get('knp_paginator');


        $thumbs = $paginator->paginate($thumbsQuery, $request->query->getInt('page',1),12


        );



        /*$thumbs = $this->getDoctrine()
            ->getRepository(Thumbnails::class)
            ->findByDirname($dir);*/




        return $this->render('images/'.$item.'/'.$item.'.html.twig',[

            'thumbs' => $thumbs,
            'bg_ramdom' => $bg_ramdom2,
            'bg_ramdom3' => $bg_ramdom3,
            'bg_ramdom6' =>$bg_ramdom6,


        ]);
    }

    public function Doudounes(Request $request)
    {
        return ($this->item('Doudounes', $request));
    }

    public function Desigual(Request $request)
    {
        return ($this->item('Desigual',$request));
    }

    public function Pulls(Request $request)
    {

        return ($this->item('Pulls',$request));
    }

    public function Creapulka(Request $request)
    {

        return ($this->item('Creapulka',$request));
    }

    public function Delicious_Sev(Request $request)
    {

        return ($this->item('Delicious_Sev'));
    }

    public function FursWomen(Request $request)
    {
        return ($this->item('fursWomen',$request));
    }

    public function FursMen(Request $request)
    {
        return ($this->item('fursMen',$request));
    }

    public function Wax(Request $request)
    {
        return ($this->item('Wax',$request));
    }

    public function openExplo($item)
    { /* la fonction openExplo permet d'ouvrir le fichier d'un thème dans l'explorateur windows lorsque celui-ci est vide*/

        $explo=  exec("C:\WINDOWS\\explorer.exe /e,/select,C:\wamp64\www\PhpTraining\pinterest\pinterest2\public\img\\".$item."\\thumbs");

        return $this->redirect($this->item($item));


    }










}
